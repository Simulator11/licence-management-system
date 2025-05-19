<?php
session_start();
require_once "db.php"; // Include your database connection

if (!isset($_SESSION['control_number'])) {
    header("Location: create_license.php");
    exit();
}

$control_number = $_SESSION['control_number'];
$amount = $_SESSION['amount'];

// Generate a fake transaction ID
$fake_transaction_id = "TXN" . rand(100000, 999999);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - License Management</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white;
        }
        .navbar-toggler-icon {
            background-color: white;
        }
        .payment-card {
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border: none;
            max-width: 600px;
            margin: 2rem auto;
        }
        .payment-header {
            background: linear-gradient(135deg, #007bff, #00bfff);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        .control-number {
            font-size: 1.5rem;
            letter-spacing: 2px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border-left: 5px solid #007bff;
        }
        .btn-payment {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-payment:hover {
            background-color: #0069d9;
            transform: translateY(-2px);
        }
        .btn-simulate {
            background-color: #28a745;
            border: none;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-simulate:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 1rem 0;
            margin-top: auto;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Navigation Bar (same as other pages) -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">License Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="homepage.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="payment.php">Payments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contacts.php">Contacts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about_us.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.php">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Payment Content -->
<div class="container my-5">
    <div class="card payment-card">
        <div class="card-header payment-header text-center">
            <h2><i class="fas fa-credit-card me-2"></i>Complete Your Payment</h2>
        </div>
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <p class="lead">Please use the following control number for payment:</p>
                <div class="control-number d-inline-block px-4 py-2 mb-3">
                    <?php echo $control_number; ?>
                </div>
                <p class="h5">Amount Due: <span class="text-success">$<?php echo number_format($amount, 2); ?></span></p>
            </div>
            
            <form action="verify_payment.php" method="POST">
                <div class="mb-4">
                    <label for="transaction_id" class="form-label fw-bold">Transaction ID</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-receipt"></i></span>
                        <input type="text" name="transaction_id" id="transaction_id" class="form-control form-control-lg" required>
                    </div>
                    <small class="text-muted">Enter the transaction ID from your payment confirmation</small>
                </div>
                
                <input type="hidden" name="control_number" value="<?php echo $control_number; ?>">
                
                <div class="d-grid gap-3">
                    <button type="submit" class="btn btn-payment btn-lg">
                        <i class="fas fa-check-circle me-2"></i> Verify Payment
                    </button>
                    
                    <button type="button" class="btn btn-simulate btn-lg" onclick="fillFakeTransaction()">
                        <i class="fas fa-bolt me-2"></i> Simulate Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Footer (same as other pages) -->
<footer class="mt-auto">
    <div class="container text-center">
        <p class="mb-0">© <?php echo date("Y"); ?> License Management System. All rights reserved.</p>
        <p class="mb-0">
            <a href="privacy.php" class="text-white">Privacy Policy</a> | 
            <a href="terms.php" class="text-white">Terms of Service</a>
        </p>
    </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script>
    function fillFakeTransaction() {
        document.getElementById("transaction_id").value = "<?php echo $fake_transaction_id; ?>";
        alert('Fake transaction ID filled. Click "Verify Payment" to proceed.');
    }
</script>
</body>
</html>