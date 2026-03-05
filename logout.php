<?php
// Start the session to access it
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session completely
session_destroy();

// Use JavaScript to redirect the user to the homepage
echo "<script>
    alert('You have been logged out successfully.');
    window.location.href = 'index.php';
</script>";
exit();
?>
