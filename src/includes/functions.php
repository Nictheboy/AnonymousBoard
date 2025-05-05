<?php
/**
 * Get all posts from the database
 * 
 * @return array All posts ordered by creation date (newest first)
 */
function getPosts() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Error getting posts: " . $e->getMessage());
        return [];
    }
}

/**
 * Add a new post to the database
 * 
 * @param string $content The content of the post
 * @param string $nickname Optional nickname (default is null)
 * @return boolean True if successful, false otherwise
 */
function addPost($content, $nickname = null) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO posts (nickname, content) VALUES (:nickname, :content)");
        return $stmt->execute([
            'nickname' => $nickname,
            'content' => $content
        ]);
    } catch (PDOException $e) {
        error_log("Error adding post: " . $e->getMessage());
        return false;
    }
}

/**
 * Clean input data
 * 
 * @param string $data Data to be cleaned
 * @return string Cleaned data
 */
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
} 