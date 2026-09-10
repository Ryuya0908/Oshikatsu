<?php
session_start();
require_once 'db_connect.php';

// ユーザー名取得
if (isset($_POST['user']) && $_POST['user'] !== '') {
    $user_name = $_POST['user'];
} else {
    $user_name = isset($_GET['user']) ? $_GET['user'] : '';
}
if ($user_name === '') {
    echo 'ユーザーが指定されていません。';
    exit;
}

// 推しキャラ情報の登録処理（POSTで送信された場合）
if (
    isset($_SESSION['username']) &&
    isset($_POST['characterName'], $_POST['animeName'], $_POST['characterImage'])
) {
    // user_oshiテーブルにINSERT
    $target_user = $user_name !== '' ? $user_name : $_SESSION['username'];
    $stmt = $mysqli->prepare('INSERT INTO user_oshi (user, characterName, animeName, characterImage) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $target_user, $_POST['characterName'], $_POST['animeName'], $_POST['characterImage']);
    $stmt->execute();
    $stmt->close();
    // 再取得
    $stmt = $mysqli->prepare('SELECT username, oshi_name, anime_name, oshi_icon FROM users WHERE username = ?');
    $stmt->bind_param('s', $target_user);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    $user_name = $target_user;
} else {
    // ユーザー情報取得
    $stmt = $mysqli->prepare('SELECT username, oshi_name, anime_name, oshi_icon FROM users WHERE username = ?');
    $stmt->bind_param('s', $user_name);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (!$user) {
    echo 'ユーザーが見つかりません。';
    exit;
}

// 参加コミュニティ取得
$cstmt = $mysqli->prepare('SELECT c.id, c.titles FROM communities c INNER JOIN community_members m ON c.id = m.community_id WHERE m.user_name = ?');
$cstmt->bind_param('s', $user_name);
$cstmt->execute();
$communities = $cstmt->get_result();
$cstmt->close();

// 推しキャラ一覧取得
$oshi_list = [];
$ostmt = $mysqli->prepare('SELECT id, characterName, animeName, characterImage FROM user_oshi WHERE user = ?');
$ostmt->bind_param('s', $user_name);
$ostmt->execute();
$oshi_result = $ostmt->get_result();
while ($row = $oshi_result->fetch_assoc()) {
    $oshi_list[] = $row;
}
$ostmt->close();

$my_profile = (isset($_SESSION['username']) && $_SESSION['username'] === $user_name);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>のプロフィール</title>
    <link rel="stylesheet" href="css/diagnosis.css">
    <link rel="stylesheet" href="css/broad.css">
    <style>
        .profile-icon { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; }
        .profile-section { margin-bottom: 20px; }
        .community-btn { margin: 5px; }
    </style>
</head>
<body>
<div class="frame" style="max-width:700px; margin:40px auto; background:#fff; border-radius:20px; box-shadow:0 8px 32px rgba(0,0,0,0.08); padding:32px 24px;">
    <div class="logo" style="margin: 0 auto 24px auto; justify-content:center; align-items:center; gap:8px; display:flex;">
      <div class="logo-icon" style="width:32px; height:32px; border-radius:999px; background:#ff5a9b; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px;">♥</div>
      <div class="logo-text" style="font-size:26px; font-weight:700; color:#ff4b92;">Oshikatu</div>
    </div>
    <h1 style="font-size:2rem; color:#ff4b92; margin-bottom:18px; text-align:center;"><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>のプロフィール</h1>
    <div class="profile-section" style="text-align:center; margin-bottom:32px;">
        <img src="<?php echo htmlspecialchars($user['oshi_icon'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" alt="推しアイコン" class="profile-icon" style="margin-bottom:10px;"><br>
        <strong>推しキャラ名:</strong> <?php echo htmlspecialchars($user['oshi_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?><br>
        <strong>アニメ名:</strong> <?php echo htmlspecialchars($user['anime_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?><br>
        <?php if ($my_profile): ?>
            <a href="Character_entry.php?SUB1=1&user=<?php echo urlencode($user['username']); ?>" class="button" style="display:inline-block; margin:18px 0 0 0; padding:10px 24px; background:#ff5a9b; color:#fff; border-radius:8px; text-decoration:none; font-weight:600;">推しの追加へ</a>
        <?php endif; ?>
        <?php
        if (isset($_GET['from']) && $_GET['from'] === 'diagnosis' && isset($_SESSION['last_diagnosis'])):
            $d = $_SESSION['last_diagnosis'];
        ?>
            <div class="diagnosis-result" style="margin-top:18px; background:#fff0f7; border-radius:8px; padding:12px 0;">
                <h3 style="color:#e0558e;">診断結果</h3>
                <p>性別: <?php echo htmlspecialchars($d['gender'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p>年齢層: <?php echo htmlspecialchars($d['age'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        <?php unset($_SESSION['last_diagnosis']); endif; ?>
    </div>
    <div class="profile-section" style="text-align:center; margin-bottom:32px;">
        <h2 style="color:#ff4b92; font-size:1.2rem; margin-bottom:10px;">推しキャラ一覧</h2>
        <?php if (count($oshi_list) > 0): ?>
            <div style="display:flex;flex-wrap:wrap;gap:18px;justify-content:center;">
            <?php foreach ($oshi_list as $oshi): ?>
                <div style="background:#fff0f7;border-radius:12px;padding:12px 18px;min-width:180px;max-width:220px;box-shadow:0 2px 8px #ffe3f0;">
                    <?php if ($oshi['characterImage']): ?>
                        <img src="<?php echo htmlspecialchars($oshi['characterImage'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($oshi['characterName'], ENT_QUOTES); ?>" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin-bottom:8px;">
                    <?php endif; ?>
                    <div><strong><?php echo htmlspecialchars($oshi['characterName'], ENT_QUOTES); ?></strong></div>
                    <div style="font-size:0.95em;color:#888;">(<?php echo htmlspecialchars($oshi['animeName'], ENT_QUOTES); ?>)</div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color:#888;">登録された推しキャラはありません。</p>
        <?php endif; ?>
    </div>
    <div class="profile-section" style="margin-bottom:32px;">
        <h2 style="color:#ff4b92; font-size:1.2rem; margin-bottom:10px;">参加しているコミュニティ</h2>
        <?php if ($communities && $communities->num_rows > 0): ?>
            <div style="display:flex; flex-wrap:wrap; gap:10px; justify-content:center;">
            <?php while ($c = $communities->fetch_assoc()): ?>
                <a href="board.php?id=<?php echo $c['id']; ?>" class="community-btn button" style="background:#ffe3f0; color:#e0558e; border-radius:6px; padding:8px 16px; text-decoration:none; font-weight:600; border:1px solid #ffb6d5;"><?php echo htmlspecialchars($c['titles'], ENT_QUOTES, 'UTF-8'); ?></a>
            <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p style="color:#888;">参加中のコミュニティはありません。</p>
        <?php endif; ?>
    </div>
    <div style="text-align:center; margin-top:24px;">
      <a href="community.php" style="margin-right:18px; color:#ff4b92; text-decoration:none; font-weight:600;">← コミュニティ一覧に戻る</a>
      <a href="home.php" style="color:#ff4b92; text-decoration:none; font-weight:600;">← ホームに戻る</a>
    </div>
</div>
</body>
</html>
<?php $mysqli->close(); ?>
