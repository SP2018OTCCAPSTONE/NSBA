<?php

/**
 * Homepage
 */

// Initialisation
require_once('includes/init.php');

// Show the page header, then the rest of the HTML
include('includes/header.php');

?>

<h1>Home</h1>

<?php if (Auth::getInstance()->isLoggedIn()): ?>

    <p>Hello <?php echo htmlspecialchars(Auth::getInstance()->getCurrentUser()->firstName); ?>.</p>
    <!-- <a href="profile.php">View profile</a> or <a href="logout.php">Log out</a>.</p> -->

<?php else: ?>

    <!--<p><a href="signup.php">Sign up</a> or <a href="login.php">Log in</a></p>-->
    <p><a href="#">Sign up</a> or <a href="login.php">Log in</a></p>

<?php endif; ?>

<?php include('includes/footer.php'); ?>
