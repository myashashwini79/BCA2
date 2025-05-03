<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Approve/Reject Logic
if (isset($_GET['action'], $_GET['id'])) {
    $leave_id = intval($_GET['id']);
    $status = ($_GET['action'] === 'approve') ? 'Approved' : 'Rejected';

    $stmt = $conn->prepare("UPDATE leaves SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $leave_id);
    if ($stmt->execute()) {
        header("Location: manage_leaves.php");
        exit();
    }
    $stmt->close();
}

$sql = "SELECT l.*, u.username FROM leaves l JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC";
$leaves = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Leave Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Body and Font Settings */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f7fb;
        margin: 0;
        padding: 0;
        color: #333;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 100vh;
    }

    /* Header Styling */
    header {
        background-color: #fff;
        width: 100%;
        padding: 20px 0;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 10;
    }

    header h1 {
        font-size: 2rem;
        color: #4a4a4a;
        font-weight: 600;
    }

    /* Logout Button */
    .logout-btn {
        background-color: #e63946;
        color: white;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
        margin-top: 10px;
    }

    .logout-btn:hover {
        background-color: #d62828;
    }

    /* Container for Content */
    .container {
        width: 90%;
        max-width: 1200px;
        margin: 40px auto;
        padding: 20px;
    }

    /* Glass Card Style */
    .card {
        background-color: rgba(255, 255, 255, 0.85);
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }

    /* Table Styling */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
        font-size: 1rem;
        color: #333;
    }

    th, td {
        padding: 12px 20px;
        text-align: center;
        font-weight: 500;
    }

    thead {
        background-color: #007bff;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .status {
        padding: 6px 15px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-block;
    }

    .pending {
        background-color: #ffeb3b;
        color: #fff;
    }

    .approved {
        background-color: #4caf50;
        color: #fff;
    }

    .rejected {
        background-color: #f44336;
        color: #fff;
    }

    /* Action Button Styles */
    .action-buttons a {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: bold;
        color: white;
        text-decoration: none;
        transition: transform 0.3s ease;
    }

    .approve {
        background-color: #4caf50;
    }

    .reject {
        background-color: #f44336;
    }

    .approve:hover, .reject:hover {
        transform: scale(1.1);
    }

    /* Footer Navigation Links */
    nav {
        margin-top: 30px;
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    nav a {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }

    nav a:hover {
        background-color: #0056b3;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        header h1 {
            font-size: 1.5rem;
        }

        table {
            font-size: 0.9rem;
        }

        .container {
            width: 100%;
            padding: 10px;
        }
    }

    </style>


</head>
<body>

<header>
    <h1>Admin - Manage Leave Requests</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
</header>

<div class="container">
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $leaves->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $row['from_date'] ?></td>
                    <td><?= $row['to_date'] ?></td>
                    <td><?= htmlspecialchars($row['reason']) ?></td>
                    <td>
                        <span class="status <?= strtolower($row['status']) ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td class="action-buttons">
                        <?php if ($row['status'] === 'Pending'): ?>
                            <a href="?action=approve&id=<?= $row['id'] ?>" class="approve">Approve</a>
                            <a href="?action=reject&id=<?= $row['id'] ?>" class="reject">Reject</a>
                        <?php else: ?>
                            <em>No Action</em>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <nav>
        <a href="admin.php">Dashboard</a>
        <a href="apply_leave.php">Apply Leave</a>
        <a href="manage_users1.php">Manage Users</a>
        <a href="payroll.php">payroll</a>
        <a href="payslip.php">payslip</a>
        <a href="reports.php">View Reports</a>
    </nav>
</div>

</body>
</html>
