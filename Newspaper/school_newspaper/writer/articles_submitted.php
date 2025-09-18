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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
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
      <div class="row justify-content-center">
        <div class="col-md-6">
          <form action="core/handleForms.php" method="POST">
            <div class="form-group">
              <input type="text" class="form-control mt-4" name="title" placeholder="Input title here">
            </div>
            <div class="form-group">
              <textarea name="description" class="form-control mt-4" placeholder="Submit an article!"></textarea>
            </div>
            <input type="submit" class="btn btn-primary form-control float-right mt-4 mb-4" name="insertArticleBtn">
          </form>

          <div class="display-4">Double click to edit article</div>
          <?php $articles = $articleObj->getArticlesByUserID($_SESSION['user_id']); ?>
          <?php foreach ($articles as $article) { ?>
          <div class="card mt-4 shadow articleCard">
            <div class="card-body">
              <h1><?php echo $article['title']; ?></h1> 
              <small><?php echo $article['username'] ?> - <?php echo $article['created_at']; ?> </small>
              <?php if ($article['is_active'] == 0) { ?>
                <p class="text-danger">Status: PENDING</p>
              <?php } ?>
              <?php if ($article['is_active'] == 1) { ?>
                <p class="text-success">Status: ACTIVE</p>
              <?php } ?>
              <p><?php echo $article['content']; ?> </p>
              <form class="deleteArticleForm">
                <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" class="article_id">
                <input type="submit" class="btn btn-danger float-right mb-4 deleteArticleBtn" value="Delete">
              </form>
              <div class="updateArticleForm d-none">
                <h4>Edit the article</h4>
                <form action="core/handleForms.php" method="POST">
                  <div class="form-group mt-4">
                    <input type="text" class="form-control" name="title" value="<?php echo $article['title']; ?>">
                  </div>
                  <div class="form-group">
                    <textarea name="description" id="" class="form-control"><?php echo $article['content']; ?></textarea>
                  </div>
                  <div class="form-group">
                    <select name="category_id" class="form-control" required>
                      <option value="">Select Category</option>
                      <?php
                        $categories = $articleObj->getCategories();
                        foreach ($categories as $category) {
                          $selected = ($category['id'] == $article['category_id']) ? 'selected' : '';
                          echo '<option value="' . $category['id'] . '" ' . $selected . '>' . htmlspecialchars($category['name']) . '</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>">
                  <input type="submit" class="btn btn-primary float-right mt-4" name="editArticleBtn">
                </form>
              </div>
              <?php
                $editRequests = $articleObj->getEditRequestsForAuthor($_SESSION['user_id']);
                foreach ($editRequests as $request) {
                  if ($request['article_id'] == $article['article_id']) {
              ?>
              <div class="editRequest mt-3 p-3 border rounded bg-light">
                <p><strong><?= htmlspecialchars($request['requester_username']) ?></strong> has requested to edit this article.</p>
                <form action="core/handleForms.php" method="POST" class="d-inline">
                  <input type="hidden" name="request_id" value="<?= $request['request_id'] ?>">
                  <button type="submit" name="acceptEditBtn" class="btn btn-success btn-sm">Accept</button>
                </form>
                <form action="core/handleForms.php" method="POST" class="d-inline">
                  <input type="hidden" name="request_id" value="<?= $request['request_id'] ?>">
                  <button type="submit" name="rejectEditBtn" class="btn btn-danger btn-sm">Reject</button>
                </form>
              </div>
              <?php
                  }
                }
              ?>
            </div>
          </div>  
          <?php } ?> 
        </div>
      </div>
    </div>
    <script>
      $('.articleCard').on('dblclick', function (event) {
        var updateArticleForm = $(this).find('.updateArticleForm');
        updateArticleForm.toggleClass('d-none');
      });

      $('.deleteArticleForm').on('submit', function (event) {
        event.preventDefault();
        var formData = {
          article_id: $(this).find('.article_id').val(),
          deleteArticleBtn: 1
        }
        if (confirm("Are you sure you want to delete this article?")) {
          $.ajax({
            type:"POST",
            url: "core/handleForms.php",
            data:formData,
            success: function (data) {
              if (data) {
                location.reload();
              }
              else{
                alert("Deletion failed");
              }
            }
          })
        }
      })
    </script>
  </body>
</html>