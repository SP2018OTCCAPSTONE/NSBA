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

// Get the user
$user = Auth::getInstance()->getCurrentUser();

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

$bullet = mb_convert_encoding('&#8226;', 'UTF-8', 'HTML-ENTITIES');

?>

<h1>Profile</h1>

  <dl>
  <dt>Image</dt>
  <dd><img src="/NSBA/images/<?php echo htmlspecialchars($user->image) ?>" alt="user image"></dd>
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

  <ul>
  <li><a class = "btn btn-warning" href="#">Edit</a></li>
  </ul>
<?php include('includes/footer.php'); ?>
