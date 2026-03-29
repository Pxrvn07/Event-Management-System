<?php
session_start();
session_destroy();
header("Location: ../Login/login.html");
exit();
?>
