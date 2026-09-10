<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>コミュニティ作成</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="frame" style="max-width:600px; margin:40px auto;">
        <div class="logo" style="margin: 0 auto 32px auto; justify-content:center; align-items:center; gap:10px; display:flex;">
            <div class="logo-icon">♥</div>
            <div class="logo-text">Oshikatu</div>
        </div>
        <div class="card" style="padding:32px 24px 24px 24px; background:#fff; box-shadow:0 4px 24px rgba(0,0,0,0.10),0 1.5px 4px rgba(0,0,0,0.08); border-radius:18px;">
        <?php
        session_start();
        require_once 'db_connect.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titles = trim($_POST['titles'] ?? '');
            $descriptions = trim($_POST['descriptions'] ?? '');
            if ($titles !== '') {
                $stmt = $mysqli->prepare('INSERT INTO communities (titles, descriptions) VALUES (?, ?)');
                if ($stmt) {
                    $stmt->bind_param('ss', $titles, $descriptions);
                    if ($stmt->execute()) {
                        echo '<div class="alert success">コミュニティ「' . htmlspecialchars($titles, ENT_QUOTES, 'UTF-8') . '」を作成しました。</div>';
                        echo '<div style="text-align:center; margin-top:24px;"><a class="btn btn-main" href="community.php">コミュニティ一覧へ戻る</a></div>';
                    } else {
                        echo '<div class="alert">エラー: ' . $stmt->error . '</div>';
                    }
                    $stmt->close();
                }
            } else {
                echo '<div class="alert">コミュニティ名を入力してください。</div>';
            }
        }
        ?>
        <h2 class="section-title">新しいコミュニティを作成</h2>
        <form action="create-com.php" method="post" style="margin-top:24px;">
            <div class="form-group" style="margin-bottom:18px;">
                <label for="titles">コミュニティ名</label>
                <input type="text" id="titles" name="titles" class="form-control" style="width:100%; padding:8px; border-radius:8px; border:1px solid #ccc;" required>
            </div>
            <div class="form-group" style="margin-bottom:24px;">
                <label for="descriptions">説明 (任意)</label>
                <textarea id="descriptions" name="descriptions" class="form-control" style="width:100%; padding:8px; border-radius:8px; border:1px solid #ccc; min-height:80px;"></textarea>
            </div>
            <div style="text-align:center;">
                <button class="btn btn-main" type="submit" style="font-size:1.1em; padding:10px 32px;">作成する</button>
            </div>
        </form>
        <div style="margin-top:32px; text-align:center;">
            <a class="btn btn-outline" href="community.php">コミュニティ一覧へ戻る</a>
        </div>
        </div>
    </div>
</body>
</html>