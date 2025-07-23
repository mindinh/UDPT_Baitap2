<?php
ob_start();
$title = "Paper Details";
?>

<div style='border: 1px solid #ccc; padding: 20px; margin: 20px;'>
    <h2 style='color: #333;'><?= $paper['title'] ?></h2>
    <p><strong>Abstract: </strong><?= $paper['abstract'] ?></p>
    <p><strong>Author(s):</strong></p>
    <ul>
        <?php
            if (isset($_SESSION['userId']) && $_SESSION['role'] === 'admin') {
                foreach ($authors as $author) {
                    $auth_id = $author['user_id'];
                    echo "<li style='margin-top: 3px; '>" . "<a href='?action=author-details&author_id=$auth_id'>" . trim($author['full_name']) . "</a>" .
                    "<a href='?action=remove-author&paperId=$paperId&author=" . urlencode(trim($author['full_name'])) . 
                    "' class='btn btn-danger btn-sm' style='margin-left: 10px;'><i class='fas fa-trash' style='font-size:15px;'></i></a></li>";
                }
            } else {
                foreach ($authors as $author) {
                    $auth_id = $author['user_id'];
                    echo "<li><a href='?action=author-details&author_id=$auth_id'>" . trim($author['full_name']) . "</a></li>";
                }
            }
        ?>
    </ul>
    <p><strong>Conference: </strong><?= $paper['conference_name'] ?></p>
    <p><strong>Topic: </strong><?= $paper['topic_name'] ?></p>
    <p>
        <strong>Month: </strong>
        <?= date('F d', strtotime($paper['start_date'])) ?>
        -
        <?= date('F d', strtotime($paper['end_date'])) ?>
    </p>
    <p>
        <strong>Year: </strong>
        <?= (date('Y', strtotime($paper['start_date'])) === date('Y', strtotime($paper['end_date']))) 
        ? date('Y', strtotime($paper['start_date'])) 
        : date('Y', strtotime($paper['start_date'])) . ' - ' . date('Y', strtotime($paper['end_date'])) 
        ?>
    </p>
</div>
<?php if (isset($_SESSION['userId'])): ?>
    <form action='?action=add-author&id=<?=$paper['paper_id']?>' method='post' style='margin-left: 20px;'>
        <button type='submit' id='addAuthorButton' class='btn btn-primary'>Add Author</button>
    </form>
<?php endif; ?>

<?php
$cont = ob_get_clean();

require_once 'views/layout.php';