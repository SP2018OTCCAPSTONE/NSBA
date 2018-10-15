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
if (isset($_GET['userId'])) {
  $user = User::findByID($_GET['userId']);
}

// Find the user or show a 404 page.
$user = User::getByIDor404($_GET);

// // Show 404 if user not found
// if ( ! isset($user)) {
//   header('HTTP/1.0 404 Not Found');
//   echo '404 Not Found';
//   exit;
// }

// Concat name
// $name = $user->firstName.' '.$user->lastName;

// Show the page header, then the rest of the HTML
include('../../includes/header.php');

?>

<h1>User</h1>

<p><a href="/NSBA/admin/users">&laquo; back to list of users</a></p>

<dl>
  <dt>Name</dt>
  <dd><?php echo htmlspecialchars($user->firstName.' '.$user->lastName); ?></dd>
  <dt>email address</dt>
  <dd><?php echo htmlspecialchars($user->email); ?></dd>
  <dt>Active</dt>
  <dd><?php echo $user->is_active ? '&#10004;' : '&#10008;'; ?></dd>
  <dt>Administrator</dt>
  <dd><?php echo $user->is_admin ? '&#10004;' : '&#10008;'; ?></dd>
</dl>

<ul>
    
  <li><a href="/NSBA/admin/users/edit.php?userId=<?php echo $user->userId; ?>">Edit</a></li>
  <li>
    <?php if ($user->userId == Auth::getInstance()->getCurrentUser()->userId): ?>
      Delete
    <?php else: ?>
      <a href="/NSBA/admin/users/delete.php?userId=<?php echo $user->userId; ?>">Delete</a>
    <?php endif; ?>
  </li>
</ul>
    
<?php include('../../includes/footer.php'); ?>
