<?php
require_once "./database/config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM payment WHERE serial_number = :id";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("location: index.php?deleted=1");
    } else {
        echo "Error deleting the record.";
    }
} else {
    header("location: index.php");
}
