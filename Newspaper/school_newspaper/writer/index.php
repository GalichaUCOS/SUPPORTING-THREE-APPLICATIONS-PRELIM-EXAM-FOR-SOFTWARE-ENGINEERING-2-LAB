<?php require_once 'classloader.php'; ?>

<?php 
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if ($userObj->isAdmin()) {
  header("Location: ../admin/index.php");
}  
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  </head>
  <body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container-fluid">
      <div class="display-4 text-center mb-4">Hello there and welcome! <span class="text-success"><?php echo $_SESSION['username']; ?></span>. Here are all the articles</div>
      <div class="row justify-content-center">
        <div class="col-md-6">
          <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <input type="text" class="form-control mt-4" name="title" placeholder="Input title here">
            </div>
            <div class="form-group">
              <textarea name="description" class="form-control mt-4" placeholder="Submit an article!"></textarea>
            </div>
            <div class="form-group">
              <input type="file" class="form-control-file mt-2" name="article_image" accept="image/*">
            </div>
            <div class="form-group">
              <select name="category_id" class="form-control mt-4" required>
                <option value="">Select Category</option>
                <?php
                  $categories = $articleObj->getCategories();
                  foreach ($categories as $category) {
                    echo '<option value="' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</option>';
                  }
                ?>
              </select>
            </div>
            <input type="submit" class="btn btn-primary btn-block mt-4 mb-4" name="insertArticleBtn" value="Submit">
          </form>
          <?php $articles = $articleObj->getActiveArticles(); ?>
          <?php foreach ($articles as $article) { ?>
          <div class="card mt-4 mb-3 shadow">
            <div class="card-body">
              <h4 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h4>
              <?php if (!empty($article['image_url'])) { ?>
                <img src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="Article Image" class="img-fluid mb-2">
              <?php } ?>
              <?php if ($article['is_admin'] == 1) { ?>
                <p><small class="bg-primary text-white p-1">Message From Admin</small></p>
              <?php } ?>
              <small>
                <strong><?php echo htmlspecialchars($article['username']); ?></strong> - 
                <?php echo htmlspecialchars($article['created_at']); ?>
              </small>
              <p class="card-text mt-2"><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
              <?php if ($article['author_id'] == $_SESSION['user_id']) { ?>
                <a href="articles_submitted.php" class="btn btn-primary btn-sm mt-2">Edit</a>
              <?php } elseif ($article['author_id'] != $_SESSION['user_id']) { ?>
                <form action="core/handleForms.php" method="POST">
                    <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                    <button type="submit" name="requestEditBtn" class="btn btn-warning btn-sm mt-2">Request Edit</button>
                </form>
              <?php } ?>
            </div>
          </div>  
          <?php echo "<!-- author_id: {$article['author_id']} | session: {$_SESSION['user_id']} -->"; ?>
          <?php } ?> 
        </div>
      </div>
    </div>
  </body>
</html>