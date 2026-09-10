<?php
session_start();
require_once 'db_connect.php';

$error = '';
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $error = '無効なリクエストです。';
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
        $pw1 = (string)($_POST['password'] ?? '');
        $pw2 = (string)($_POST['password_confirm'] ?? '');

        if ($username === '') {
            $error = 'ユーザ名を入力してください。';
        } elseif ($pw1 === '' || $pw2 === '') {
            $error = 'パスワードを入力してください。';
        } elseif ($pw1 !== $pw2) {
            $error = 'パスワードが一致しません。';
        } elseif (mb_strlen($pw1) < 6) {
            $error = 'パスワードは6文字以上にしてください。';
        } else {
            // ユーザー名重複チェック（大文字小文字区別なし）
            $stmt = $mysqli->prepare('SELECT COUNT(*) AS c FROM users WHERE LOWER(username) = LOWER(?)');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
            if ($count > 0) {
                $error = 'そのユーザ名は既に使われています。別の名前を試してください。';
            } else {
                $hash = password_hash($pw1, PASSWORD_DEFAULT);
                $stmt = $mysqli->prepare('INSERT INTO users (username, password_hash, created_at) VALUES (?, ?, ?)');
                $now = date('Y-m-d H:i:s');
                $stmt->bind_param('sss', $username, $hash, $now);
                if ($stmt->execute()) {
                    $stmt->close();
                    header('Location: /進級制作/login.php?registered=1');
                    exit;
                } else {
                    $error = 'サーバーエラー：登録に失敗しました。';
                    $stmt->close();
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>ユーザ登録 - 推し活×SNS</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">
  <style>
    .signup-frame{ min-height:80vh; display:flex;align-items:center;justify-content:center; padding:24px; }
    .signup-card{ width:360px; max-width:96%; background:#fff; padding:24px; border-radius:8px; text-align:center; }
    .signup-card h1{ color:#ff6aa8; margin:0 0 8px 0; }
    .form-input{ width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; margin:8px 0; }
    .btn-register{ width:140px; margin:12px auto 0; display:inline-block; padding:10px 18px; background:#ff6aa8; color:#fff; border-radius:8px; border:none; cursor:pointer; }
    .error{ color:#c00; margin-bottom:8px; }
  </style>
</head>
<body>

  <main class="signup-frame">
    <div class="signup-card">
      <h1>推し活×SNS</h1>
      <div class="logo" style="margin: 30px auto 20px auto; justify-content:center;">
        <div class="logo-icon">♥</div>
        <div class="logo-text">Oshikatu</div>
      </div>
      <h2>ユーザ登録</h2>

      <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <input class="form-input" name="username" placeholder="ユーザ名" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        <input class="form-input" type="password" name="password" placeholder="パスワード">
        <input class="form-input" type="password" name="password_confirm" placeholder="パスワード（確認）">
        <button type="submit" class="btn-register">登録</button>
      </form>

      <p style="margin-top:12px;">すでにアカウントをお持ちですか？ <a href="/進級制作/login.php">ログイン</a></p>
    </div>
  </main>

</body>
</html>