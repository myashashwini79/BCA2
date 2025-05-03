<?php
session_start();
require_once "config.php";

//if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Manager') {
  //  header("Location: login.php");
    //exit();
//}

$username = htmlspecialchars($_SESSION['username']);

if (isset($_GET['action'], $_GET['id'])) {
    $leave_id = intval($_GET['id']);
    $status = ($_GET['action'] === 'approve') ? 'Approved' : 'Rejected';

    $stmt = $conn->prepare("UPDATE leaves SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $leave_id);
    if ($stmt->execute()) {
        header("Location: manage_leave.php");
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
    <title>Manager - Manage Leaves</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to right, #e0eafc, #cfdef3);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        color: #333;
    }

    header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        padding: 20px 30px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        box-shadow: 0px 4px 12px rgba(0,0,0,0.2);
    }

    header h1 {
        font-size: 28px;
        font-weight: 700;
    }

    .user-info {
        margin-top: 5px;
        font-size: 15px;
        opacity: 0.9;
    }

    .logout-btn {
        background: #ff4b5c;
        padding: 10px 20px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: background 0.3s ease;
    }

    .logout-btn:hover {
        background: #e8434e;
    }

    .container {
        padding: 40px 20px;
        flex: 1;
        width: 95%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .card {
        background: #ffffff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 16px 20px;
        text-align: left;
        font-size: 15px;
        border-bottom: 1px solid #eee;
    }

    th {
        background: #667eea;
        color: #fff;
        font-weight: 600;
    }

    tr:hover {
        background: #f4f7fb;
        transition: background 0.3s ease;
    }

    .status {
        display: inline-block;
        padding: 6px 18px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        min-width: 100px;
    }

    .pending {
        background: #f1c40f;
        color: #fff;
    }

    .approved {
        background: #2ecc71;
        color: #fff;
    }

    .rejected {
        background: #e74c3c;
        color: #fff;
    }

    .action-buttons a {
        display: inline-block;
        margin: 4px;
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        color: #fff;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .approve {
        background: #2ecc71;
    }

    .approve:hover {
        background: #27ae60;
    }

    .reject {
        background: #e74c3c;
    }

    .reject:hover {
        background: #c0392b;
    }

    nav {
        margin-top: 40px;
        text-align: center;
    }

    nav a {
        margin: 10px 12px;
        padding: 12px 25px;
        border-radius: 30px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        font-size: 16px;
        box-shadow: 0px 4px 12px rgba(0,0,0,0.15);
        transition: background 0.3s ease;
    }

    nav a:hover {
        background: linear-gradient(135deg, #5a67d8, #6b46c1);
    }

    @media (max-width: 768px) {
        header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .action-buttons a {
            display: block;
            margin-bottom: 8px;
        }

        nav a {
            display: block;
            margin: 12px 0;
        }
    }
    </style>
</head>
<body>

<header>
    <div>
        <h1>Manager Panel</h1>
        <div class="user-info">Logged in as: <strong><?= $username ?></strong></div>
    </div>
    <a href="login.php" class="logout-btn">Logout</a>
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
        <a href="apply_leave.php">Apply Leave</a>
        <a href="settings.php">Settings</a>
        <a href="cab.php">Cab Bookings</a>
        <a href="reports.php">View Reports</a>
    </nav>
</div>

</body>
</html>
