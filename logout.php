<?php
session_destroy();
session_start();
session_destroy();
// Redirect to user homepage
header("Location: user/homepage.php");
exit();
?>
