<?php
session_start();
require_once "config.php";

// Check if user is Admin
//if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
  //  header("Location: login.php");
    //exit();
//}

function fetchUsers($conn) {
    $sql = "SELECT id, username, role FROM users";
    return $conn->query($sql);
}

$message = "";

// Handle Add User
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!empty($username) && !empty($password)) {
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $message = "<p class='error'>Username already exists.</p>";
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $passwordHash, $role);
            $stmt->execute();
            $stmt->close();
            $_SESSION['message'] = "User added successfully.";
            header("Location: manage_users.php");
            exit();
        }
        $checkStmt->close();
    } else {
        $message = "<p class='error'>All fields are required.</p>";
    }
}

// Handle Delete User
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $id = intval($_POST['delete_user']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "User deleted successfully.";
    } else {
        $_SESSION['message'] = "Failed to delete the user.";
    }
    $stmt->close();
    header("Location: manage_users.php");
    exit();
}

// Fetch all users
$users = fetchUsers($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --accent-color: #f43f5e;
            --bg-light: #f9fafb;
            --text-dark: #1f2937;
            --text-light: #6b7280;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        header {
            background-color: var(--primary-color);
            padding: 20px;
            text-align: center;
            color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        header h1 {
            margin: 0;
            font-size: 28px;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }

        a.back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        a.back-link:hover {
            text-decoration: underline;
        }

        .message {
            background-color: #d1fae5;
            color: #065f46;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .error {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        th, td {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f3f4f6;
        }

        .actions a, .actions button {
            background-color: var(--primary-color);
            color: white;
            padding: 6px 12px;
            margin: 0 5px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .actions button {
            background-color: var(--accent-color);
        }

        .actions a:hover {
            background-color: #4f46e5;
        }

        .actions button:hover {
            background-color: #e11d48;
        }

        form.add-user {
            margin-top: 40px;
            display: grid;
            gap: 20px;
        }

        label {
            font-weight: 600;
        }

        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
        }

        button[type="submit"] {
            background-color: #10b981;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #059669;
        }
    </style>
</head>
<body>

<header>
    <h1>Manage Users</h1>
</header>

<div class="container">

    <a href="reports.php" class="back-link">← Back to Dashboard</a>

    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='message'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']);
    }
    echo $message;
    ?>

    <h2>All Users</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $user['id']; ?></td>
                <td><?= htmlspecialchars($user['username']); ?></td>
                <td><?= htmlspecialchars($user['role']); ?></td>
                <td class="actions">
                    <a href="edit_users.php?id=<?= $user['id']; ?>">Edit</a>
                    <form method="POST" action="manage_users.php" style="display:inline;">
                        <input type="hidden" name="delete_user" value="<?= $user['id']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Add New User</h2>
    <form class="add-user" method="POST" action="manage_users.php">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="Employee">Employee</option>
                <option value="Manager">Manager</option>
                <option value="Admin">Admin</option>
            </select>
        </div>

        <button type="submit" name="add_user">Add User</button>
    </form>

</div>

</body>
</html>
