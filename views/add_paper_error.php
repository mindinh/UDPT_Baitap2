<?php
ob_start();
$title = "Add Paper Error";
?>

<div class="alert alert-danger" role="alert">
    <h4 class="alert-heading">Error!</h4>
    <p><?php echo $errorMessage; ?></p>
</div>

<?php
$cont = ob_get_clean();
require_once 'views/layout.php';