<?php

/**
 * Log in a user
 */

// Initialisation
require_once('includes/init.php');

// Require the user to NOT be logged in before they can see this page.
Auth::getInstance()->requireGuest();

// Get checked status of the "remember me" checkbox
$remember_me = isset($_POST['remember_me']);

// <?php $str = "passwords";
// echo password_hash($str, PASSWORD_DEFAULT);
//$dbName = Database::getDBname();

// Process the submitted form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email1 = $_POST['email1'];

    if (Auth::getInstance()->login($email1, $_POST['password'], $remember_me)) {

    // Redirect to home page or intended page, if set
        if (isset($_SESSION['return_to'])) {
            $url = $_SESSION['return_to'];
            unset($_SESSION['return_to']);
        } else {
            $url = '/NSBA/index.php';
        }

        Util::redirect($url);
    }
}


// Set the title, show the page header, then the rest of the HTML
$current = 'login';
include('includes/header.php');

?>

<h1>Login</h1>

<?php if (isset($email1)): ?> 
  <p>Invalid login</p>
<?php endif; ?>

<form method="post">
  <div>
    <label for="email1">Email Address</label>
    <input id="email1" name="email1" value="<?php echo isset($email1) ? htmlspecialchars($email1) : ''; ?>" />
  </div>

  <div>
    <label for="password">Password</label>
    <input type="password" id="password" name="password" />
  </div>

  <div>
    <label for="remember_me">
      <input id="remember_me" name="remember_me" type="checkbox" value="1"
             <?php if ($remember_me): ?>checked="checked"<?php endif; ?>/> remember me
    </label>
  </div>

  <input type="submit" value="Login" />
  <a href="forgot_password.php">I forgot my password</a>
</form>

<?php include('includes/footer.php'); ?>
