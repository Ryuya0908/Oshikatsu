<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header('Location: auth_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'], $_POST['comment_text'])) {
    $user_name = $_SESSION['username'];
    $post_id = (int)$_POST['post_id'];
    $comment_text = trim($_POST['comment_text']);
    if ($comment_text !== '') {
        $stmt = $mysqli->prepare('INSERT INTO comments (post_id, user_name, comment_text) VALUES (?, ?, ?)');
        $stmt->bind_param('iss', $post_id, $user_name, $comment_text);
        $stmt->execute();
        $stmt->close();
    }
}
// board.phpのidを明示的にリダイレクト
$redirect_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
// post_idからcommunity_idを取得
$community_id = 0;
if ($redirect_id) {
    $stmt = $mysqli->prepare('SELECT community_id FROM posts WHERE id = ?');
    $stmt->bind_param('i', $redirect_id);
    $stmt->execute();
    $stmt->bind_result($community_id);
    $stmt->fetch();
    $stmt->close();
}
if ($community_id) {
    header('Location: board.php?id=' . $community_id);
} else {
    header('Location: board.php');
}
exit;
?>
