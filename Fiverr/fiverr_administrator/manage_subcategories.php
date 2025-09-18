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
  <title>Manage Subcategories</title>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <div class="container mt-4">
    <h1>Manage Subcategories</h1>

    <!-- Add Subcategory Form -->
    <div class="card mb-4">
      <div class="card-header">Add New Subcategory</div>
      <div class="card-body">
        <form action="core/handleForms.php" method="POST">
          <div class="form-group">
            <label for="category_id">Category</label>
            <select class="form-control" name="category_id" required>
              <option value="">Select Category</option>
              <?php
              $categories = $categoryObj->getCategories();
              foreach ($categories as $category) {
                echo "<option value='{$category['category_id']}'>{$category['category_name']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="subcategory_name">Subcategory Name</label>
            <input type="text" class="form-control" name="subcategory_name" required>
          </div>
          <button type="submit" class="btn btn-primary" name="insertSubcategoryBtn">Add Subcategory</button>
        </form>
      </div>
    </div>

    <!-- Existing Subcategories -->
    <div class="card">
      <div class="card-header">Existing Subcategories</div>
      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Category</th>
              <th>Subcategory Name</th>
              <th>Date Added</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $subcategories = $subcategoryObj->getSubcategories();
            foreach ($subcategories as $subcategory) {
              $category = $categoryObj->getCategories($subcategory['category_id']);
              echo "<tr>";
              echo "<td>{$subcategory['subcategory_id']}</td>";
              echo "<td>{$category['category_name']}</td>";
              echo "<td>{$subcategory['subcategory_name']}</td>";
              echo "<td>{$subcategory['date_added']}</td>";
              echo "<td>
                      <form action='core/handleForms.php' method='POST' style='display:inline;'>
                        <input type='hidden' name='subcategory_id' value='{$subcategory['subcategory_id']}'>
                        <input type='text' name='subcategory_name' value='{$subcategory['subcategory_name']}' required>
                        <button type='submit' class='btn btn-sm btn-warning' name='updateSubcategoryBtn'>Update</button>
                      </form>
                      <form action='core/handleForms.php' method='POST' style='display:inline;'>
                        <input type='hidden' name='subcategory_id' value='{$subcategory['subcategory_id']}'>
                        <button type='submit' class='btn btn-sm btn-danger' name='deleteSubcategoryBtn'>Delete</button>
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
