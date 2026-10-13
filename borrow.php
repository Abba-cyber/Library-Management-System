<?php
include 'config/database.php';

$message = "";
$error = "";

// Handle book borrowing submission
if (isset($_POST['borrow_book'])) {
    $book_id = (int)$_POST['book_id'];
    $member_id = (int)$_POST['member_id'];
    $borrow_date = date('Y-m-d');
    $status = 'Borrowed';

    // Check if book has available quantity
    $book_check = mysqli_query($conn, "SELECT quantity FROM books WHERE id = $book_id");
    if ($book_check && mysqli_num_rows($book_check) > 0) {
        $book_data = mysqli_fetch_assoc($book_check);
        if ($book_data['quantity'] > 0) {
            // Insert into borrow_records
            $sql = "INSERT INTO borrow_records (book_id, member_id, borrow_date, status) VALUES ($book_id, $member_id, '$borrow_date', '$status')";
            if (mysqli_query($conn, $sql)) {
                // Decrease book quantity
                mysqli_query($conn, "UPDATE books SET quantity = quantity - 1 WHERE id = $book_id");
                $message = "Book borrowed successfully!";
            } else {
                $error = "Error recording borrow transaction.";
            }
        } else {
            $error = "Sorry, this book is currently out of stock.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow a Book - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .card-custom { background: #fff; border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="py-5">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="index.php" class="text-decoration-none text-muted fw-semibold mb-3 d-inline-block">
                <i class="bi bi-arrow-left"></i> Back to Home
            </a>

            <div class="card card-custom p-4">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Borrow a Book</h2>
                    <p class="text-muted">Assign an available book to a registered library member.</p>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Book</label>
                        <select name="book_id" class="form-select" required>
                            <option value="">-- Choose Available Book --</option>
                            <?php
                            $books = mysqli_query($conn, "SELECT * FROM books WHERE quantity > 0");
                            while ($book = mysqli_fetch_assoc($books)) {
                                echo "<option value='{$book['id']}'>{$book['title']} by {$book['author']} (Qty: {$book['quantity']})</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Select Member</label>
                        <select name="member_id" class="form-select" required>
                            <option value="">-- Choose Member --</option>
                            <?php
                            $members = mysqli_query($conn, "SELECT * FROM members");
                            while ($member = mysqli_fetch_assoc($members)) {
                                echo "<option value='{$member['id']}'>{$member['full_name']} ({$member['email']})</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <button type="submit" name="borrow_book" class="btn btn-dark w-100 fw-semibold py-2 rounded-2">
                        <i class="bi bi-book-half me-1"></i> Confirm Borrow
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>