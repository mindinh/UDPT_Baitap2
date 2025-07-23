<?php

require_once 'models/AuthorModel.php';
require_once 'models/PaperModel.php';

class AuthorController {
    private $authorModel;
    private $paperModel;

    public function __construct() {
        $this->authorModel = new AuthorModel();
        $this->paperModel = new PaperModel();
    }

    public function index() {
        $authors = $this->authorModel->getAllAuthors();

        
        require_once 'views/authors.php';
    }

    public function getAuthorProfile($authorId) {
        $author = $this->authorModel->getAuthorById($authorId);
        
        
        if (isset($author)) {
            $papers = $this->paperModel->getPaperByAuthorId($authorId);
            $profileJson = $author['profile_json_text'];
            $profileData = json_decode($profileJson, true);
            $author['bio'] = $profileData['bio'] ?? '';
            $author['interests'] = $profileData['interests'] ?? [];
            $author['education'] = $profileData['education'] ?? [];
            $author['work_experience'] = $profileData['work_experience'] ?? [];
            

            require_once 'views/author_profile.php';
        }

        // $title = "Author Details Not Found";
        // $content = "<p>No details available for this paper.</p>";
        // require_once 'views/layout.php';
        // return;
    }
}