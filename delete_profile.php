<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'uitm_eprofile');

if ($_SESSION['role'] !== 'admin') {
    header('Location: list_profiles.php');
    exit;
}

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM profiles WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header('Location: list_profiles.php');
    exit;
}
?>
