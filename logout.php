<?php
session_start();      // Start the session
session_destroy();    // Destroy all session data
header("Location: index.php");  // Redirect back to home page
exit();
?>
