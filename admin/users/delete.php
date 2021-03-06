<?php

/**
 * User admin - delete a user
 */

// Initialisation
require_once('../../includes/init.php');

// Require the user to be logged in before they can see this page.
Auth::getInstance()->requireLogin();

// Require the user to be an administrator before they can see this page.
Auth::getInstance()->requireAdmin();

if(isset($_GET['data'])) {
  parse_str($_GET['data']);
  $criteria = $dataArray['criteria'];
  $title = $dataArray['title'];
}

// Find the user or show a 404 page.
$user = User::getByIDor404($_GET);

// Process the submitted form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $user->delete();

  // Redirect to index page
  Util::redirect('/NSBA/admin/users/index.php?data=' .urlencode($_GET['data']). '');
}

// Show the page header, then the rest of the HTML
include('../../includes/header.php');

?>

<h1>Delete User</h1>

<form method="post">

  <p>Are you sure?</p>

  <input type="submit" value="Delete" class = "btn btn-danger"/>
  <a class = "btn btn-warning" href="/NSBA/admin/users/delete.php?user_id=<?php echo $user->user_id; ?>&data=<?php echo urlencode($_GET['data']); ?>">Cancel</a>
</form>

<?php include('../../includes/footer.php'); ?>
