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

parse_str($_GET['data']);
$criteria = $dataArray['criteria'];
if(isset($dataArray['title']) && (string) $dataArray['title'] !== '') {
  $title = $dataArray['title'];
}

//$id = $dataArray['id'];

//<?php var_dump($criteria);

// Get the paginated data
$data = User::paginate(isset($_GET['page']) ? $_GET['page'] : 1, $dataArray);

// Show the page header, then the rest of the HTML
include('../../includes/header.php');

?>

<h1><?php echo $title ?></h1>

<p><a href="/NSBA/admin/users/new_first.php" class="btn btn-warning">Add A New User</a></p>

<?php include('../../includes/sidebar.php'); ?>
<div id='listContainer'>
  <table id = "MembershipTable">
    <thead>
      <tr>
        <th>Name</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($data['users'] as $user): ?>
      <?php $name = $user['last_name'].', '.$user['first_name']; ?>
      <tr>
          <td><a href="/NSBA/admin/users/show.php?user_id=<?php echo $user['user_id']; ?>&data=<?php echo urlencode($_GET['data']); ?>"><?php echo htmlspecialchars($name); ?></a></td>
          
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<ul>
  <li>
    <?php if ($data['previous'] === null): ?>
      <!--Previous-->
      <a class = "btn btn-success disabled" href="#">Previous</a>
    <?php else: ?>
      <a class = "btn btn-success" href="/NSBA/admin/users/?page=<?php echo $data['previous']; ?>&data=<?php echo urlencode($_GET['data']); ?>">Previous</a>
    <?php endif; ?>
  </li>
  <li>
    <?php if ($data['next'] === null): ?>
      <a id = "nextBtn" class = "btn btn-success disabled" href="#">Next</a>
    <?php else: ?>
      <a class = "btn btn-success" href="/NSBA/admin/users/?page=<?php echo $data['next']; ?>&data=<?php echo urlencode($_GET['data']); ?>">Next</a>
    <?php endif; ?>
  </li>
</ul>
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
        <div class="modal-footer justify-content-center">
        <button type="submit" class="btn btn-success btn-default" data-dismiss="modal"><span class=""></span>Export to Excel</button>
        <button type="submit" class="btn btn-info btn-default" data-dismiss="modal"><span class=""></span>Print</button>
        <button type="submit" class="btn btn-warning btn-default" data-dismiss="modal"><span class=""></span> Export to Csv</button>
        </div>
      </div>
    </div>
  
    <script src="../../includes/utility.js">
    
<?php include('../../includes/footer.php'); ?>
