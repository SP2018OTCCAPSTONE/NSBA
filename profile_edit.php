<?php

/**
 * User admin - edit a user***************************THIS WILL BE USER PROFILE EDIT 
 */

// Initialisation
require_once('../../includes/init.php');

// Require the user to be logged in before they can see this page.
Auth::getInstance()->requireLogin();

// Require the user to be an administrator before they can see this page.
Auth::getInstance()->requireAdmin();

// Find the user or show a 404 page.
$user = User::getByIDor404($_GET);


// Process the submitted form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if ($user->save($_POST)) {

    // Redirect to show page
    Util::redirect('/NSBA/admin/users/show.php?userId=' . $user->userId);
  }
}


// Show the page header, then the rest of the HTML
include('../../includes/header.php');

?>

<h1>Edit User</h1>

<?php include('form.php'); ?>
    
<?php include('../../includes/footer.php'); ?>