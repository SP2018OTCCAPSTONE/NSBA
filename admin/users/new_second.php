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
// $type = $_GET['type'];
// $id = $_GET['id'];// This is the paying corporate member user id
parse_str($_GET['data']);
$type = $dataArray['type'];
$id = $dataArray['id'];
$parent = $dataArray['parent'];
echo $type;
echo $id;
echo $parent;// Put this in a hidden <input>?


// Process the submitted form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // LOGIC CODE HERE TO GO TO 2ND/3RD FORMS FOR CORP2/CORP3 ASSOCIATE MEMBERS
  if ($user->save($_POST)) {
    if ($type == '7') { //isset($_POST['memberType']) && $_POST['memberType'] == '7'
    
        Util::redirect('/NSBA/admin/users/new_third.php?id=' . $id . '&data=' .$data. '');
    }
    Util::redirect('/NSBA/admin/users/show.php?user_id=' . $id . '&data=' .$data. '');
  } 
}


// Show the page header, then the rest of the HTML
include('../../includes/header.php');

?>

<h1>Create 2nd Corporate New User</h1>

<?php include('form.php'); ?>
    
<?php include('../../includes/footer.php'); ?>
