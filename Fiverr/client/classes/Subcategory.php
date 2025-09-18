<?php
require_once 'Database.php';

class Subcategory extends Database {

    public function createSubcategory($category_id, $subcategory_name) {
        $sql = "INSERT INTO subcategories (category_id, subcategory_name) VALUES (?, ?)";
        return $this->executeNonQuery($sql, [$category_id, $subcategory_name]);
    }

    public function getSubcategories($subcategory_id = null) {
        if ($subcategory_id) {
            $sql = "SELECT * FROM subcategories WHERE subcategory_id = ?";
            return $this->executeQuerySingle($sql, [$subcategory_id]);
        }
        $sql = "SELECT * FROM subcategories ORDER BY subcategory_name ASC";
        return $this->executeQuery($sql);
    }

    public function getSubcategoriesByCategory($category_id) {
        $sql = "SELECT * FROM subcategories WHERE category_id = ? ORDER BY subcategory_name ASC";
        return $this->executeQuery($sql, [$category_id]);
    }

    public function updateSubcategory($subcategory_id, $subcategory_name) {
        $sql = "UPDATE subcategories SET subcategory_name = ? WHERE subcategory_id = ?";
        return $this->executeNonQuery($sql, [$subcategory_name, $subcategory_id]);
    }

    public function deleteSubcategory($subcategory_id) {
        $sql = "DELETE FROM subcategories WHERE subcategory_id = ?";
        return $this->executeNonQuery($sql, [$subcategory_id]);
    }
}
?>
