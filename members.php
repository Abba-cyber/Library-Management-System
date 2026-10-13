<?php
include 'config/database.php';

$message = "";
$error = "";

// Handle Add Member
if (isset($_POST['add_member'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $sql = "INSERT INTO members (full_name, email, phone) VALUES ('$full_name', '$email', '$phone')";
    if (mysqli_query($conn, $sql)) {
        $message = "Member added successfully!";
    } else {
        $error = "Error adding member.";
    }
}

// Handle Delete Member
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    mysqli_query($conn, "DELETE FROM members WHERE id = $delete_id");
    header("Location: members.php");
    exit();
}

// Handle Edit Member Update
if (isset($_POST['update_member'])) {
    $id = (int)$_POST['member_id'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    mysqli_query($conn, "UPDATE members SET full_name='$full_name', email='$email', phone='$phone' WHERE id=$id");
    header("Location: members.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members - Library System</title>
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

    <h2 class="fw-bold text-center mb-4">Manage Members</h2>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Add Member Form Section -->
    <div class="card card-custom p-4 mb-5">
        <h5 class="fw-bold mb-3">Add New Member</h5>
        <form action="" method="POST" class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Full Name</label>
                <input type="text" name="full_name" class="form-control" placeholder="e.g. John Doe" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="e.g. john@email.com" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Phone Number</label>
                <input type="text" name="phone" class="form-control" placeholder="e.g. 08156617789" required>
            </div>
            <div class="col-12">
                <button type="submit" name="add_member" class="btn btn-dark fw-semibold px-4 py-2 rounded-2">
                    <i class="bi bi-person-plus-fill me-1"></i> Add Member
                </button>
            </div>
        </form>
    </div>

    <!-- Registered Members Table -->
    <h4 class="fw-bold mb-3">Registered Members</h4>
    <div class="card card-custom overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col" class="py-3 px-4">ID</th>
                        <th scope="col" class="py-3">Full Name</th>
                        <th scope="col" class="py-3">Email Address</th>
                        <th scope="col" class="py-3">Phone Number</th>
                        <th scope="col" class="py-3 px-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($conn, "SELECT * FROM members ORDER BY id DESC");
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='px-4 fw-semibold'>{$row['id']}</td>";
                            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                            echo "<td class='px-4 text-end'>
                                    <button class='btn btn-sm btn-outline-dark me-1' data-bs-toggle='modal' data-bs-target='#editModal{$row['id']}'>
                                        <i class='bi bi-pencil-square'></i> Edit
                                    </button>
                                    <a href='members.php?delete_id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this member?\")'>
                                        <i class='bi bi-trash-fill'></i> Delete
                                    </a>
                                  </td>";
                            echo "</tr>";

                            // Edit Modal for each member
                            echo "
                            <div class='modal fade' id='editModal{$row['id']}' tabindex='-1'>
                                <div class='modal-dialog'>
                                    <div class='modal-content'>
                                        <form action='' method='POST'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title fw-bold'>Edit Member</h5>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                            </div>
                                            <div class='modal-body text-start'>
                                                <input type='hidden' name='member_id' value='{$row['id']}'>
                                                <div class='mb-3'>
                                                    <label class='form-label fw-semibold'>Full Name</label>
                                                    <input type='text' name='full_name' class='form-control' value='" . htmlspecialchars($row['full_name']) . "' required>
                                                </div>
                                                <div class='mb-3'>
                                                    <label class='form-label fw-semibold'>Email Address</label>
                                                    <input type='email' name='email' class='form-control' value='" . htmlspecialchars($row['email']) . "' required>
                                                </div>
                                                <div class='mb-3'>
                                                    <label class='form-label fw-semibold'>Phone Number</label>
                                                    <input type='text' name='phone' class='form-control' value='" . htmlspecialchars($row['phone']) . "' required>
                                                </div>
                                            </div>
                                            <div class='modal-footer'>
                                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                                <button type='submit' name='update_member' class='btn btn-dark'>Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4 text-muted'>No members registered yet.</td></tr>";
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