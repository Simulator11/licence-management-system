<?php
// Start the session (optional, in case you want to track user sessions)
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - License Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 450px;
            width: 100%;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background: linear-gradient(45deg, #007bff, #6610f2);
            color: white;
            font-weight: bold;
        }
        .btn-custom:hover {
            background: linear-gradient(45deg, #0056b3, #4a0ea6);
        }
        .title {
            font-size: 2rem;
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card p-4 text-center">
        <h1 class="title">Welcome to License Management System</h1>
        <p class="lead">Please choose an option below to get started:</p>

        <div class="d-grid gap-2">
            <a href="auth/register.php" class="btn btn-custom">Register</a>
            <a href="auth/login.php" class="btn btn-custom">Login</a>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS (Optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
