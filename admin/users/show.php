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

$line1 = htmlspecialchars($user->line_1);
$line2 = htmlspecialchars($user->line_2);
$city = htmlspecialchars($user->city);
$state = htmlspecialchars($user->state);
$zip = htmlspecialchars($user->zip);
$cell = isset($user->cell_phone) && (string) $user->cell_phone !== '' ? htmlspecialchars($user->cell_phone) : ' - ';
$work = isset($user->work_phone) && (string) $user->work_phone !== '' ? htmlspecialchars($user->work_phone) : ' - ';
$fax = isset($user->fax) && (string) $user->fax !== '' ? htmlspecialchars($user->fax) : ' - ';

$address = $line1 !== '' ? $line2 !== '' ? nl2br($line1 ."\r\n". $line2 ."\r\n". $city .', '. $state .' '. $zip) 
: nl2br($line1 ."\r\n". $city .', '. $state .' '. $zip) : ' - ';

//$bullet = " \u{8226} ";
$bullet = mb_convert_encoding('&#8226;', 'UTF-8', 'HTML-ENTITIES');

?>

<h1>User</h1>

<p><a href="/NSBA/admin/users">&laquo; Back To List Of Users</a></p>

<dl>
  <dt>Image</dt>
  <dd><img src="../../images/<?php echo htmlspecialchars($user->image) ?>" alt="user image"></dd>
  <dt>Name</dt>
  <dd><?php echo htmlspecialchars($user->first_name.' '.$user->last_name); ?></dd>
  <dt>Membership Type</dt>
  <dd><?php echo htmlspecialchars($user->type_name); ?></dd>
  <dt>Company/Organization</dt>
  <dd><?php echo htmlspecialchars($user->company); ?></dd>
  <dt>Address</dt> 
  <dd><?php echo $address; ?></dd>
  <dt>Email/Username</dt> 
  <dd><?php echo isset($user->email_1) ? htmlspecialchars($user->email_1) : '-'; ?></dd>
  <dt>Secondary Email</dt> 
  <dd><?php echo isset($user->email_2) && (string) $user->email_2 !== ''  ? htmlspecialchars($user->email_2) : '-'; ?></dd>
  <dt>Phone Numbers</dt> 
  <dd><i>Cell </i><?php echo $cell.' '.$bullet ?><i> Work </i><?php echo $work.' '.$bullet ?><i> Fax </i><?php echo $fax; ?></dd>
  <dt>Enabled</dt>
  <dd style="<?php echo $user->is_enabled ? 'color: green;' : 'color: red;'; ?>"><?php echo $user->is_enabled ? '&#10004;' : '&#10008;'; ?></dd>
  <dt>Administrator</dt>
  <dd style="<?php echo $user->is_admin ? 'color: green;' : 'color: red;'; ?>"><?php echo $user->is_admin ? '&#10004;' : '&#10008;'; ?></dd>
  <dt>Report Permissions</dt>
  <dd style="<?php echo $user->has_permissions ? 'color: green;' : 'color: red;'; ?>"><?php echo $user->has_permissions ? '&#10004;' : '&#10008;'; ?></dd>
  <dt>Board Member</dt>
  <dd style="<?php echo $user->board_member ? 'color: green;' : 'color: red;'; ?>"><?php echo $user->board_member ? '&#10004;' : '&#10008;'; ?></dd>
</dl>

<ul>
    
  <li><a class = "btn btn-warning" href="/NSBA/admin/users/edit.php?user_id=<?php echo $user->user_id; ?>">Edit</a></li>
  <li>
    <?php if ($user->user_id == Auth::getInstance()->getCurrentUser()->user_id): ?>
      <a class = "btn btn-danger disabled" href="/NSBA/admin/users/delete.php?user_id=<?php echo $user->user_id; ?>">Delete</a>
    <?php else: ?>
      <a class = "btn btn-danger" href="/NSBA/admin/users/delete.php?user_id=<?php echo $user->user_id; ?>">Delete</a>
    <?php endif; ?>
  </li>
</ul>
    
<?php include('../../includes/footer.php'); ?>
