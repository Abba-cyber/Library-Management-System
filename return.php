<?php
include 'config/database.php';

// Handle book return action
if (isset($_GET['return_id'])) {
    $record_id = (int)$_GET['return_id'];
    $return_date = date('Y-m-d');

    // Get the book_id associated with this record before updating
    $res = mysqli_query($conn, "SELECT book_id FROM borrow_records WHERE id = $record_id");
    if ($res && mysqli_num_rows($res) > 0) {
        $data = mysqli_fetch_assoc($res);
        $book_id = $data['book_id'];

        // Update record status
        mysqli_query($conn, "UPDATE borrow_records SET return_date = '$return_date', status = 'Returned' WHERE id = $record_id");
        
        // Increase book quantity back
        mysqli_query($conn, "UPDATE books SET quantity = quantity + 1 WHERE id = $book_id");
        
        header("Location: return.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowing Records - Library System</title>
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

    <h2 class="fw-bold text-center mb-4">Borrowing Records</h2>

    <div class="card card-custom overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="py-3 px-4">ID</th>
                        <th scope="col" class="py-3">Book Title</th>
                        <th scope="col" class="py-3">Member Name</th>
                        <th scope="col" class="py-3">Borrow Date</th>
                        <th scope="col" class="py-3">Return Date</th>
                        <th scope="col" class="py-3">Status</th>
                        <th scope="col" class="py-3 px-4 text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT borrow_records.*, books.title as book_title, members.full_name as member_name 
                              FROM borrow_records 
                              JOIN books ON borrow_records.book_id = books.id 
                              JOIN members ON borrow_records.member_id = members.id 
                              ORDER BY borrow_records.id DESC";
                    
                    $result = mysqli_query($conn, $query);
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='px-4 fw-semibold'>{$row['id']}</td>";
                            echo "<td>" . htmlspecialchars($row['book_title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['member_name']) . "</td>";
                            echo "<td>{$row['borrow_date']}</td>";
                            echo "<td>" . ($row['return_date'] ? $row['return_date'] : '<span class="text-muted">Not yet returned</span>') . "</td>";
                            
                            if ($row['status'] == 'Borrowed') {
                                echo "<td><span class='badge bg-warning text-dark'>Borrowed</span></td>";
                                echo "<td class='px-4 text-end'><a href='return.php?return_id={$row['id']}' class='btn btn-sm btn-dark fw-semibold'>Return Book</a></td>";
                            } else {
                                echo "<td><span class='badge bg-success'>Returned</span></td>";
                                echo "<td class='px-4 text-end'><span class='text-muted small'>Completed</span></td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center py-4 text-muted'>No borrowing records found.</td></tr>";
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
