<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>コミュニティ</title>
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

        // 参加・退会処理
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['community_id'])) {
            if (!isset($_SESSION['username'])) {
                echo '<div class="alert">操作するにはログインしてください。<a href="login.php">ログイン</a></div>';
            } else {
                $action = $_POST['action'];
                $cid = (int)$_POST['community_id'];
                $uname = $_SESSION['username'];
                if ($action === 'join') {
                    $ins = $mysqli->prepare('INSERT INTO community_members (community_id, user_name) VALUES (?, ?)');
                    $ins->bind_param('is', $cid, $uname);
                    $ins->execute();
                    $ins->close();
                } elseif ($action === 'leave') {
                    $del = $mysqli->prepare('DELETE FROM community_members WHERE community_id = ? AND user_name = ?');
                    $del->bind_param('is', $cid, $uname);
                    $del->execute();
                    $del->close();
                }
                header('Location: community.php');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titles'])) {
            $titles = trim($_POST['titles']);
            $descriptions = isset($_POST['descriptions']) ? trim($_POST['descriptions']) : '';
            if (!empty($titles)) {
                $stmt = $mysqli->prepare('INSERT INTO communities (titles, descriptions) VALUES (?, ?)');
                if ($stmt) {
                    $stmt->bind_param('ss', $titles, $descriptions);
                    if ($stmt->execute()) {
                        $community_id = $stmt->insert_id;
                        echo '<div class="alert success"><strong>' . htmlspecialchars($titles, ENT_QUOTES, 'UTF-8') . '</strong> のコミュニティを作成しました。<br>';
                        echo htmlspecialchars($descriptions, ENT_QUOTES, 'UTF-8') . '<br>';
                        echo '<a class="btn" href="community.php">一覧に戻る</a></div>';
                    } else {
                        echo '<div class="alert">エラー: ' . $stmt->error . '</div>';
                    }
                    $stmt->close();
                }
            } else {
                echo '<div class="alert">コミュニティ名を入力してください。</div>';
            }
        } else {
            echo '<h2 class="section-title">作成済みコミュニティ一覧</h2>';
            $result = $mysqli->query('SELECT id, titles, descriptions, created_at FROM communities ORDER BY created_at DESC');
            if ($result && $result->num_rows > 0) {
                echo '<div class="community-list">';
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="community-card card" style="margin-bottom:20px;">';
                    echo '<div class="community-title"><strong>' . htmlspecialchars($row['titles'], ENT_QUOTES, 'UTF-8') . '</strong></div>';
                    echo '<div class="community-desc">' . nl2br(htmlspecialchars($row['descriptions'], ENT_QUOTES, 'UTF-8')) . '</div>';
                    echo '<div class="community-meta">作成日時: ' . $row['created_at'] . '</div>';
                    echo '<div class="community-actions" style="margin-top:10px; display:flex; gap:10px; align-items:center;">';
                    echo '<a class="btn btn-outline" href="board.php?id=' . $row['id'] . '">掲示板を見る</a>';
                    if (isset($_SESSION['username'])) {
                        $cm = $mysqli->prepare('SELECT id FROM community_members WHERE community_id = ? AND user_name = ?');
                        $cm->bind_param('is', $row['id'], $_SESSION['username']);
                        $cm->execute();
                        $cm->store_result();
                        $is_member = $cm->num_rows > 0;
                        $cm->close();
                        if ($is_member) {
                            echo '<form action="community.php" method="post" style="display:inline;">';
                            echo '<input type="hidden" name="community_id" value="' . $row['id'] . '">';
                            echo '<input type="hidden" name="action" value="leave">';
                            echo '<button class="btn btn-danger" type="submit">退会する</button>';
                            echo '</form>';
                        } else {
                            echo '<form action="community.php" method="post" style="display:inline;">';
                            echo '<input type="hidden" name="community_id" value="' . $row['id'] . '">';
                            echo '<input type="hidden" name="action" value="join">';
                            echo '<button class="btn btn-main" type="submit">参加する</button>';
                            echo '</form>';
                        }
                    } else {
                        echo '<span class="note">(ログインすると参加できます)</span>';
                    }
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
            } else {
                echo '<div class="note">まだコミュニティが作成されていません。</div>';
            }
            echo '<div style="margin-top:32px; text-align:center;">';
            echo '<a class="btn btn-main" style="font-size:1.1em; padding:12px 32px;" href="create-com.php">＋ 新しいコミュニティを作成する</a>';
            echo '</div>';
        }
        $mysqli->close();
        ?>
        <div style="margin-top:32px; text-align:center;">
            <a class="btn btn-outline" href="home.php">ホームに戻る</a>
        </div>
        </div>
    </div>
</body>
</html>