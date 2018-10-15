<?php

/**
 * Sign up a new user THIS WILL BECOME ADMIN "CREATE MEMBER" ULTIMATELY
 * MEMBERSHIP APPLICATION WILL SUBMIT TO ADMIN QUEUE -- TEMPORARY TABLE or ARRAY??
 */

// Initialisation
require_once('includes/init.php');

// Require the user to NOT be logged in before they can see this page.
Auth::getInstance()->requireGuest();

// Process the submitted form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $user = User::signup($_POST);

  if (empty($user->errors)) {
    // Redirect to signup success page
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/NSBA/signup_success.php'); 
    exit;
  }
}

// Set the title, show the page header, then the rest of the HTML
$page_title = 'Sign Up';
include('includes/header.php');

?>

<h1>Sign Up</h1>

<?php if (isset($user)): ?>
  <ul>
    <?php foreach ($user->errors as $error): ?>
      <li><?php echo $error; ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<form method="post">
  <div>
    <label for="firstName"> First Name</label>
    <input id="firstName" name="firstName" value="<?php echo isset($user) ? htmlspecialchars($user->firstName) : ''; ?>"/>
  </div>

  <div>
    <label for="lastName">Last Name</label>
    <input id="lastName" name="lastName" value="<?php echo isset($user) ? htmlspecialchars($user->lastName) : ''; ?>"/>
  </div>

  <div>
    <label for="email">Primary Email Address</label>
    <input id="email" name="email" value="<?php echo isset($user) ? htmlspecialchars($user->email) : ''; ?>"/>
  </div>

  <div>
    <label for="emailSecondary">Secondary Email Address</label>
    <input id="emailSecondary" name="emailSecondary" value="<?php echo isset($user) ? htmlspecialchars($user->emailSecondary) : ''; ?>"/>
  </div>

  <div>
    <label for="password">Password</label>
    <input type="password" id="password" name="password"/>
  </div>

  <input type="submit" value="Sign Up" />
</form>

<?php include('includes/footer.php'); ?>
