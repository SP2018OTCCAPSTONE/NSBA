<?php
require_once('includes/init.php');

// Require the user to be logged in before they can see this page.
Auth::getInstance()->requireLogin();

// Require the user to be a permissioned user before they can see this page.
Auth::getInstance()->requirePermissions();

$id = $_GET['query'];

switch($id) {
    case '1':
    break;
    case '2':
    break;
    case '3':
    break;
    case '4':
    break;
    case '5':
    break;
    case '6':
    break;
    case '7':
    break;
    case '8':
    break;
    case '18':
    $mailingList = Util::getMailingList($id);
    echo json_encode($mailingList);
    default:
    break;
}

?>