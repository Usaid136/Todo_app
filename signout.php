<?php
session_start();

session_unset();
session_destroy();
//redirecting me to signin
header("location: signin.php");
?>