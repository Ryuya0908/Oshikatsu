<?php
session_start();
require_once 'db_connect.php';
// 実運用では DB から投稿を取得して表示
$timeline_data = [
  [
    'user' => 'Alice',
    'handle' => '@alice',
    'time' => '2025-12-02 10:12',
    'content' => '今日は新しい機能を追加しました！みんなの意見を聞かせてください。',
  ],
  [
    'user' => 'Bob',
    'handle' => '@bob',
    'time' => '2025-12-02 09:45',
    'content' => 'おはようございます。今日は寒いですね。',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
  [
    'user' => 'Carol',
    'handle' => '@carol',
    'time' => '2025-12-01 20:30',
    'content' => 'デザインを少し調整しました。見た目はいかがでしょうか？',
  ],
];
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Oshikatu ホーム画面</title>
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <?php
  // ログインユーザー名を取得
  $login_user = isset($_SESSION['username']) ? $_SESSION['username'] : null;
  ?>
  <?php if ($login_user): ?>
    <div style="text-align:right; color:#888; font-size:0.95em; margin:8px 12px 0 0;">ログイン中: <?php echo htmlspecialchars($login_user, ENT_QUOTES, 'UTF-8'); ?></div>
  <?php endif; ?>
  <div class="home-card">
    <aside class="main-lyout">
      <!-- ロゴ + ナビ -->
      <div class="home-info">
        <div class="logo">
          <div class="logo-icon">♥</div>
          <div class="logo-text">Oshikatu</div>
        </div>
        <div class="search">
          <input type="search" id="search-text" name="search" class="searchform" placeholder="検索ワードを入力">
          <button id="searchBtn">🔎</button>
        </div>
      </div>
    </aside>
    <div class="aggregate-layout">
      <aside class="sidebar-left">
        <button class="btn-post">投稿</button>
        <nav class="sidebar-nav">
          <li><a href="profile.php?user=<?php echo $login_user ? urlencode($login_user) : ''; ?>" class="nav-item">マイページ</a></li>
          <li><a href="diagnosis.php?return=profile" class="nav-item">推し診断</a></li>
          <li><a href="ranking.php" class="nav-item">ランキング</a></li>
        </nav>


      </aside>
      <main class="main-content">
        <?php foreach ($timeline_data as $post) { ?>
          <div class="timeline-post">
            <!-- アイコンを利用者が決められるようにする -->
            <span class="post-img"><img src="image.png" alt=""></span>
            <div class="post-area">
              <div class="post-header">
                <span class="post-user"><?= htmlspecialchars($post['user']) ?></span>
                <span class="post-handle"><?= htmlspecialchars($post['handle']) ?></span>
                <span class="post-time"><?= htmlspecialchars($post['time']) ?></span>
              </div>
              <div class="post-content">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
              </div>
            </div>
          </div>
        <?php } ?>
      </main>
    </div>
  </div>
</body>

</html>