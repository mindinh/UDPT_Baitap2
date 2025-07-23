<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/css/login.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/css/user_profile.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/css/authors.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg px-4" style="background-color: #3c75b1;">
        <span class="navbar-brand fw-bold text-white">UDPT BaiTap2</span>

            <div class="collapse navbar-collapse justify-content-between">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link text-white" href="?action=home">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="?action=paper-search">Search</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="?action=papers">Papers</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="?action=authors">Authors</a></li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['username'])): ?>
                <li class="nav-item"><a class="nav-link text-white" href="?action=profile">Profile</a></li>
                <li class="nav-item">
                    <span class="nav-link text-white fw-bold">
                    Hi, <?= htmlspecialchars($_SESSION['username']) ?>!
                    </span>
                </li>
                <li class="nav-item"><a class="nav-link text-white" href="?action=logout">Logout</a></li>
                <?php else: ?>
                <li class="nav-item"><a class="nav-link text-white" href="?action=login">Login</a></li>
                <?php endif; ?>
            </ul>
            </div>
        </nav>
    </header>


    <main>
        
        <?php
        if (isset($cont)) {
            echo $cont;
        } else {
            echo "<p>No content available.</p>";
        }
        ?>
    </main>
    <footer class="text-white text-center px-4 fw-bold" style="background-color: #3c75b1;">
        <p class="m-0 py-3">21127353 - Đinh Thiện Minh</p>
    </footer>
    <script src="public/js/search.js"></script>
</body>
</html>