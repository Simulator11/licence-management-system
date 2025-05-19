<?php
// Start the session
session_start();

// Destroy all session data
session_destroy();

// Redirect to the index page where the user can log in or register
header("Location: index.php");
exit();
?>
