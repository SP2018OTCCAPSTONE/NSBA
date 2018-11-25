<?php

/**
 * User admin - add a new user
 */

// Initialisation
require_once('../../includes/init.php');

$current = 'new_third';

// Require the user to be logged in before they can see this page.
Auth::getInstance()->requireLogin();

// Require the user to be an administrator before they can see this page.
Auth::getInstance()->requireAdmin();


$user = new User();
//$type = $_GET['type'];
$id = $_GET['id'];// This is the paying corporate member user id

// Process the submitted form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // LOGIC CODE HERE TO GO TO 2ND/3RD FORMS FOR CORP2/CORP3 ASSOCIATE MEMBERS
  if ($user->save($_POST)) {
    // Redirect to show page
    Util::redirect('/NSBA/admin/users/show.php?user_id=' . $id);  
  }
}


// Show the page header, then the rest of the HTML
include('../../includes/header.php');

?>

<h1>Create 3rd Corporate New User</h1>

<?php include('form.php'); ?>
    
<?php include('../../includes/footer.php'); ?>
