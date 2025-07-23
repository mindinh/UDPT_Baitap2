<?php
ob_start();
$title = "Authors Page";
?>


<div class="container">
    <h3>All Authors</h3>
    <div class="row">
        
        <?php
        if (isset($authors) && !empty($authors)) {
            foreach ($authors as $author) {
                
                $author['profile_json_text'] = json_decode($author['profile_json_text'], true);
                
                ?>
                <div class="col-sm-6 col-md-3">
                    <div class="card small-card" style="margin-bottom: 20px;">
                        <img src="public <?= isset($author["image_path"]) ? $author["image_path"] : "/images/default-ava.png" ?>" class="card-img-top" alt="avatar">
                        <div class="card-body">
                            <h6 class="card-title"><?= htmlspecialchars($author['full_name']) ?></h6>
                            <p class="card-text"><?= htmlspecialchars(substr($author['profile_json_text']['bio'], 0, 40)) ?>...</p>
                            <a href="?action=author-details&author_id=<?= $author['user_id'] ?>" class="btn btn-sm btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        else {
            echo "<p>No authors found.</p>";
        }
        ?>
    </div>
</div>


<?php
$cont = ob_get_clean();

require_once 'views/layout.php';