<?php
ob_start();
$title = "Login";
?>


<form action='?action=login' method='post' class='login-form'>
    <h2>Login</h2>
    <label for='email'>Email:</label>
    <input type='text' id='email' name='email' required>

    <label for='password'>Password:</label>
    <input type='password' id='password' name='password' required>
    
    <?php
    if (isset($error) && !empty($error)) {
        echo "<div class='alert-box'>$error</div>";
    }
    ?>
    <button type='submit'>Login</button>
</form>


<?php
$cont = ob_get_clean();
require_once 'layout.php';
