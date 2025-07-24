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
            $user['interests'] = $profileData['interests'] ?? [];
            $user['education'] = $profileData['education'] ?? [];
            $user['work_experience'] = $profileData['work_experience'] ?? [];

   
            require_once 'views/profile_update.php';
        }
    }

    public function updateProfile() {
        $userId = $_SESSION['userId'];

        $website = $_POST['website'] ?? '';
        $bio = $_POST['bio'] ?? '';
        $avatarPath = '';
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
        
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatar = $_FILES['avatar'];

            $path = '/images/' . $userId . '.png';
            move_uploaded_file($avatar['tmp_name'], "public" . $path);
            $avatarPath = $path;
        }

        $isSuccess = $this->userModel->updateProfile($userId, $profileJson, $avatarPath);
        if ($isSuccess) {
            header("Location: ?action=profile");
            exit();
        }
        else {
            echo "Error updating profile.";
            exit();
        }
    }
}