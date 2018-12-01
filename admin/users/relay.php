<?php
require_once('../../includes/init.php');

// Require the user to be logged in before they can see this page.
Auth::getInstance()->requireLogin();

$id = $_GET['report'];

// Maybe a switch statement here for all reports
if($id == '4') {
    $mailingList = Util::getMailingList($id);
    echo json_encode($mailingList);
}


?>