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

if(isset($_GET['data'])) {
  parse_str($_GET['data']);
  $criteria = $dataArray['criteria'];
  $title = $dataArray['title'];
}

if(isset($_GET['member'])) {
  parse_str($_GET['member']);
  $type = $memberArray['type'];
  $id = $memberArray['id'];
  $parent = $memberArray['parent'];// this is parent membership id
}
// // troubleshooters
// echo $type;
// echo $id;
// echo $parent;

$user = new User();
//$type = $_GET['type'];
$id = $_GET['id'];// This is the paying corporate member user id

// Process the submitted form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  if ($user->save($_POST)) {
    // Redirect to show page for parent member
    Util::redirect('/NSBA/admin/users/show.php?user_id=' . $id . '&data=' .urlencode($_GET['data']). '');  
  }
}


// Show the page header, then the rest of the HTML
include('../../includes/header.php');

?>

<h1>Create 3rd Corporate Associate Member</h1>

<?php include('form.php'); ?>
    
<?php include('../../includes/footer.php'); ?>
