<?php
session_start(); // セッション開始を必ず明示
ob_start(); ?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アニメキャラクター登録画面</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <?php
    //if (!isset($_POST['推し活診断']) || !isset($_POST['プロフィールからの登録'])) {
    //    exit('不正なアクセスです。');
    //}
    ?>
    <main>
            <div class="logo" style="margin: 0 auto 24px auto; justify-content:center; align-items:center; gap:8px; display:flex;">
            <div class="logo-icon" style="width:32px; height:32px; border-radius:999px; background:#ff5a9b; display:flex; align-items:center; justify-content:center; color:#fff; font-size:18px;">♥</div>
            <div class="logo-text" style="font-size:26px; font-weight:700; color:#ff4b92;">Oshikatu</div>
        </div>
        <h1>推し活×SNS</h1>
        <?php
        // SUB1/SUB2の判定をPOST/GET両方で対応
        $is_sub1 = isset($_GET['SUB1']) || isset($_POST['SUB1']);
        $is_sub2 = isset($_GET['SUB2']) || isset($_POST['SUB2']);
        // userパラメータがあればセッションにもセット
        if (isset($_GET['user']) && !empty($_GET['user'])) {
            $_SESSION['username'] = $_GET['user'];
        }
        if ($is_sub2) { ?>
            <h2>推し診断結果</h2>
        <?php } elseif ($is_sub1) { ?>
            <h2>推し追加</h2>
        <?php }
        if (isset($_POST['charcterName'])) {
            $charcterName = $_POST['charcterName'];
            $animeName = $_POST['animeName'];
            $charcterImage = $_POST['charcterImage'];
        } else {
            $charcterName = "";
            $animeName = "";
            $charcterImage = "";
        }
        ?>

        <!-- AJAX リクエストの場合のみ resultArea 断片を返す -->
        <?php if (isset($_POST['ajax']) && $_POST['ajax'] == '1'):
            // Clear any output buffering so we return only the fragment.
            while (ob_get_level()) {
                ob_end_clean();
            }
        ?>
            <div id="resultArea">
                <div class="aaa">
                    <div class="name">
                        <h3>キャラ名</h3>
                        <?php echo '<h4>' . htmlspecialchars($charcterName, ENT_QUOTES) . '</h4>'; ?>
                        <form id="searchForm" method="post">
                            <input type="text" id="searchInput" name="search" value="<?php echo htmlspecialchars($charcterName ?? '', ENT_QUOTES); ?>" class="abc">
                            <br>
                            <button type="submit" id="searchBtn">検索</button>
                        </form>
                        <h3>アニメ名</h3>
                        <?php echo '<h4>' . htmlspecialchars($animeName, ENT_QUOTES) . '</h4>'; ?>
                    </div>
                    <div class="bbb">
                        <h3>キャラクター画像</h3>
                        <img src="<?php echo htmlspecialchars($charcterImage, ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($charcterName, ENT_QUOTES); ?>" width="200">
                    </div>
                </div>
                <hr>
                <p>この推し（推しキャラ）を登録しますか？</p>
                <?php
                // Decide form action based on SUB1/SUB2（POST/GET両対応）
                $action_file = '';
                if ($is_sub1) {
                    $action_file = 'profile.php';
                } elseif ($is_sub2) {
                    $action_file = 'aaa.php';
                }
                if ($action_file !== '') {
                ?>
                    <form action="<?php echo htmlspecialchars($action_file, ENT_QUOTES); ?>" method="post">
                        <input type="hidden" name="characterName" value="<?php echo htmlspecialchars($charcterName, ENT_QUOTES); ?>">
                        <input type="hidden" name="animeName" value="<?php echo htmlspecialchars($animeName, ENT_QUOTES); ?>">
                        <input type="hidden" name="characterImage" value="<?php echo htmlspecialchars($charcterImage, ENT_QUOTES); ?>">
                        <?php if (!empty($_SESSION['username'])): ?>
                        <input type="hidden" name="user" value="<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES); ?>">
                        <?php endif; ?>
                        <button type="submit" name="register_oshi">登録する</button>
                    </form>
                <?php
                } else {
                    echo '<p>登録先が設定されていません。</p>';
                }
                ?>
            </div>

        <?php exit;
        endif; ?>

        <!-- 通常のページ表示 -->
        <div id="resultArea">
            <div class="aaa">
                <div class="name">
                    <h3>キャラ名</h3>
                    <form id="searchForm" method="post">
                        <input type="text" id="searchInput" name="search" class="abc" placeholder="キャラ名を入力">
                        <br>
                        <button type="submit">検索</button>
                    </form>
                    <h3>アニメ名</h3>
                    <?php echo '<h4>' . htmlspecialchars($animeName, ENT_QUOTES); ?></h4>
                </div>
                <div class="bbb">
                    <h3>キャラクター画像</h3>
                    <?php if ($charcterImage): ?>
                        <img src="<?php echo htmlspecialchars($charcterImage, ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($charcterName, ENT_QUOTES); ?>" width="200">
                    <?php else: ?>
                        <p>画像がまだ選択されていません</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
        // SUB1/SUB2の判定をPOST/GET両方で対応
        if ($is_sub1) {
            echo '<br><a href="Character_entry.php?SUB1=1">別のキャラを検索</a>';
            if (!empty($_SESSION['username'])) {
                echo '<form action="profile.php" method="get" style="display:inline; margin-left:10px;">';
                echo '<input type="hidden" name="user" value="' . htmlspecialchars($_SESSION['username'], ENT_QUOTES) . '">';
                echo '<button type="submit">アカウント画面へ戻る</button>';
                echo '</form>';
            } else {
                echo '<a href="login.php">ログイン画面へ</a>';
            }
            echo '<br>';
        } elseif ($is_sub2) {
            // セッションがなければログイン画面へリダイレクト
            if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
                header('Location: login.php');
                exit;
            }
        ?>
            <form action="profile.php" method="get" style="display:inline;">
                <input type="hidden" name="user" value="<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES); ?>">
                <button type="submit">プロフィール</button>
            </form>
            <p><a href="diagnosis.php">診断画面へ戻る</a></p>
        <?php
        }
        ?>
        <div style="margin-top:32px; text-align:center;">
            <a class="btn btn-outline" href="home.php">ホームに戻る</a>
        </div>
    </main>
    <script>
        // サーバー側で送信されたsearch値をJSで使えるようにする
        window.serverSearch = <?php echo json_encode($_POST['search'] ?? ''); ?>;
    </script>
    <script src="aaaa.js"></script>
</body>

</html>

<?php
// 推しキャラ登録処理（user_oshiテーブルにINSERT）
if (
    isset($_POST['register_oshi']) &&
    isset($_POST['characterName'], $_POST['animeName'], $_POST['characterImage']) &&
    !empty($_SESSION['username'])
) {
    require_once 'db_connect.php';
    $stmt = $mysqli->prepare('INSERT INTO user_oshi (user, characterName, animeName, characterImage) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $_SESSION['username'], $_POST['characterName'], $_POST['animeName'], $_POST['characterImage']);
    $stmt->execute();
    $stmt->close();
    // 完了後はプロフィールへリダイレクト
    header('Location: profile.php?user=' . urlencode($_SESSION['username']));
    exit;
}
?>