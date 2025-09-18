<?php
require_once '../classes/Database.php';
session_start();
$db = new Database();
$user_id = $_SESSION['user_id'];
$shared = $db->runQuery(
    "SELECT a.* FROM articles a
     JOIN edit_requests e ON a.article_id = e.article_id
     WHERE e.requester_id = ? AND e.status = 'accepted'", [$user_id]);
?>
<!DOCTYPE html>
<html>
<head>
  <title>Shared Articles</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <div class="container mt-4">
    <h2>Articles Shared With You For Editing</h2>
    <div class="display-4">Double click to edit article</div>
    <?php foreach ($shared as $article): ?>
      <div class="card mb-3 shadow articleCard">
        <div class="card-body">
          <h1><?= htmlspecialchars($article['title']) ?></h1>
          <p class="card-text"><?= nl2br(htmlspecialchars($article['content'])) ?></p>
          <?php if (!empty($article['image_url'])): ?>
            <img src="<?= htmlspecialchars($article['image_url']) ?>" alt="Article Image" class="img-fluid mb-2">
          <?php endif; ?>
          <div class="updateArticleForm d-none">
            <h4>Edit the article</h4>
            <form action="core/handleForms.php" method="POST">
              <div class="form-group mt-4">
                <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($article['title']) ?>">
              </div>
              <div class="form-group">
                <textarea name="description" class="form-control"><?= htmlspecialchars($article['content']) ?></textarea>
                <input type="hidden" name="article_id" value="<?= $article['article_id'] ?>">
                <input type="submit" class="btn btn-primary float-right mt-4" name="editArticleBtn">
              </div>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <script>
    $('.articleCard').on('dblclick', function (event) {
      var updateArticleForm = $(this).find('.updateArticleForm');
      updateArticleForm.toggleClass('d-none');
    });
  </script>
</body>
</html>
