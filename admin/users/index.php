<?php

/**
 * User admin index - list all users
 */

// Initialisation
require_once('../../includes/init.php');

// Require the user to be logged in before they can see this page.
Auth::getInstance()->requireLogin();

// Require the user to be an administrator before they can see this page.
Auth::getInstance()->requireAdmin();

$current = "admin";

$yr = date('Y');


// Get the paginated data
$data = User::paginate(isset($_GET['page']) ? $_GET['page'] : 1);

// Show the page header, then the rest of the HTML
include('../../includes/header.php');

?>

<h1>All Membership <?php echo date('Y') ?></h1>

<p><a href="/NSBA/admin/users/new_first.php" class="btn btn-warning">Add A New User</a></p>

<?php include('../../includes/sidebar.php'); ?>

<table>
  <thead>
    <tr>
      <th>Name</th>
      <!--<th>Email</th>-->
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['users'] as $user): ?>
    <?php $name = $user['last_name'].', '.$user['first_name']; ?>
    <tr>
        <td><a href="/NSBA/admin/users/show.php?user_id=<?php echo $user['user_id']; ?>"><?php echo htmlspecialchars($name); ?></a></td>
        
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>


<ul>
  <li>
    <?php if ($data['previous'] === null): ?>
      <!--Previous-->
      <a class = "btn btn-success disabled" href="#">Previous</a>
    <?php else: ?>
      <a class = "btn btn-success" href="/NSBA/admin/users/?page=<?php echo $data['previous']; ?>">Previous</a>
    <?php endif; ?>
  </li>
  <li>
    <?php if ($data['next'] === null): ?>
      <a id = "nextBtn" class = "btn btn-success disabled" href="#">Next</a>
    <?php else: ?>
      <a class = "btn btn-success" href="/NSBA/admin/users/?page=<?php echo $data['next']; ?>">Next</a>
    <?php endif; ?>
  </li>
</ul>
<!-- <div id="test"></div> -->
</div> 
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
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
        </div>
      </div>
      
    </div>
  
    <script src="../../includes/utility.js">
    
<?php include('../../includes/footer.php'); ?>
