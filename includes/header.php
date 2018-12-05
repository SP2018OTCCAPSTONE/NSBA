<!--*** Header ****************************** -->
<?php  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="/NSBA/newEditionStyle.css" rel ="stylesheet">

</head>
    <body>

        <div class="container shadow" id ="page">
            <div #id = "logo" >
            <img  src = "/NSBA/images/nsba-logo.png" width ="200" alt. ="NSBA Logo">
            <h3 >Member Portal</h3>
            </div>

            <div id = "nav" class ="shadow" role="navigation">
                <ul>
                <li><a href="/NSBA">Home</a></li> 

                <?php if (Auth::getInstance()->isLoggedIn()): ?>

                    <?php if (Auth::getInstance()->isAdmin()): ?>
                    <?php
                        $raw = array(
                            'criteria' => '0',
                            'title' => 'All Membership'
                          );
                          $search = http_build_query(array('dataArray' => $raw));
                          $search = urlencode($search);
                    ?>
                    <li><a href="/NSBA/admin/users/index.php?data=<?php echo $search ?>">Admin</a></li>
                    <?php endif; ?>
                    <?php if (Auth::getInstance()->hasPermissions()): ?>
                    <li><a href="/NSBA/reports.php">Reports</a></li>
                    <?php endif; ?>
                    <li><a href="/NSBA/profile.php">Profile</a></li>
                    <li><a href="/NSBA/logout.php">Logout</a></li>

                <?php else: ?>

                    <li><a href="/NSBA/login.php">Login</a></li>

                <?php endif; ?>
                </ul>
            </div>    
