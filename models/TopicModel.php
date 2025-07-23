<?php

require_once 'Database.php';

class TopicModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllTopics() {
        $query = "SELECT * FROM topics";
        $result = $this->db->query($query);
        $topics = [];
        while ($row = $result->fetch_assoc()) {
            $topics[] = $row;
        }
        return $topics;
    }   

}