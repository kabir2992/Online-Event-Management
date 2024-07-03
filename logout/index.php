<?php
session_start(); // Start the session (if not already started)
session_destroy();
clearstatcache();
// Redirect back to the home page (index.php) after logout
header('Location: /ems/login.php');