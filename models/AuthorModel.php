<?php

require_once 'Database.php';
class AuthorModel {
    private $db;
    public function __construct() {
        $this->db = new Database();
    }

    public function getAllAuthors() {
        $query = "SELECT * FROM authors";
        $result = $this->db->query($query);
        $authors = [];
        while ($row = $result->fetch_assoc()) {
            $authors[] = $row;
        }
        return $authors;
    }

    public function getAllOtherAuthors($userId) {
        $query = "SELECT a.user_id AS AuthorId, a.full_name AS AuthorFName
                FROM users u JOIN authors a ON u.user_id = a.user_id
                WHERE u.user_id != ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $authors = [];
        while ($row = $result->fetch_assoc()) {
            $authors[] = $row;
        }
        return $authors;
    }

    public function getAuthorById($authorId) {
        $query = "SELECT a.*, u.email FROM authors a
                JOIN users u ON a.user_id = u.user_id
                WHERE a.user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $authorId);
        $stmt->execute();
        $result = $stmt->get_result();
        $author = $result->fetch_assoc();
        $stmt->close();
        return $author;
    }

    public function getAuthorsByPaperId($paperId) {
        $query = "SELECT a.full_name, a.user_id, p.paper_id FROM authors a
                JOIN participation p ON a.user_id = p.author_id
                WHERE p.paper_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $paperId);
        $stmt->execute();
        $result = $stmt->get_result();
        $authors = [];
        while ($row = $result->fetch_assoc()) {
            $authors[] = $row;
        }
        $stmt->close();
        return $authors;

    }
    
}