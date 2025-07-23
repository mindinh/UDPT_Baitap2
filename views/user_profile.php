<?php
ob_start();
$title = "Profile";
?>

<div class='profile-container my-3'>
    <div class='profile-image'>
        <img src="public <?= isset($user["image_path"]) ? $user["image_path"] : "/images/default-ava.png" ?>" alt='Profile Image'>
    </div>
    <div class='profile-details'>
        <h2><?= $user['full_name'] ?></h2>
        <p><strong>Email: </strong><?= $user['email'] ?></p>
        <p><strong>Bio: </strong><?= $user['bio'] ?></p>
        <p><strong>Interests: </strong><?= implode(' - ', $user['interests'])  ?></p>
        <p><strong>Education: </strong><?= implode(' - ', $user['education']) ?></p>
        <p><strong>Work Experience: </strong><?=  implode(' - ', $user['work_experience']) ?></p>
                    
                
        <p><strong>Papers:</strong></p>
        <?php
        foreach ($papers as $paper) {
            echo "<ul>";
            echo "<li>" . "Title: " . $paper['Title'] . " ( " . explode('-', $paper['DateAdded'])[1] . "/" . explode('-', $paper['DateAdded'])[0] . " )</li>";
            echo "</ul>";
        }
        ?>
        <div class='profile-actions'>
            <a href='?action=update-profile' class='btn btn-primary'>Update Profile</a>
            <a href='?action=add-paper' class='btn btn-primary'>Add Paper</a>
        </div>
        
    </div>
</div>


<?php
$cont = ob_get_clean();

require_once 'views/layout.php';