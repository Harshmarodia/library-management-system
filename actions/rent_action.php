<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['book_id'])) {
    $book_id = (int)$_GET['book_id'];
    $user_id = $_SESSION['user_id'];
    $rent_date = date("Y-m-d");

    // 1. Check if book is available
    $stmt = $conn->prepare("SELECT quantity FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $book = $stmt->get_result()->fetch_assoc();

    if ($book && $book['quantity'] > 0) {
        // 2. Start Transaction (conceptual)
        // 3. Insert Rental
        $stmt = $conn->prepare("INSERT INTO rentals (user_id, book_id, rent_date, status) VALUES (?, ?, ?, 'rented')");
        $stmt->bind_param("iis", $user_id, $book_id, $rent_date);
        
        if ($stmt->execute()) {
            // 4. Decrease book quantity
            $conn->query("UPDATE books SET quantity = quantity - 1 WHERE id = $book_id");
            header("Location: ../my_rentals.php?msg=success");
        } else {
            header("Location: ../browse_books.php?msg=error");
        }
    } else {
        header("Location: ../browse_books.php?msg=out_of_stock");
    }
} else {
    header("Location: ../browse_books.php");
}
exit();
?>
