<?php

require_once 'Database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllAuthors() {
        $query = "SELECT a.user_id AS AuthorId, a.full_name AS AuthorFName
                FROM users u JOIN authors a ON u.user_id = a.user_id";
        $result = $this->db->query($query);
        $authors = [];
        while ($row = $result->fetch_assoc()) {
            $authors[] = $row;
        }
        return $authors;
    }

    public function getUserById($userId) {
        $query = "SELECT * FROM users u JOIN authors a ON u.user_id = a.user_id WHERE u.user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();   
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function getUserByFullName($fullName) {
        $query = "SELECT * FROM authors WHERE full_name = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $fullName);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function getAuthorParticipationByFullname($fullName, $paperId) {
        $query = "SELECT a.user_id AS AuthorId, a.full_name AS AuthorFName, p.role AS ParticipationRole
                FROM authors a JOIN participation p
                ON a.user_id = p.author_id
                WHERE a.full_name = ? AND p.paper_id = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $fullName, $paperId);
        $stmt->execute();
        $result = $stmt->get_result();
        $authorParticipation = $result->fetch_assoc();
        $stmt->close();
        return $authorParticipation;
    }


    public function authenticate($email, $password) {
        // Placeholder logic for authentication
        $query = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); 
        }
        return null;
    }

    public function updateProfile($userId, $profileJson) {
        $query = "UPDATE authors SET profile_json_text = ? WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $profileJson, $userId);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }
}
