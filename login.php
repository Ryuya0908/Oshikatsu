<?php
session_start();
require_once 'db_connect.php';
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = $_POST["id"] ?? "";
  $password = $_POST["password"] ?? "";

  // DBからユーザー情報取得（community_dbのusersテーブルを利用）
  $stmt = $mysqli->prepare('SELECT username, password_hash FROM users WHERE username = ?');
  $stmt->bind_param('s', $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  $stmt->close();

  if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['username'] = $user['username'];
    header("Location: home.php");
    exit;
  } else {
    $error = "ID またはパスワードが違います。";
  }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>Oshikatu ログイン</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <div class="login-card">
    <div class="login-logo">
      <div class="logo">
        <div class="logo-icon">♥</div>
        <div class="logo-text">Oshikatu</div>
      </div>
    </div>
    <h1>ログイン</h1>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="id" class="form-input" placeholder="ID" required>
      <input type="password" name="password" class="form-input" placeholder="パスワード" required>

      <button type="submit" class="login-button">ログイン</button>

      <p class="signup-text">
        アカウントをお持ちでないですか？
        <a href="sign-up.php">新規登録</a>
      </p>
    </form>
  </div>

</body>

</html>