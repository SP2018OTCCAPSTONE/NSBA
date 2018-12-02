<?php

/**
 * User admin - add a new user
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

$current = "new_first";

$user = new User();


// Process the submitted form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // TODO: LOGIC CODE HERE TO GO TO 2ND/3RD FORMS FOR CORP2/CORP3 ASSOCIATE MEMBERS
  if ($user->save($_POST)) {

    $user->user_id = User::getParentId();
    $parentMembershipId = User::getParentMembershipId();// Put this in a hidden <input>?
    // Redirect to new submember page if Corp2 or Corp3
    if (isset($_POST['memberType']) && $_POST['memberType'] == '6' || $_POST['memberType'] == '7') {
      $raw = array(
        'id' => $user->user_id,
        'parent' => $parentMembershipId,
        'type' => $_POST['memberType']
      );
      $data = http_build_query(array('dataArray' => $raw));
      $data = urlencode($data);
      Util::redirect('/NSBA/admin/users/new_second.php?data='.$data.'');
    }
    // Else redirect to show page
    Util::redirect('/NSBA/admin/users/show.php?user_id=' . $user->user_id);
  }
}


// Show the page header, then the rest of the HTML
include('../../includes/header.php');

?>

<h1>Create New User</h1>

<?php include('form.php'); ?>
    
<?php include('../../includes/footer.php'); ?>
