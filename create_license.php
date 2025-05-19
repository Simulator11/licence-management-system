<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "license_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nida_no = $_POST['nida_no'];
    $full_name = $_POST['full_name'];
    $years_selected = $_POST['years'];

    $issue_date = date("Y-m-d");
    $expiry_date = date("Y-m-d", strtotime("+$years_selected years"));
    $amount = ($years_selected == 1) ? 50 : 90;

    // File upload
    $upload_dir = 'uploads/';
    $photo_path = "";

    if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === 0) {
        $file_tmp = $_FILES['user_image']['tmp_name'];
        $file_name = basename($_FILES['user_image']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed)) {
            $new_filename = uniqid("img_") . "." . $file_ext;
            $photo_path = $upload_dir . $new_filename;

            if (!move_uploaded_file($file_tmp, $photo_path)) {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Only JPG, PNG, and GIF files are allowed.";
        }
    } else {
        $error = "Image upload is required.";
    }

    if (!isset($error)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE nida_no = ? AND full_name = ?");
        $stmt->bind_param("ss", $nida_no, $full_name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            $check = $conn->prepare("SELECT expiry_date FROM licenses WHERE user_id = ? ORDER BY expiry_date DESC LIMIT 1");
            $check->bind_param("i", $user_id);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $check->bind_result($last_expiry);
                $check->fetch();
                if (strtotime($last_expiry) > strtotime(date("Y-m-d"))) {
                    $error = "Active license found. Expires on $last_expiry.";
                }
            }

            if (!isset($error)) {
                $control_number = "CTRL-" . strtoupper(bin2hex(random_bytes(5)));

                $insert = $conn->prepare("INSERT INTO licenses (user_id, nida_no, full_name, photo_path, control_number, expiry_date, payment_status, license_status) VALUES (?, ?, ?, ?, ?, ?, 'Pending', 'Inactive')");
                $insert->bind_param("isssss", $user_id, $nida_no, $full_name, $photo_path, $control_number, $expiry_date);

                if ($insert->execute()) {
                    $_SESSION['control_number'] = $control_number;
                    $_SESSION['amount'] = $amount;
                    $_SESSION['years_selected'] = $years_selected;
                    header("Location: payment.php");
                    exit();
                } else {
                    $error = "Error inserting license record.";
                }
                $insert->close();
            }
            $check->close();
        } else {
            $error = "No matching user found.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create License</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
<div class="container">
    <div class="card p-4 shadow-sm">
        <h2 class="text-center text-primary mb-4">License Application</h2>

        <?php if (isset($error)) : ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nida_no" class="form-label">NIDA Number</label>
                <input type="text" name="nida_no" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="years" class="form-label">License Duration</label>
                <select name="years" class="form-control" required>
                    <option value="1">1 Year - $50</option>
                    <option value="2">2 Years - $90</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="user_image" class="form-label">Upload Your Photo</label>
                <input type="file" name="user_image" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Proceed to Payment</button>
        </form>
    </div>
</div>
</body>
</html>
