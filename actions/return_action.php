<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $rental_id = (int)$_GET['id'];
    $return_date = date("Y-m-d");

    // 1. Fetch book_id from rental
    $stmt = $conn->prepare("SELECT book_id FROM rentals WHERE id = ?");
    $stmt->bind_param("i", $rental_id);
    $stmt->execute();
    $rental = $stmt->get_result()->fetch_assoc();

    if ($rental) {
        $book_id = $rental['book_id'];

        // 2. Update rental status
        $stmt = $conn->prepare("UPDATE rentals SET status = 'returned', return_date = ? WHERE id = ?");
        $stmt->bind_param("si", $return_date, $rental_id);
        
        if ($stmt->execute()) {
            // 3. Increase book quantity
            $conn->query("UPDATE books SET quantity = quantity + 1 WHERE id = $book_id");
            header("Location: ../my_rentals.php?msg=returned");
        } else {
            header("Location: ../my_rentals.php?msg=error");
        }
    }
} else {
    header("Location: ../my_rentals.php");
}
exit();
?>
