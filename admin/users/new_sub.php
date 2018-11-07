<?php

/**
 * User admin - add a new user
 */

// Initialisation
require_once('../../includes/init.php');

$current = 'new_sub';

// Require the user to be logged in before they can see this page.
Auth::getInstance()->requireLogin();

// Require the user to be an administrator before they can see this page.
Auth::getInstance()->requireAdmin();


$user = new User();
// $sub1 = new User();
// $sub2 = new User();


// Process the submitted form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // LOGIC CODE HERE TO GO TO 2ND/3RD FORMS FOR CORP2/CORP3 ASSOCIATE MEMBERS
  if ($user->save($_POST)) {
    // Redirect to show page
    Util::redirect('/NSBA/admin/users/show.php?userId=' . $user->userId);// php variable here with passed user_id
    
  }
}


// Show the page header, then the rest of the HTML
include('../../includes/header.php');

?>

<h1>Create New User</h1>

<?php include('form.php'); ?>
    
<?php include('../../includes/footer.php'); ?>
