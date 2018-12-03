<?php
require_once('../../includes/init.php');

// Require the user to be logged in before they can see this page.
Auth::getInstance()->requireLogin();

$id = $_GET['query'];
if(isset($_GET['title']) && (string) $_GET['title'] !== '') {
    $title = $_GET['title'];
}
// $title = $_GET['title'];

switch($id) {

    case '0':
    $raw = array(
        'criteria' => '0',
        'title' => $title
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '1':
    $raw = array(
        'criteria' => '1',
        'title' => $title
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '2':
    $raw = array(
        'criteria' => '2',
        'title' => $title
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '3':
    $raw = array(
        'criteria' => '3',
        'title' => $title
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '4':
    $raw = array(
        'criteria' => '4',
        'title' => $title
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '5':
    $raw = array(
        'criteria' => '5',
        'title' => $title
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '6':
    $raw = array(
        'criteria' => '6',
        'title' => $title
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '7':
    $raw = array(
        'criteria' => '7',
        'title' => $title
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '8':
    $raw = array(
        'criteria' => '8',
        'title' => $title
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '9':
    $raw = array(
        'criteria' => '9',
        'title' => $title
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '10':
    $raw = array(
        'criteria' => '10',
        'title' => $title
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '11':
    $raw = array(
        'criteria' => '11',
        'title' => $title
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '12':
    $raw = array(
        'criteria' => '0',
        'title' => $title
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '13':
    $raw = array(
        'criteria' => '13',
        'title' => $title.' '.date('Y') 
    );
    $search = http_build_query(array('dataArray' => $raw));
    $search = urlencode($search);
    Util::redirect('/NSBA/admin/users/index.php?data='.$search.'');
    break;

    case '14':
    $raw = array(
        'criteria' => '14',
        'title' => $title.' '.date('Y') 
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