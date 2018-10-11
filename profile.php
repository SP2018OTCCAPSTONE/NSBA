<?php

/**
 * Profile
 */

// Initialisation
require_once('includes/init.php');

// Require the user to be logged in before they can see this page.
Auth::getInstance()->requireLogin();

// Set the title, show the page header, then the rest of the HTML
$page_title = 'Profile';
include('includes/header.php');

?>

<h1>Profile</h1>

  <?php $user = Auth::getInstance()->getCurrentUser(); ?>

  <dl>
    <dt>Name</dt>
    <dd><?php echo htmlspecialchars($user->firstName); ?></dd>
    <dt>email address</dt>
    <dd><?php echo htmlspecialchars($user->email); ?></dd>
  </dl>
    
<?php include('includes/footer.php'); ?>
