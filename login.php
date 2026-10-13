<?php
session_start();
include 'config/database.php';

$error = "";

// Auto-seed default user if the users table is empty or account doesn't exist
$check_default = mysqli_query($conn, "SELECT * FROM users WHERE email = 'abbahabdallah1234@gmail.com'");
if ($check_default && mysqli_num_rows($check_default) == 0) {
    $default_pass = password_hash('12345678', PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO users (full_name, email, password) VALUES ('Abbah Abdallah', 'abbahabdallah1234@gmail.com', '$default_pass')");
}

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .card-custom { background: #fff; border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-custom p-4 p-md-5">
                <div class="text-center mb-4">
                    <span class="fs-1">📚</span>
                    <h3 class="fw-bold mt-2">Welcome Back</h3>
                    <p class="text-muted small">Sign in to access the library portal</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Email Address</label>
                        <input type="email" name="email" class="form-control" value="abbahabdallah1234@gmail.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold small">Password</label>
                        <input type="password" name="password" class="form-control" value="12345678" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-dark w-100 fw-semibold py-2 rounded-2 mb-3">
                        Sign In
                    </button>
                    <div class="text-center small text-muted">
                        Don't have an account? <a href="register.php" class="text-dark fw-semibold text-decoration-none">Register here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>