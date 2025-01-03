<?php
session_start();
session_destroy();
header('Location: ui_profile_system.php');
exit;
?>
