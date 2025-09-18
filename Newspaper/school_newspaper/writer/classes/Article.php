<?php  

require_once 'Database.php';
require_once 'User.php';
/**
 * Class for handling Article-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Article extends Database {
    /**
     * Creates a new article.
     * @param string $title The article title.
     * @param string $content The article content.
     * @param int $author_id The ID of the author.
     * @param string $image_url The image URL.
     * @param int $category_id The category ID.
     * @return int The ID of the newly created article.
     */
    public function createArticle($title, $content, $author_id, $image_url, $category_id = null) {
        $sql = "INSERT INTO articles (title, content, author_id, image_url, category_id, is_active) VALUES (?, ?, ?, ?, ?, 0)";
        return $this->executeNonQuery($sql, [$title, $content, $author_id, $image_url, $category_id]);
    }

    /**
     * Retrieves articles from the database.
     * @param int|null $id The article ID to retrieve, or null for all articles.
     * @return array
     */
    public function getArticles($id = null) {
        if ($id) {
            $sql = "SELECT * FROM articles WHERE article_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM articles JOIN school_publication_users ON articles.author_id = school_publication_users.user_id ORDER BY articles.created_at DESC";
        return $this->executeQuery($sql);
    }

    public function getActiveArticles($id = null) {
        if ($id) {
            $sql = "SELECT * FROM articles WHERE article_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM articles 
                JOIN school_publication_users ON 
                articles.author_id = school_publication_users.user_id 
                WHERE is_active = 1 ORDER BY articles.created_at DESC";
                
        return $this->executeQuery($sql);
    }

    public function getArticlesByUserID($user_id) {
        $sql = "SELECT * FROM articles 
                JOIN school_publication_users ON 
                articles.author_id = school_publication_users.user_id
                WHERE author_id = ? ORDER BY articles.created_at DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    /**
     * Updates an article.
     * @param int $id The article ID to update.
     * @param string $title The new title.
     * @param string $content The new content.
     * @param int $category_id The category ID.
     * @return int The number of affected rows.
     */
    public function updateArticle($id, $title, $content, $category_id = null) {
        $sql = "UPDATE articles SET title = ?, content = ?, category_id = ? WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$title, $content, $category_id, $id]);
    }
    
    /**
     * Toggles the visibility (is_active status) of an article.
     * This operation is restricted to admin users only.
     * @param int $id The article ID to update.
     * @param bool $is_active The new visibility status.
     * @return int The number of affected rows.
     */
    public function updateArticleVisibility($id, $is_active) {
        $userModel = new User();
        if (!$userModel->isAdmin()) {
            return 0;
        }
        $sql = "UPDATE articles SET is_active = ? WHERE article_id = ?";
        return $this->executeNonQuery($sql, [(int)$is_active, $id]);
    }


    /**
     * Deletes an article.
     * @param int $id The article ID to delete.
     * @return int The number of affected rows.
     */
    public function deleteArticle($id) {
        $sql = "DELETE FROM articles WHERE article_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }

    public function getArticleById($article_id) {
        $sql = "SELECT * FROM articles WHERE article_id = ?";
        return $this->runQuerySingle($sql, [$article_id]);
    }

    public function insertEditRequest($article_id, $requester_id) {
        $sql = "INSERT INTO edit_requests (article_id, requester_id, status) VALUES (?, ?, 'pending')";
        return $this->runNonQuery($sql, [$article_id, $requester_id]);
    }

    public function updateEditRequestStatus($request_id, $status) {
        $sql = "UPDATE edit_requests SET status = ? WHERE request_id = ?";
        return $this->runNonQuery($sql, [$status, $request_id]);
    }

    public function getEditRequestsForAuthor($author_id) {
        $sql = "SELECT er.*, a.title, u.username as requester_username
                FROM edit_requests er
                JOIN articles a ON er.article_id = a.article_id
                JOIN school_publication_users u ON er.requester_id = u.user_id
                WHERE a.author_id = ? AND er.status = 'pending'";
        return $this->runQuery($sql, [$author_id]);
    }

    /**
     * Retrieves all categories.
     * @return array
     */
    public function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY name";
        return $this->executeQuery($sql);
    }
}
?>
