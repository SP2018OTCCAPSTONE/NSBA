<!--*** Header ****************************** -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="/NSBA/newEditionStyle.css" rel ="stylesteet">
</head>
    <body>
        <div class="container shadow" id="page">
            <div id = "nav" class ="shadow" role="navigation">
                <ul>
                <li class ="current"><a href="/NSBA">Home</a></li> <!-- this needs fixed -->

                <?php if (Auth::getInstance()->isLoggedIn()): ?>

                    <?php if (Auth::getInstance()->isAdmin()): ?>
                    <li><a href="/NSBA/admin/users">Admin</a></li>
                    <?php endif; ?>
                    <li><a href="/NSBA/profile.php">Profile</a></li>
                    <li><a href="/NSBA/logout.php">Logout</a></li>

                <?php else: ?>

                    <li><a href="/NSBA/login.php">Login</a></li>

                <?php endif; ?>
                </ul>
            </div>    
