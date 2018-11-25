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


// Get the paginated data
$data = User::paginate(isset($_GET['page']) ? $_GET['page'] : 1);



// Show the page header, then the rest of the HTML
include('../../includes/header.php');


?>

<h1>Members and Permissioned Users</h1>

<p><a href="/NSBA/admin/users/new_first.php">Add A New User</a></p>

<?php include('../../includes/sidebar.php'); ?>

<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Email</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($data['users'] as $user): ?>
    <?php $name = $user['last_name'].', '.$user['first_name']; ?>
    <tr>
        <td><a href="/NSBA/admin/users/show.php?user_id=<?php echo $user['user_id']; ?>"><?php echo htmlspecialchars($name); ?></a></td>
        <td><?php echo isset($user['email_1']) ? htmlspecialchars($user['email_1']) : '-'; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>


<ul>
  <li>
    <?php if ($data['previous'] === null): ?>
      <!--Previous-->
      <a class = "btn btn-light disabled" href="#">Previous</a>
    <?php else: ?>
      <a class = "btn btn-light" href="/NSBA/admin/users/?page=<?php echo $data['previous']; ?>">Previous</a>
    <?php endif; ?>
  </li>
  <li>
    <?php if ($data['next'] === null): ?>
      <a id = "nextBtn" class = "btn btn-light disabled" href="#">Next</a>
    <?php else: ?>
      <a class = "btn btn-light" href="/NSBA/admin/users/?page=<?php echo $data['next']; ?>">Next</a>
    <?php endif; ?>
  </li>
</ul>

    
<?php include('../../includes/footer.php'); ?>
