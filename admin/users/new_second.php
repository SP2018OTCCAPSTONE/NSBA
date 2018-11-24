<?php

/**
 * User admin - add a new user
 */

// Initialisation
require_once('../../includes/init.php');

$current = 'new_second';

// Require the user to be logged in before they can see this page.
Auth::getInstance()->requireLogin();

// Require the user to be an administrator before they can see this page.
Auth::getInstance()->requireAdmin();


$user = new User();
$type = $_GET['type'];
$id = $_GET['id'];

// Process the submitted form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // LOGIC CODE HERE TO GO TO 2ND/3RD FORMS FOR CORP2/CORP3 ASSOCIATE MEMBERS
  if ($user->save($_POST)) {
    if (isset($_POST['memberType']) && $_POST['memberType'] == '7') { 
    
        Util::redirect('/NSBA/admin/users/new_third.php?id=' . $id);
    }
    Util::redirect('/NSBA/admin/users/show.php?userId=' . $id);
  } 
}


// Show the page header, then the rest of the HTML
include('../../includes/header.php');

?>

<h1>Create New User</h1>

<?php include('form.php'); ?>
    
<?php include('../../includes/footer.php'); ?>