<?php

/**
 * User admin index - list all users
 */

// Initialisation
require_once('includes/init.php');

// Require the user to be logged in before they can see this page.
Auth::getInstance()->requireLogin();

// Require the user to be a permissioned user before they can see this page.
Auth::getInstance()->requirePermissions();

$current = "reports";

$yr = date('Y');

// Show the page header, then the rest of the HTML
include('includes/header.php');

?>

<h1 id='reportsTitle'>Reports</h1>


<?php include('includes/sidebar.php'); ?>

<!-- Reports Modal -->
<div class="modal fade" id="reportModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <div id="mail">

          </div>
        </div>
        <div class="modal-footer justify-content-center">
        <button type="submit" class="btn btn-success btn-default" data-dismiss="modal"><span class=""></span>Export to Excel</button>
        <button type="submit" class="btn btn-info btn-default" data-dismiss="modal"><span class=""></span>Print</button>
        <button type="submit" class="btn btn-warning btn-default" data-dismiss="modal"><span class=""></span> Export to Csv</button>
        </div>
      </div>
    </div>
    
    <script src="includes/utility.js">
    
<?php include('includes/footer.php'); ?>
