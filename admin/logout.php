<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

logoutAdmin();
header('Location: login.php?message=logged_out');
exit();
