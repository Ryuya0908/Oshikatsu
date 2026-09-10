<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="diagnosis.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <title>推し活診断</title>
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
        if (!isset($_SESSION['username'])) {
            echo '<p>ログインしてください。<a href="login.php">ログイン</a></p>';
            exit;
        } else {
            $username = $_SESSION['username'];
        }

        // 診断フォームから送られてくるか、リンクの ?return=... で戻り先を受け取る
        $return_to = '';
        if (isset($_GET['return'])) {
            $return_to = $_GET['return'];
        } elseif (isset($_POST['return_to'])) {
            $return_to = $_POST['return_to'];
        }
        ?>
        <h1>推し活×SNS</h1>
        <h2>ようこそ推し診断へ！！</h2>
        <form action="Character_entry.php" method="POST">
        <!-- 呼び出し元を次の処理に伝えるための hidden -->
        <input type="hidden" name="return_to" value="<?php echo htmlspecialchars($return_to, ENT_QUOTES, 'UTF-8'); ?>">
        <ul>
            <li>質問１ 性別</li>
            <input type="radio" name="gender" value="男" id="men"><label for="men">男</label>
            <input type="radio" name="gender" value="女" id="girl"><label for="girl">女</label>
            <input type="radio" name="gender" value="不明" id="unknown"><label for="unknown">不明</label>
            <input type="radio" name="gender" value="人外" id="monster"><label for="monster">人外</label>
            <li>質問２ 年齢</li>
            <?php
            for($i=0;$i<100;$i+=10){
                echo '<input type="radio" name="age" value='.$i.' id='.$i.'><label for='.$i.'>'.$i.'</label>';
            }
            ?>
            <input type="radio" name="age" value="不明" id="unknown"><label for="unknown">不明</label>
            <li>質問３</li>
        </ul>
        <button type="submit" name="SUB1">診断する</button>
        <!-- <button type="submit" formaction="community.php">コミュニティ</button> -->
        </form>
        <?php
        // プロフィールボタンの表示条件を限定
        $show_profile_btn = false;
        if (isset($_SESSION['username']) && isset($return_to)) {
            // home.phpまたはprofile.phpから遷移した場合のみ表示
            if ($return_to === 'profile' || $return_to === 'home') {
                $show_profile_btn = true;
            }
        }
        if ($show_profile_btn) {
            $u = $_SESSION['username']; // urlencode不要（profile.php側でhtmlエスケープされるため）
            echo '<div style="margin-top:10px;">';
            echo '<form action="profile.php" method="get" style="display:inline;">';
            echo '<input type="hidden" name="user" value="' . htmlspecialchars($u, ENT_QUOTES, 'UTF-8') . '">';
            echo '<button type="submit">プロフィール</button>';
            echo '</form>';
            echo '</div>';
        }
        ?>
        </div>
        <div style="margin-top:32px; text-align:center;">
            <a class="btn btn-outline" href="home.php">ホームに戻る</a>
        </div>
    </div>
</body>
</html>