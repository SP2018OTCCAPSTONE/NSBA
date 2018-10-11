<?php

/**
 * Cleanup expired remember tokens ****************NEED CHRON JOB FOR THIS!!!
 */

// Initialisation
require_once('includes/init.php');

echo User::deleteExpiredTokens();

?>
