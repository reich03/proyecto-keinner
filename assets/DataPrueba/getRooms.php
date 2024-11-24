<?php
header('Content-Type: application/json');
require_once './rooms.php';
echo json_encode($rooms);
?>
