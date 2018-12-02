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

if(isset($_GET['data'])) {
  parse_str($_GET['data']);
  $criteria = $dataArray['criteria'];
  $title = $dataArray['title'];
}

$user = new User();

parse_str($_GET['member']);
$type = $memberArray['type'];
$id = $memberArray['id'];
$parent = $memberArray['parent'];
echo $type;
echo $id;
echo $parent;// Put this in a hidden <input>?


// Process the submitted form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($user->save($_POST)) {
    // Go to new_third if Corporate 2
    if ($type == '7') { 
    
      Util::redirect('/NSBA/admin/users/new_third.php?id=' . $id . '&member=' .urlencode($_GET['member']). '&data=' . urlencode($_GET['data']) . '');
    }
    Util::redirect('/NSBA/admin/users/show.php?user_id=' . $id . '&data=' . urlencode($_GET['data']) . '');
  } 
}


// Show the page header, then the rest of the HTML
include('../../includes/header.php');

?>

<h1>Create 2nd Corporate New User</h1>

<?php include('form.php'); ?>
    
<?php include('../../includes/footer.php'); ?>
