<?php
ob_start();
$title = "Author Profile";
?>

<div class='profile-container my-3'>
    <div class='profile-image'>
        <img src="public <?= isset($author["image_path"]) ? $author["image_path"] : "/images/default-ava.png" ?>" alt='Profile Image'>
    </div>
    <div class='profile-details'>
    <h2><?= $author['full_name'] ?> </h2>
    <p><strong>Email: </strong><?= $author['email'] ?></p>
    <p><strong>Bio: </strong><?= $author['bio'] ?></p>
    <p><strong>Interests: </strong><?=  implode(' - ', $author['interests']) ?></p>
    <p><strong>Education: </strong><?= implode(' - ', $author['education'])  ?></p>
    <p><strong>Work Experience: </strong><?=  implode(' - ', $author['work_experience']) ?></p>
                
    <p><strong>Papers:</strong></p>
    <?php
    foreach ($papers as $paper) {
        echo "<ul>";
        echo "<li> Title: " . $paper['Title'] . " ( " . explode('-', $paper['DateAdded'])[1] . "/" . explode('-', $paper['DateAdded'])[0] . " )</li>";
        echo "</ul>";
    }
    ?>
</div>
</div>


<?php
$cont = ob_get_clean();

require_once 'views/layout.php';