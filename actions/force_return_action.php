<?php
session_start();
include '../config.php';

// Check admin session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rental_id = (int)$_POST['rental_id'];
    $admin_pass = $_POST['admin_pass'];
    $return_date = date("Y-m-d");

    // Simple Admin PIN check (Realism)
    if ($admin_pass === "1234") {
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
                echo "<script>alert('✅ Forced Return Successful'); window.location='../rental_reports.php';</script>";
            } else {
                echo "<script>alert('❌ Database error'); window.location='../rental_reports.php';</script>";
            }
        }
    } else {
        echo "<script>alert('❌ Invalid Admin PIN. Access Denied.'); window.location='../rental_reports.php';</script>";
    }
} else {
    header("Location: ../rental_reports.php");
}
exit();
?>
