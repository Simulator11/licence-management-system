<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

require_once "db.php";

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_stmt = $conn->prepare("SELECT full_name, email FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_stmt->store_result();
$user_stmt->bind_result($full_name, $email);
$user_stmt->fetch();

// Fetch active license details
$license_stmt = $conn->prepare("SELECT control_number, license_status, expiry_date FROM licenses WHERE user_id = ? AND license_status = 'Active'");
$license_stmt->bind_param("i", $user_id);
$license_stmt->execute();
$license_stmt->store_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - License Management</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #00bfff;
            --dark-color: #343a40;
        }
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
        }
        .navbar-nav .nav-link {
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.3);
        }
        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
            border: none;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .menu-card {
            cursor: pointer;
            height: 100%;
        }
        .menu-card .card-body {
            padding: 2rem 1rem;
        }
        .menu-card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .profile-card {
            border-top: 5px solid var(--primary-color);
        }
        .profile-card i {
            font-size: 4rem;
            color: var(--primary-color);
        }
        .license-card {
            border-top: 5px solid #28a745;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 10px 25px;
        }
        .btn-danger {
            padding: 10px 25px;
        }
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 1.5rem 0;
            margin-top: auto;
        }
        footer a {
            color: #adb5bd;
            transition: color 0.3s;
        }
        footer a:hover {
            color: white;
            text-decoration: none;
        }
        .alert-success {
            border-left: 5px solid #28a745;
        }
    </style>
</head>
<body>

<!-- Enhanced Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="homepage.php">
            <i class="fas fa-id-card-alt me-2"></i>License System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="homepage.php"><i class="fas fa-home me-1"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="payment.php"><i class="fas fa-credit-card me-1"></i> Payments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contacts.php"><i class="fas fa-address-book me-1"></i> Contacts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about_us.php"><i class="fas fa-info-circle me-1"></i> About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.php"><i class="fas fa-concierge-bell me-1"></i> Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container my-4">
    <!-- User Profile Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card profile-card">
                <div class="card-body text-center py-4">
                    <i class="fas fa-user-circle"></i>
                    <h3 class="mt-3"><?php echo htmlspecialchars($full_name); ?></h3>
                    <p class="text-muted mb-4"><?php echo htmlspecialchars($email); ?></p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="manage_profile.php" class="btn btn-primary">
                            <i class="fas fa-user-edit me-2"></i>Manage Profile
                        </a>
                        <a href="logout.php" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- License Status Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card license-card">
                <div class="card-body">
                    <?php
                    if (isset($_SESSION['success_message'])) {
                        echo "<div class='alert alert-success alert-dismissible fade show'>" . 
                             $_SESSION['success_message'] . 
                             '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        unset($_SESSION['success_message']);
                    }

                    if ($license_stmt->num_rows > 0) {
                        $license_stmt->bind_result($control_number, $license_status, $expiry_date);
                        $license_stmt->fetch();
                        
                        echo '<div class="text-center">';
                        echo '<h3 class="text-success mb-4"><i class="fas fa-check-circle me-2"></i>Your License is Active</h3>';
                        echo '<div class="d-flex justify-content-center gap-5 mb-4">';
                        echo '<div><h5>Control Number</h5><p class="lead">' . $control_number . '</p></div>';
                        echo '<div><h5>Expiry Date</h5><p class="lead">' . $expiry_date . '</p></div>';
                        echo '</div>';
                        
                        if ($license_status == "Active") {
                            echo '<div class="text-center mt-3">';
                            echo '<a href="generate_pdf.php" class="btn btn-primary btn-lg">';
                            echo '<i class="fas fa-file-pdf me-2"></i>Download License PDF</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="text-center">';
                        echo '<h3 class="text-danger"><i class="fas fa-exclamation-circle me-2"></i>No active license found.</h3>';
                        echo '<p class="lead mt-3">Please apply and complete the payment to activate your license.</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Menu Cards -->
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card menu-card h-100" onclick="window.location.href='create_license.php'">
                <div class="card-body text-center">
                    <i class="fas fa-id-card"></i>
                    <h5 class="card-title mt-3">Create License</h5>
                    <p class="card-text">Start the process of creating a new license.</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card menu-card h-100" onclick="window.location.href='renew_license.php'">
                <div class="card-body text-center">
                    <i class="fas fa-sync"></i>
                    <h5 class="card-title mt-3">Renew License</h5>
                    <p class="card-text">Renew an existing license with ease.</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card menu-card h-100" onclick="window.location.href='view_licenses.php'">
                <div class="card-body text-center">
                    <i class="fas fa-eye"></i>
                    <h5 class="card-title mt-3">View Licenses</h5>
                    <p class="card-text">Check your active and expired licenses.</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card menu-card h-100" onclick="window.location.href='payment.php'">
                <div class="card-body text-center">
                    <i class="fas fa-money-bill-wave"></i>
                    <h5 class="card-title mt-3">Make Payment</h5>
                    <p class="card-text">Complete payment for your license.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">© <?php echo date("Y"); ?> License Management System. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0">
                    <a href="privacy.php">Privacy Policy</a> | 
                    <a href="terms.php">Terms of Service</a>
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

</body>
</html>