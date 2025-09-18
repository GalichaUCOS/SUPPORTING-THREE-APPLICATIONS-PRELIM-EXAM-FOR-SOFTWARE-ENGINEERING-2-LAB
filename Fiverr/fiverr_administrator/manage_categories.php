<?php require_once 'classloader.php'; ?>
<?php
if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
}

if (!$userObj->isFiverrAdministrator()) {
  header("Location: ../freelancer/index.php");
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <title>Manage Categories</title>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <div class="container mt-4">
    <h1>Manage Categories</h1>

    <!-- Add Category Form -->
    <div class="card mb-4">
      <div class="card-header">Add New Category</div>
      <div class="card-body">
        <form action="core/handleForms.php" method="POST">
          <div class="form-group">
            <label for="category_name">Category Name</label>
            <input type="text" class="form-control" name="category_name" required>
          </div>
          <button type="submit" class="btn btn-primary" name="insertCategoryBtn">Add Category</button>
        </form>
      </div>
    </div>

    <!-- Existing Categories -->
    <div class="card">
      <div class="card-header">Existing Categories</div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Category Name</th>
              <th>Date Added</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $categories = $categoryObj->getCategories();
            foreach ($categories as $category) {
              echo "<tr>";
              echo "<td>{$category['category_id']}</td>";
              echo "<td>{$category['category_name']}</td>";
              echo "<td>{$category['date_added']}</td>";
              echo "<td>
                      <form action='core/handleForms.php' method='POST' style='display:inline;'>
                        <input type='hidden' name='category_id' value='{$category['category_id']}'>
                        <input type='text' name='category_name' value='{$category['category_name']}' required>
                        <button type='submit' class='btn btn-sm btn-warning' name='updateCategoryBtn'>Update</button>
                      </form>
                      <form action='core/handleForms.php' method='POST' style='display:inline;'>
                        <input type='hidden' name='category_id' value='{$category['category_id']}'>
                        <button type='submit' class='btn btn-sm btn-danger' name='deleteCategoryBtn'>Delete</button>
                      </form>
                    </td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
