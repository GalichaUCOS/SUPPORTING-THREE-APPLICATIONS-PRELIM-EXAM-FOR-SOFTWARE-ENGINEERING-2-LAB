<?php require_once 'classloader.php'; ?>
<?php require_once 'classes/Database.php'; ?>

<?php 
$db = new Database(); // Create the database object

if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if (!$userObj->isAdmin()) {
  header("Location: ../writer/index.php");
}

// Handle delete and notify
if (isset($_POST['deleteArticleBtn'])) {
    $article_id = intval($_POST['delete_article_id']);
    $author_id = intval($_POST['author_id']);
    $article_title = $_POST['article_title'];

    // Delete the article
    $db->runNonQuery("DELETE FROM articles WHERE article_id = ?", [$article_id]);

    // Notify the author
    $msg = "Your article titled '<b>" . htmlspecialchars($article_title) . "</b>' was deleted by an admin.";
    $db->runNonQuery("INSERT INTO notifications (user_id, message) VALUES (?, ?)", [$author_id, $msg]);

    echo "<script>alert('Article deleted and author notified.');window.location.href='index.php';</script>";
    exit;
}
?>
<!doctype html>
  <html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <style>
      body {
        font-family: "Arial";
      }
    </style>
  </head>
  <body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container-fluid">
      <div class="display-4 text-center">Hello there and welcome to the admin side! <span class="text-success"><?php echo $_SESSION['username']; ?></span>. Here are all the articles</div>
      <div class="row justify-content-center">
        <div class="col-md-6">
          <h3>Add New Category</h3>
          <form action="core/handleForms.php" method="POST">
            <div class="form-group">
              <input type="text" class="form-control mt-4" name="category_name" placeholder="Category Name" required>
            </div>
            <input type="submit" class="btn btn-success form-control float-right mt-4 mb-4" name="insertCategoryBtn" value="Add Category">
          </form>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-6">
          <form action="core/handleForms.php" method="POST">
            <div class="form-group">
              <input type="text" class="form-control mt-4" name="title" placeholder="Input title here">
            </div>
            <div class="form-group">
              <textarea name="description" class="form-control mt-4" placeholder="Message as admin"></textarea>
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
            <input type="submit" class="btn btn-primary form-control float-right mt-4 mb-4" name="insertAdminArticleBtn">
          </form>
          <?php $articles = $articleObj->getActiveArticles(); ?>
          <?php foreach ($articles as $article) { ?>
          <div class="card mt-4 shadow">
            <div class="card-body">
              <h1><?php echo $article['title']; ?></h1> 
              <?php if ($article['is_admin'] == 1) { ?>
                <p><small class="bg-primary text-white p-1">  
                  Message From Admin
                </small></p>
              <?php } ?>
              <small><strong><?php echo $article['username'] ?></strong> - <?php echo $article['created_at']; ?> </small>
              <p><?php echo $article['content']; ?> </p>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="delete_article_id" value="<?php echo $article['article_id']; ?>">
                <input type="hidden" name="author_id" value="<?php echo $article['author_id']; ?>">
                <input type="hidden" name="article_title" value="<?php echo htmlspecialchars($article['title'], ENT_QUOTES); ?>">
                <button type="submit" name="deleteArticleBtn" class="btn btn-danger btn-sm" onclick="return confirm('Delete this article?');">Delete</button>
              </form>
            </div>
          </div>  
          <?php } ?> 
        </div>
      </div>
    </div>
  </body>
</html>