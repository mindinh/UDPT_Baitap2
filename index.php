<?php
session_start();
require_once 'controllers/HomeController.php';
require_once 'controllers/PaperController.php';
require_once 'controllers/AuthorController.php';
require_once 'controllers/LoginController.php';
require_once 'controllers/UserController.php';

$action = "";
if (isset($_REQUEST["action"]))
{    
    $action = $_REQUEST["action"];
}
else {
    $action = "home";
}
 
switch ($action)
{   
    case "list":
        echo "List of topics";
        break;
    case "papers":
        $controller = new PaperController();
        $controller->index();
        break;
    case "paper-details":
        if (isset($_REQUEST['id'])) {
            $paperId = $_REQUEST['id'];
            $controller = new PaperController();
            $controller->showPaperDetails($paperId);
        }
        break;
    case "paper-search":
        $controller = new PaperController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $criteria = [
                'title' => $_POST['title'] ?? '',
                'topic' => $_POST['topic'] ?? '',
                'author' => $_POST['author'] ?? '',
                'conference' => $_POST['conference'] ?? ''
                
            ];
            $controller->search($criteria);
        }
        else {
            $controller->showSearchPage();
        }
        
    break;
    case "add-paper":
        if (isset($_SESSION['userId'])) {
            $controller = new PaperController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->addPaper();
            } else {
                $controller->showAddPaperForm();
            }
        } else {
            echo "Unauthorized action.";
            exit();
        }
    break;  
    case "authors":
        $controller = new AuthorController();
        $controller->index();
        break;
    case "author-details":
        if (isset($_REQUEST['author_id'])) {
            $authorId = $_REQUEST['author_id'];
            $controller = new AuthorController();
            $controller->getAuthorProfile($authorId);
        }
        break;
    case "login":
        $controller = new LoginController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $controller->login($email, $password);
        } else {
            $controller->showLoginPage();
        }
        break;
    case "logout":
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php?action=login');
        exit();
    case "profile":
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            $controller = new UserController();
            $controller->getUserProfile($userId);
        }
        break;
    case "update-profile":
        if (isset($_SESSION['userId'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userId = $_SESSION['userId'];
                $controller = new UserController();
                $controller->updateProfile();
            }
            $userId = $_SESSION['userId'];
            $controller = new UserController();
            $controller->showUpdateProfilePage();
        }
        break;
    case "add-author":
        if (isset($_REQUEST['id']) && isset($_SESSION['userId'])) {
            $paperId = $_REQUEST['id'];
            $userId = $_SESSION['userId'];
            $controller = new PaperController();
            $controller->addAuthor($userId, $paperId);
        }
        break;
    case "remove-author":
        if (isset($_REQUEST['paperId']) && isset($_SESSION['userId']) && $_SESSION['role'] === 'admin') {
            $paperId = $_REQUEST['paperId'];
            $author = $_REQUEST['author'] ?? '';
            $controller = new PaperController();
            $controller->removeAuthor($paperId, $author);
        } else {
            echo "Unauthorized action.";
            exit();
        }

    case "home":
        $controller = new HomeController();
        $controller->index();
        break;
    default:
        echo "Action not found";
        break;
}

?>
