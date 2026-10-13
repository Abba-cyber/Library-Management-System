<?php
include 'config/database.php';

$message = "";
$error = "";

// Handle Add Book
if (isset($_POST['add_book'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $quantity = (int)$_POST['quantity'];

    $sql = "INSERT INTO books (title, author, category, quantity) VALUES ('$title', '$author', '$category', $quantity)";
    if (mysqli_query($conn, $sql)) {
        $message = "Book added successfully!";
    } else {
        $error = "Error adding book.";
    }
}

// Handle Delete Book
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM books WHERE id = $delete_id");
    header("Location: books.php");
    exit();
}

// Handle Edit Book Update
if (isset($_POST['update_book'])) {
    $id = (int)$_POST['book_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $quantity = (int)$_POST['quantity'];

    mysqli_query($conn, "UPDATE books SET title='$title', author='$author', category='$category', quantity=$quantity WHERE id=$id");
    header("Location: books.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books - Library System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .card-custom { background: #fff; border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .table thead { background-color: #212529; color: #fff; }
    </style>
</head>
<body class="py-4">

<div class="container">
    <a href="index.php" class="text-decoration-none text-muted fw-semibold mb-3 d-inline-block">
        <i class="bi bi-arrow-left"></i> Back to Home
    </a>

    <h2 class="fw-bold text-center mb-4">Manage Books</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Add Book Form Section -->
    <div class="card card-custom p-4 mb-5">
        <h5 class="fw-bold mb-3">Add New Book</h5>
        <form action="" method="POST" class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Book Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Things Fall Apart" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Author Name</label>
                <input type="text" name="author" class="form-control" placeholder="e.g. Chinua Achebe" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Category</label>
                <input type="text" name="category" class="form-control" placeholder="e.g. Novel" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Quantity</label>
                <input type="number" name="quantity" class="form-control" placeholder="e.g. 10" required>
            </div>
            <div class="col-12">
                <button type="submit" name="add_book" class="btn btn-dark fw-semibold px-4 py-2 rounded-2">
                    <i class="bi bi-plus-circle me-1"></i> Add Book
                </button>
            </div>
        </form>
    </div>

    <!-- Available Books Table -->
    <h4 class="fw-bold mb-3">Available Books</h4>
    <div class="card card-custom overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="py-3 px-4">ID</th>
                        <th scope="col" class="py-3">Title</th>
                        <th scope="col" class="py-3">Author</th>
                        <th scope="col" class="py-3">Category</th>
                        <th scope="col" class="py-3">Quantity</th>
                        <th scope="col" class="py-3 px-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC");
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='px-4 fw-semibold'>{$row['id']}</td>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                            echo "<td><span class='badge bg-light text-dark border'>" . htmlspecialchars($row['category']) . "</span></td>";
                            echo "<td>{$row['quantity']}</td>";
                            echo "<td class='px-4 text-end'>
                                    <button class='btn btn-sm btn-outline-dark me-1' data-bs-toggle='modal' data-bs-target='#editModal{$row['id']}'>
                                        <i class='bi bi-pencil-square'></i> Edit
                                    </button>
                                    <a href='books.php?delete_id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this book?\")'>
                                        <i class='bi bi-trash-fill'></i> Delete
                                    </a>
                                  </td>";
                            echo "</tr>";

                            // Edit Modal for each book record
                            echo "
                            <div class='modal fade' id='editModal{$row['id']}' tabindex='-1'>
                                <div class='modal-dialog'>
                                    <div class='modal-content'>
                                        <form action='' method='POST'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title fw-bold'>Edit Book</h5>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                            </div>
                                            <div class='modal-body text-start'>
                                                <input type='hidden' name='book_id' value='{$row['id']}'>
                                                <div class='mb-3'>
                                                    <label class='form-label fw-semibold'>Book Title</label>
                                                    <input type='text' name='title' class='form-control' value='" . htmlspecialchars($row['title']) . "' required>
                                                </div>
                                                <div class='mb-3'>
                                                    <label class='form-label fw-semibold'>Author Name</label>
                                                    <input type='text' name='author' class='form-control' value='" . htmlspecialchars($row['author']) . "' required>
                                                </div>
                                                <div class='mb-3'>
                                                    <label class='form-label fw-semibold'>Category</label>
                                                    <input type='text' name='category' class='form-control' value='" . htmlspecialchars($row['category']) . "' required>
                                                </div>
                                                <div class='mb-3'>
                                                    <label class='form-label fw-semibold'>Quantity</label>
                                                    <input type='number' name='quantity' class='form-control' value='{$row['quantity']}' required>
                                                </div>
                                            </div>
                                            <div class='modal-footer'>
                                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                                <button type='submit' name='update_book' class='btn btn-dark'>Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-4 text-muted'>No books found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>