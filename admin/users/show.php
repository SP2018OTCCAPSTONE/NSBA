<?php

/**
 * User admin - show a user
 */

// Initialisation
require_once('../../includes/init.php');

// Require the user to be logged in before they can see this page.
Auth::getInstance()->requireLogin();

// Require the user to be an administrator before they can see this page.
Auth::getInstance()->requireAdmin();


// Get the user
if (isset($_GET['user_id'])) {
  $user = User::findByID($_GET['user_id']);
}

// Find the user or show a 404 page.
$user = User::getByIDor404($_GET);

// // Show 404 if user not found
// if ( ! isset($user)) {
//   header('HTTP/1.0 404 Not Found');
//   echo '404 Not Found';
//   exit;
// }


// Show the page header, then the rest of the HTML
include('../../includes/header.php');

?>

<h1>User</h1>

<p><a href="/NSBA/admin/users">&laquo; Back To List Of Users</a></p>

<dl>
  <dt>Name</dt>
  <dd><?php echo htmlspecialchars($user->first_name.' '.$user->last_name); ?></dd>
  <dt>Email Address</dt>
  <!-- is_null($user->email), $user->email === NULL are both slower--> 
  <dd><?php echo isset($user->email_1) ? htmlspecialchars($user->email_1) : '-'; ?></dd>
  <dt>Enabled</dt>
  <dd><?php echo $user->is_enabled ? '&#10004;' : '&#10008;'; ?></dd>
  <dt>Administrator</dt>
  <dd><?php echo $user->is_admin ? '&#10004;' : '&#10008;'; ?></dd>
</dl>

<ul>
    
  <li><a class = "btn btn-light" href="/NSBA/admin/users/edit.php?user_id=<?php echo $user->user_id; ?>">Edit</a></li>
  <li>
    <?php if ($user->user_id == Auth::getInstance()->getCurrentUser()->user_id): ?>
      Delete
    <?php else: ?>
      <a class = "btn btn-light" href="/NSBA/admin/users/delete.php?user_id=<?php echo $user->user_id; ?>">Delete</a>
    <?php endif; ?>
  </li>
</ul>
    
<?php include('../../includes/footer.php'); ?>
