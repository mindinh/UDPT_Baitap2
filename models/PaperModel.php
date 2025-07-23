<?php

require_once 'Database.php';

class PaperModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllPapers() {
        $query = "SELECT * FROM papers";
        $result = $this->db->query($query);
        $papers = [];
        while ($row = $result->fetch_assoc()) {
            $papers[] = $row;
        }
        return $papers;
    }   

    public function getPaperById($paperId) {
        $query = "SELECT * FROM papers p 
                JOIN conferences c 
                ON p.conference_id = c.conference_id
                JOIN topics t 
                ON p.topic_id = t.topic_id
                WHERE paper_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $paperId);
        $stmt->execute();
        $result = $stmt->get_result();
        $paper = $result->fetch_assoc();
        $stmt->close();
        return $paper;
    }

    public function getPaperDetailsByPaperId($paperId) {
        $query = "SELECT 
                    p.paper_id,
                    p.title,
                    p.author_string_list,
                    p.abstract,
                    
                    t.topic_name,
                    
                    c.name AS conference_name,
                    c.abbreviation,
                    c.start_date,
                    c.end_date,
                    
                    pa.author_id
                FROM PAPERS p
                JOIN TOPICS t ON p.topic_id = t.topic_id
                JOIN CONFERENCES c ON p.conference_id = c.conference_id
                JOIN PARTICIPATION pa ON p.paper_id = pa.paper_id
                WHERE p.paper_id = ? AND pa.status = 'show'";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $paperId);
        $stmt->execute();
        $result = $stmt->get_result();
        $papers = [];
        while ($row = $result->fetch_assoc()) {
            $papers[] = $row;
        }
        $stmt->close();
        return $papers;
    }

    public function getPaperByTopicId($topicId) {
        $query = "SELECT p.paper_id AS PaperId, p.title AS PaperTitle FROM papers p 
                JOIN conferences c 
                ON p.conference_id = c.conference_id
                WHERE p.topic_id = ? AND YEAR(c.start_date) = 2024
                ORDER BY c.start_date DESC
                LIMIT 5";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $topicId);
        $stmt->execute();
        $result = $stmt->get_result();
        $papers = [];
        while ($row = $result->fetch_assoc()) {
            $papers[] = $row;
        }
        $stmt->close();
        return $papers;
    }

    public function searchPapers($criteria) {
        $sql = "SELECT p.paper_id, p.title, p.author_string_list, p.abstract, c.name, c.abbreviation, t.topic_name
                  FROM papers p 
                  JOIN conferences c ON p.conference_id = c.conference_id 
                  JOIN topics t ON p.topic_id = t.topic_id 
                  WHERE 1=1";
        $params = [];
        $types = '';
        
        if (!empty($criteria['title'])) {
            $sql .= " AND p.title LIKE ?";
            $params[] = '%' . $criteria['title'] . '%';
            $types .= 's';
        }   

        if (!empty($criteria['topic'])) {
            $sql .= " AND t.topic_name = ?";
            $params[] = $criteria['topic'];
            $types .= 's';
        }

        if (!empty($criteria['author'])) {
            $sql .= " AND p.author_string_list LIKE ?";
            $params[] = '%' . $criteria['author'] . '%';
            $types .= 's';
        }

        if (!empty($criteria['conference'])) {
            $sql .= " AND c.name = ?";
            $params[] = $criteria['conference'];
            $types .= 's';
        }
        

        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        
        $result = $stmt->get_result();
        $papers = [];
        while ($row = $result->fetch_assoc()) {
            $papers[] = $row;
        }
        return $papers;
    }

    public function getPaperByAuthorId($authorId) {
        $query = "SELECT p.paper_id AS PaperId, p.title AS Title, pt.role AS Role, pt.date_added AS DateAdded, pt.status AS PtStatus FROM papers p
                JOIN participation pt ON p.paper_id = pt.paper_id
                WHERE pt.author_id = ?
                ORDER BY pt.date_added DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $authorId);
        $stmt->execute();
        $result = $stmt->get_result();
        $papers = [];
        while ($row = $result->fetch_assoc()) {
            $papers[] = $row;
        }
        $stmt->close();
        return $papers;
    }

    public function updatePaperAuthors($paperId, $authorString, $authorId) {
        $this->db->startTransaction();

        try {
            $stmt1 = $this->db->prepare("UPDATE papers SET author_string_list = ? WHERE paper_id = ?");
            $stmt1->bind_param("si", $authorString, $paperId);
            $stmt1->execute();

            $stmt2 = $this->db->prepare("INSERT INTO participation (author_id, paper_id, role, date_added, status) VALUES (?, ?, 'member', NOW(), 'show')");
            $stmt2->bind_param("ii", $authorId, $paperId);
            $stmt2->execute();

            $this->db->commit();
            $stmt1->close();
            $stmt2->close();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
       
    }

    public function removeAuthor($paperId, $authorString, $removedAuthorId) {
        $this->db->startTransaction();

        try {
            $stmt = $this->db->prepare("UPDATE papers SET author_string_list = ? WHERE paper_id = ?");
            $stmt->bind_param("si", $authorString, $paperId);
            $stmt->execute();

            $stmt2 = $this->db->prepare("DELETE FROM participation WHERE paper_id = ? AND author_id = ?");
            $stmt2->bind_param("ii", $paperId, $removedAuthorId);
            $stmt2->execute();
            $this->db->commit();
            $stmt->close();
            $stmt2->close();
            return true;
        }
        catch (Exception $e) {
            $this->db->rollback();
            return false;
        }

    }

    public function addPaper($title, $abstract, $topicId, $conferenceId, $authorStringList, $authorList, $userId) {
        $this->db->startTransaction();

        try {
            $stmt = $this->db->prepare("INSERT INTO papers (title, author_string_list, abstract, conference_id, topic_id, user_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiii", $title, $authorStringList, $abstract, $conferenceId, $topicId, $userId);
            $stmt->execute();
            $paperId = $stmt->insert_id;

            $firstAuthor = 'first_author';
            $status = 'show';
            $stmt1 = $this->db->prepare("INSERT INTO participation (author_id, paper_id, role, date_added, status) VALUES (?, ?, ?, NOW(), ?)");
            $stmt1->bind_param("iiss", $userId, $paperId, $firstAuthor, $status);
            $stmt1->execute();

            $stmt2 = $this->db->prepare("INSERT INTO participation (author_id, paper_id, role, date_added, status) VALUES (?, ?, ?, NOW(), ?)");
            foreach ($authorList as $author) {
                $authorParts = explode('/', $author);
                $authorId = intval($authorParts[0]);
                $role = htmlspecialchars(trim($authorParts[1]));

                $stmt2->bind_param("iiss", $authorId, $paperId, $role, $status);
                $stmt2->execute();
                
            }
            $this->db->commit();
            
            $stmt->close();
            $stmt1->close();
            $stmt2->close();

            return $paperId;
        } catch (Exception $e) {
            $this->db->rollback();
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
}