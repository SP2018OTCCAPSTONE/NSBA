<?php
require_once('../../includes/init.php');

// Require the user to be logged in before they can see this page.
Auth::getInstance()->requireLogin();

$id = $_GET['query'];

// Maybe a switch statement here for all reports
//if($id == '4') {
switch($id) {

    case '0':
    $raw = array(
        'criteria' => '0'
        // 'parent' => $parentMembershipId,
        // 'type' => $_POST['memberType']
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '1':
    $raw = array(
        'criteria' => '1'
        // 'parent' => $parentMembershipId,
        // 'type' => $_POST['memberType']
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '2':
    $raw = array(
        'criteria' => '2'
        // 'parent' => $parentMembershipId,
        // 'type' => $_POST['memberType']
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '3':
    $raw = array(
        'criteria' => '3'
        // 'parent' => $parentMembershipId,
        // 'type' => $_POST['memberType']
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '4':
    $raw = array(
        'criteria' => '4'
        // 'parent' => $parentMembershipId,
        // 'type' => $_POST['memberType']
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '5':
    $raw = array(
        'criteria' => '5'
        // 'parent' => $parentMembershipId,
        // 'type' => $_POST['memberType']
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '6':
    $raw = array(
        'criteria' => '6'
        // 'parent' => $parentMembershipId,
        // 'type' => $_POST['memberType']
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '7':
    $raw = array(
        'criteria' => '7'
        // 'parent' => $parentMembershipId,
        // 'type' => $_POST['memberType']
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '8':
    $raw = array(
        'criteria' => '8'
        // 'parent' => $parentMembershipId,
        // 'type' => $_POST['memberType']
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '9':
    $raw = array(
        'criteria' => '9'
        // 'parent' => $parentMembershipId,
        // 'type' => $_POST['memberType']
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '10':
    $raw = array(
        'criteria' => '10'
        // 'parent' => $parentMembershipId,
        // 'type' => $_POST['memberType']
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '11':
    $raw = array(
        'criteria' => '11'
        // 'parent' => $parentMembershipId,
        // 'type' => $_POST['memberType']
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '18':
    $mailingList = Util::getMailingList($id);
    echo json_encode($mailingList);
    break;
    
    default:
    break;
}


?>