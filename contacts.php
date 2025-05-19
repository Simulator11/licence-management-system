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

// Handle contact form submission
$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    // Validate inputs
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        // Here you would typically save to database or send email
        $success_message = "Thank you for your message! We'll get back to you soon.";
    } else {
        $success_message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - License Management</title>
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
        .contact-header {
            background: linear-gradient(135deg, #007bff, #00bfff);
            color: white;
            padding: 60px 0;
            margin-bottom: 30px;
            border-radius: 0 0 10px 10px;
        }
        .contact-icon {
            font-size: 2rem;
            color: #007bff;
            margin-bottom: 15px;
        }
        .contact-info-card {
            transition: transform 0.3s;
        }
        .contact-info-card:hover {
            transform: translateY(-5px);
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
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
                    <a class="nav-link active" href="contacts.php">Contacts</a>
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

<!-- Contact Us Header -->
<div class="contact-header text-center">
    <div class="container">
        <h1 class="display-4">Contact Our Support Team</h1>
        <p class="lead">We're here to help with any questions about your license</p>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Contact Information -->
        <div class="col-lg-4">
            <div class="card contact-info-card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-map-marker-alt contact-icon"></i>
                    <h4>Our Office</h4>
                    <p>123 License Street<br>Government District<br>Dar es Salaam, Tanzania</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card contact-info-card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-phone-alt contact-icon"></i>
                    <h4>Call Us</h4>
                    <p>General Inquiries: +255 123 456 789<br>
                    Support: +255 987 654 321</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card contact-info-card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-envelope contact-icon"></i>
                    <h4>Email Us</h4>
                    <p>General: info@licensemgt.go.tz<br>
                    Support: support@licensemgt.go.tz</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Send Us a Message</h2>
                    <form method="POST" action="contacts.php">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Office Hours -->
    <div class="row mt-4">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-body text-center">
                    <h4><i class="fas fa-clock me-2"></i>Working Hours</h4>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>Monday - Friday</th>
                                <td>8:00 AM - 5:00 PM</td>
                            </tr>
                            <tr>
                                <th>Saturday</th>
                                <td>9:00 AM - 1:00 PM</td>
                            </tr>
                            <tr>
                                <th>Sunday</th>
                                <td>Closed</td>
                            </tr>
                        </tbody>
                    </table>
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