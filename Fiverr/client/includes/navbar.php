<style>
.dropdown-submenu {
  position: relative;
}

.dropdown-submenu .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -1px;
}

.dropdown-submenu:hover .dropdown-menu {
  display: block;
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark p-4" style="background-color: #023E8A;">
  <a class="navbar-brand" href="index.php">Client Panel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="categoriesDropdown">
          <?php
          $categories = $categoryObj->getCategories();
          foreach ($categories as $category) {
            echo '<div class="dropdown-submenu">';
            echo '<a class="dropdown-item dropdown-toggle" href="#" data-toggle="dropdown">' . htmlspecialchars($category['category_name']) . '</a>';
            echo '<ul class="dropdown-menu">';
            $subcategories = $subcategoryObj->getSubcategoriesByCategory($category['category_id']);
            foreach ($subcategories as $subcategory) {
              echo '<li><a class="dropdown-item" href="#">' . htmlspecialchars($subcategory['subcategory_name']) . '</a></li>';
            }
            echo '</ul>';
            echo '</div>';
          }
          ?>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="project_offers_submitted.php">Project Offers Submitted </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">Logout</a>
      </li>
    </ul>
  </div>
</nav>

<script>
$(document).ready(function(){
  $('.dropdown-submenu a.dropdown-toggle').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });
});
</script>

