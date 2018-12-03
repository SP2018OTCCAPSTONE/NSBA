<!--*** Header ****************************** -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="/NSBA/newEditionStyle.css" rel ="stylesheet">

    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->

</head>
    <body>

        <div class="container shadow" id ="page">
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
