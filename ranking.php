<?php
session_start();
require_once 'db_connect.php';

// 推しキャラランキング（例: いいね数順、または単純な登録数順）
// ここでは単純に推しキャラ名・アニメ名ごとの登録数ランキングを表示
$sql = 'SELECT oshi_name, anime_name, oshi_icon, COUNT(*) as cnt FROM users WHERE oshi_name IS NOT NULL AND oshi_name != "" GROUP BY oshi_name, anime_name, oshi_icon ORDER BY cnt DESC LIMIT 20';
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>推しキャラランキング</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .ranking-table { width: 100%; max-width: 600px; margin: 30px auto; border-collapse: collapse; background: #fff; }
        .ranking-table th, .ranking-table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        .ranking-table th { background: #ffecf2; color: #e0558e; }
        .ranking-table img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
        .back-link { display: block; margin: 20px auto; text-align: center; }
    </style>
</head>
<body>
    <h1 style="text-align:center; color:#e0558e;">推しキャラランキング</h1>
    <div class="logo" style="margin: 30px auto 20px auto; justify-content:center;">
      <div class="logo-icon">♥</div>
      <div class="logo-text">Oshikatu</div>
    </div>
    <table class="ranking-table">
        <tr><th>順位</th><th>キャラ画像</th><th>キャラ名</th><th>アニメ名</th><th>登録数</th></tr>
        <?php $rank = 1; if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $rank++; ?></td>
                    <td><?php if ($row['oshi_icon']): ?><img src="<?php echo htmlspecialchars($row['oshi_icon'], ENT_QUOTES); ?>" alt="icon"><?php endif; ?></td>
                    <td><?php echo htmlspecialchars($row['oshi_name'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($row['anime_name'], ENT_QUOTES); ?></td>
                    <td><?php echo $row['cnt']; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">ランキングデータがありません</td></tr>
        <?php endif; ?>
    </table>
    <div class="back-link"><a href="home.php">← ホームに戻る</a></div>
</body>
</html>
<?php $mysqli->close(); ?>
