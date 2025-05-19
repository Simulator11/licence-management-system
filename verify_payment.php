<?php
session_start();
require_once "db.php"; // Ensure database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transaction_id = $_POST['transaction_id'];
    $control_number = $_POST['control_number'];

    // Check if the control number exists
    $stmt = $conn->prepare("SELECT id FROM licenses WHERE control_number = ? AND payment_status = 'Pending'");
    $stmt->bind_param("s", $control_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update payment status and activate the license
        $update_stmt = $conn->prepare("UPDATE licenses SET payment_status = 'Confirmed', license_status = 'Active', expiry_date = DATE_ADD(NOW(), INTERVAL 1 YEAR) WHERE control_number = ?");
        $update_stmt->bind_param("s", $control_number);
        
        if ($update_stmt->execute()) {
            $_SESSION['success_message'] = "Payment verified successfully! Your license is now active.";
        } else {
            $_SESSION['error_message'] = "Error updating payment status.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid control number or payment already confirmed.";
    }

    $stmt->close();
    $update_stmt->close();
    $conn->close();

    // Redirect to homepage
    header("Location: homepage.php");
    exit();
}
?>
