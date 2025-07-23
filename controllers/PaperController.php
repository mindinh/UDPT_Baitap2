<?php

require_once 'models/PaperModel.php';
require_once 'models/UserModel.php';
require_once 'models/TopicModel.php';
require_once 'models/AuthorModel.php';
require_once 'models/ConferenceModel.php';

class PaperController
{
    private $paperModel;
    private $userModel;
    private $topicModel;
    private $authorModel;
    private $conferenceModel;

    public function __construct()
    {
        $this->paperModel = new PaperModel();
        $this->userModel = new UserModel();
        $this->topicModel = new TopicModel();
        $this->authorModel = new AuthorModel();
        $this->conferenceModel = new ConferenceModel();
    }

    public function index()
    {

        $topics = $this->topicModel->getAllTopics();
        

        $content = '<h3 class="mt-4 mb-3 fw-bold">All Papers</h3>';

        foreach ($topics as $topic) {
            $content .= "<div class='mb-4'>";
            $content .= "<h5 class='text-primary mb-2'>" . htmlspecialchars($topic['topic_name']) . "</h5>";

            $papersByTopic = $this->paperModel->getPaperByTopicId($topic['topic_id']);

            if (!empty($papersByTopic)) {
                $content .= "<ul class='list-group'>";
                foreach ($papersByTopic as $paper) {
                    $paperTitle = htmlspecialchars($paper['PaperTitle']);
                    $paperId = htmlspecialchars($paper['PaperId']);
                    $content .= "
                        <li class='list-group-item'>
                            <a href='?action=paper-details&id={$paperId}' class='text-decoration-none text-dark'>
                                {$paperTitle}
                            </a>
                        </li>
                    ";
                }
                $content .= "</ul>";
            } else {
                $content .= "<p class='text-muted'>No papers in this topic.</p>";
            }

        $content .= "</div><hr>";
    }


        require_once 'views/papers.php';
    }

    public function showPaperDetails($paperId)
    {
        $papers = $this->paperModel->getPaperDetailsByPaperId($paperId);
        $authors = $this->authorModel->getAuthorsByPaperId($paperId);


        if (!empty($papers) && isset($papers[0])) {
            $paper = $papers[0];

            require_once 'views/paper_details.php';
            return;
        }

        // $authorsStringList = explode(',', $papers[0]['author_string_list']);
        

        $title = "Paper Details Not Found";
        $content = "<p>No details available for this paper.</p>";
        require_once 'views/layout.php';
        return;

    }

    public function search($criteria)
    {

        $title = "Search Papers";
        
        $papers = $this->paperModel->searchPapers($criteria);
   
        require_once 'views/search_results.php';
    }

    public function showSearchPage()
    {
        $topics = $this->topicModel->getAllTopics();
        $authors = $this->authorModel->getAllOtherAuthors($_SESSION['userId']); 
        $conferences = $this->conferenceModel->getAllConferences();
        $conferencesAbbrev = $conferences ? array_map(function($conf) {
            return $conf['abbreviation'];
        }, $conferences) : [];

        


        require_once 'views/search.php';
    }


    public function addAuthor($userId, $paperId)
    {
        $paper = $this->paperModel->getPaperById($paperId);
        $user = $this->userModel->getUserById($userId);
        $authors = explode(', ', $paper['author_string_list']);
        $isSuccess = false;

        if (!in_array($user["full_name"], $authors)) {
            $authors[] = $user["full_name"];
            $authorString = implode(', ', $authors);

            $isSuccess = $this->paperModel->updatePaperAuthors($paperId, $authorString, $userId);
        } else {
            require_once 'views/add_author_error.php';
            return;
        }

        if ($isSuccess) {
            header("Location: ?action=paper-details&id=" . $paperId);
            exit;
        }
    }

    public function removeAuthor($paperId, $authorName)
    {
        $paper = $this->paperModel->getPaperById($paperId);
        $author = $this->userModel->getAuthorParticipationByFullname($authorName, $paperId);

        $authors = explode(', ', $paper['author_string_list']);
        echo $author["ParticipationRole"];
        $isSuccess = false;
        if (
            $author["ParticipationRole"] === "member" &&
            ($key = array_search($author['AuthorFName'], $authors)) !== false
        ) {
            unset($authors[$key]);
            $authorString = implode(', ', $authors);

            $isSuccess = $this->paperModel->removeAuthor($paperId, $authorString, $author['AuthorId']);
        } else {
            require_once 'views/remove_author_error.php';
            return;
        }

        if ($isSuccess) {
            header("Location: ?action=paper-details&id=" . $paperId);
            exit;
        }
    }

    public function showAddPaperForm()
    {
        $authors = $this->userModel->getAllAuthors();
        $topics = $this->topicModel->getAllTopics();
        $conferences = $this->conferenceModel->getAllConferences();

        // $activeConferences = $this->conferenceModel->getActiveConferences();

        require_once 'views/add_paper.php';
    }

    public function addPaper() {
        $title = $_POST['title'] ?? '';
        $abstract = $_POST['abstract'] ?? '';
        $topicId = $_POST['topic_id'] ?? '';
        $conference = $_POST['conference'] ?? '';
        $authors = $_POST['authors'] ?? [];
        $roles = $_POST['roles'] ?? [];
        $userId = $_SESSION['userId'];

        $userFullName = $this->authorModel->getAuthorById($userId)['full_name'] ?? 'Unknown Author';
        

        if (empty($title) || empty($abstract) || empty($topicId) || empty($conference)) {
            $error_message = "Please fill all paper information fields.";

            require_once 'views/add_paper_error.php';
            return;
        }

        $authorsList = [];
        $authorsStringList = '';
        $authorsStringList .= $userFullName . ', ';
        $conferenceId = $conference ? explode('/', $conference)[0] : '';

        foreach ($authors as $index => $author) {
            if (!empty($author) && !empty($roles[$index])) {
                $authorParts = explode('/', $author);
                if (count($authorParts) === 2) {
                    $authorId = intval($authorParts[0]);
                    $authorName = htmlspecialchars(trim($authorParts[1]));
                    $role = htmlspecialchars(trim($roles[$index]));
                    $authorsStringList .= $authorName . ', ';
                    $authorsList[] = "{$authorId}/{$role}";
                }
            }
        }
        $authorsStringList = rtrim($authorsStringList, ', ');


        $paperId = $this->paperModel->addPaper($title, $abstract, $topicId, $conferenceId, $authorsStringList, $authorsList, $userId);
        echo $paperId;
        if ($paperId) {
            
            header("Location: ?action=paper-details&id=" . $paperId);
            exit;
        } else {
            $error_message = "Failed to add paper. Please try again.";
            require_once 'views/add_paper_error.php';
        }
    }
}
