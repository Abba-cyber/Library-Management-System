<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .main-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: none;
            padding: 2.5rem;
        }
        .nav-btn {
            border-radius: 12px;
            padding: 1.25rem 1rem;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .nav-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="main-card text-center">
                <div class="mb-4">
                    <span class="fs-1">📚</span>
                    <h2 class="fw-bold mt-2 text-dark">Library Management System</h2>
                    <p class="text-muted">Welcome to the Library Management System portal.</p>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-sm-6">
                        <a href="books.php" class="btn btn-dark w-100 nav-btn fs-6">
                            <i class="bi bi-journal-bookmark-fill fs-5"></i> Manage Books
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="members.php" class="btn btn-dark w-100 nav-btn fs-6">
                            <i class="bi bi-people-fill fs-5"></i> Manage Members
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="borrow.php" class="btn btn-dark w-100 nav-btn fs-6">
                            <i class="bi bi-book-half fs-5"></i> Borrow a Book
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="return.php" class="btn btn-dark w-100 nav-btn fs-6">
                            <i class="bi bi-arrow-return-left fs-5"></i> Return a Book
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-4">
    <span class="text-muted">Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong></span>
    <a href="logout.php" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>