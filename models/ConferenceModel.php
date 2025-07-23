<?php

require_once 'Database.php';

class ConferenceModel {
    private $db;
    public function __construct() {
        $this->db = new Database();
    }

    public function getAllConferences() {
        $query = "SELECT * FROM conferences";
        $result = $this->db->query($query);
        $conferences = [];
        while ($row = $result->fetch_assoc()) {
            $conferences[] = $row;
        }
        return $conferences;
    }


    public function getActiveConferences() {
        $query = "SELECT * FROM conferences WHERE end_date >= CURDATE() AND start_date <= CURDATE()";
        $result = $this->db->query($query);
        $conferences = [];
        while ($row = $result->fetch_assoc()) {
            $conferences[] = $row;
        }
        return $conferences;
    }

}