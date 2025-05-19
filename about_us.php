<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: logout.php");
    exit();
}

require_once "db.php";

$user_id = $_SESSION['user_id'];

// Fetch user details for the header
$user_stmt = $conn->prepare("SELECT full_name, email FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_stmt->store_result();
$user_stmt->bind_result($full_name, $email);
$user_stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - License Management</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
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
        .about-header {
            background: linear-gradient(135deg, #007bff, #00bfff);
            color: white;
            padding: 60px 0;
            margin-bottom: 30px;
            border-radius: 0 0 10px 10px;
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #007bff;
        }
        .team-member img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid #007bff;
        }
    </style>
</head>
<body>

<!-- Navbar (same as homepage) -->
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
                    <a class="nav-link" href="payment.php">Payments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contacts.php">Contacts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="about_us.php">About Us</a>
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

<!-- About Us Header -->
<div class="about-header text-center">
    <div class="container">
        <h1 class="display-4">About Our License Management System</h1>
        <p class="lead">Streamlining license processing with secure digital solutions</p>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Our Mission</h2>
                    <p class="card-text">
                        We are committed to transforming traditional license management into a seamless digital experience. 
                        Our system provides government agencies and citizens with a secure, efficient platform for license 
                        applications, renewals, and verifications.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-shield-alt feature-icon"></i>
                    <h4>Security First</h4>
                    <p>Military-grade encryption protects all your data and transactions with multiple verification layers.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-bolt feature-icon"></i>
                    <h4>Fast Processing</h4>
                    <p>Automated workflows reduce processing times from weeks to just days or even hours.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-mobile-alt feature-icon"></i>
                    <h4>Mobile Friendly</h4>
                    <p>Access the system anytime, anywhere with our fully responsive web application.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Our Team</h2>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="assets/images/team1.jpg" alt="Team Member" class="rounded-circle team-member mb-3">
                    <h4>Seleman</h4>
                    <p class="text-muted">System Architect</p>
                    <p>10+ years experience in government digital transformation projects.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="assets/images/team2.jpg" alt="Team Member" class="rounded-circle team-member mb-3">
                    <h4>Japhsam Simulator</h4>
                    <p class="text-muted">Lead Developer</p>
                    <p>Specializes in secure document generation and verification systems.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <img src="assets/images/team3.jpg" alt="Team Member" class="rounded-circle team-member mb-3">
                    <h4>Michael Sandanda</h4>
                    <p class="text-muted">Security Specialist</p>
                    <p>Focuses on data protection and compliance with government standards.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Technology Stack -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center">Our Technology Stack</h2>
                    <div class="row text-center mt-4">
                        <div class="col-md-3 col-6 mb-4">
                            <i class="fab fa-php fa-3x text-primary mb-2"></i>
                            <h5>PHP 8.1</h5>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <i class="fab fa-js-square fa-3x text-warning mb-2"></i>
                            <h5>JavaScript</h5>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <i class="fas fa-database fa-3x text-info mb-2"></i>
                            <h5>MySQL</h5>
                        </div>
                        <div class="col-md-3 col-6 mb-4">
                            <i class="fab fa-bootstrap fa-3x text-purple mb-2"></i>
                            <h5>Bootstrap 5</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer (same style as your homepage) -->
<footer class="bg-dark text-white mt-5 py-4">
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

</body>
</html>