<?php

require_once 'models/UserModel.php';

class UserController {

    private $userModel;
    private $paperModel;
    public function __construct() {
        $this->userModel = new UserModel();
        $this->paperModel = new PaperModel();
    }

    public function getUserProfile($userId) {
        $user = $this->userModel->getUserById($userId);
        if ($user) {
            $papers = $this->paperModel->getPaperByAuthorId($userId);
            $profileJson = $user['profile_json_text'];
            $profileData = json_decode($profileJson, true);
            $user['bio'] = $profileData['bio'] ?? '';
            $user['interests'] = $profileData['interests'] ?? [];
            $user['education'] = $profileData['education'] ?? [];
            $user['work_experience'] = $profileData['work_experience'] ?? [];

            

            require_once 'views/user_profile.php'; 
        }

    }

    public function showUpdateProfilePage() {
        $user = $this->userModel->getUserById($_SESSION['userId']);
        if ($user) {
            $profileJson = $user['profile_json_text'];
            $profileData = json_decode($profileJson, true);
            $user['bio'] = $profileData['bio'] ?? '';
            $user['interests'] = $profileData['interests'] ?? '';
            $user['education'] = $profileData['education'] ?? '';
            $user['work_experience'] = $profileData['work_experience'] ?? '';

   
            require_once 'views/profile_update.php';
        }
    }

    public function updateProfile() {
        $userId = $_SESSION['userId'];

        $website = $_POST['website'] ?? '';
        $bio = $_POST['bio'] ?? '';
        $interests = array_map('trim', explode(',', $_POST['interests'] ?? ''));
        $education = array_filter(array_map('trim', explode("\n", $_POST['education'] ?? '')));
        $workExp = array_filter(array_map('trim', explode("\n", $_POST['work_exp'] ?? '')));

        $profileData = [
            'bio' => $bio,
            'interests' => $interests,
            'education' => $education,
            'work_experience' => $workExp
        ];

        $profileJson = json_encode($profileData, JSON_UNESCAPED_UNICODE);
        
        echo "Profile JSON: " . $profileJson; // Debugging line
        $boolean = $this->userModel->updateProfile($userId, $profileJson);
        if ($boolean) {
            header("Location: ?action=profile");
            exit();
        }
        else {
            echo "Error updating profile.";
            exit();
        }
    }
}