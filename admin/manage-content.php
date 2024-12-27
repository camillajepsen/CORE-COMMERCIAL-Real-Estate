<?php
session_start();
include '../includes/db.php';
include '../includes/content-functions.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// defaulting to 'about'
$page = 'about'; 
$updateMessage = ''; 

if (isset($_POST['page'])) {
    $page = $_POST['page'];
}

$content = getContentByPage($pdo, $page);

// update content if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content']) && isset($_POST['update'])) {
    $newContent = $_POST['content'];
    updateContent($pdo, $page, $newContent);
    $updateMessage = "Content for '$page' page updated successfully";
    // get updated content
    $content = getContentByPage($pdo, $page); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Manage Content</title>
</head>

<body>

    <div class="admin-dashboard">
        <header class="navbar">
            <nav class="nav-bar-container">
                <div class="nav-left">
                    <h1><a href="../index.php"><b>CORE COMMERCIAL</b> • Real Estate</a></h1>
                </div>
                <div class="nav-right">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span id="greeting">Hello, <?php echo htmlspecialchars($_SESSION['name']); ?>!</span>
                    <?php endif; ?>
                </div>
            </nav>

            <nav class="nav-links">
                <ul>
                    <div class="centered-links">
                    <!-- shared nav links -->
                        <li><a href="../about.php">About Us</a></li>
                        <li><a href="../customer/properties.php">Properties</a></li>
                        <li><a href="../contact.php">Contact Us</a></li>
                        <li><a href="dashboard.php">My Dashboard</a></li>
                    </div>

                    <div class="right-hand-links">
                            <li><a href="../my_account.php">My Account</a></li>
                            <li><a href="../logout.php">Logout</a></li>
                    </div>
                </ul>
            </nav>
        </header>

        <main class="admin-overview">
        <div class="admin-menu">
                <div class="admin-button">
                    <a href="manage-users.php">Manage Users</a>
                </div>
                <div class="admin-button">
                    <a href="manage-agents.php">Manage Agents</a>
                </div>
                <div class="admin-button">
                    <a href="manage-owners.php">Manage Owners</a>
                </div>
                <div class="admin-button">
                    <a href="manage-cities.php">Manage Cities</a>
                </div>
                <div class="admin-button">
                    <a href="manage-states.php">Manage States</a>
                </div>
                <div class="admin-button">
                    <a href="manage-countries.php">Manage Countries</a>
                </div>
                <div class="admin-button" style="background-color: #d3eff6;">
                    <a href="manage-content.php" style="color: black">Manage About/Contact</a>
                </div>
                <div class="admin-button">
                    <a href="manage-reviews.php">Manage Reviews</a>
                </div>
                <div class="admin-button">
                    <a href="manage-property-types.php">Manage Property Types</a>
                </div>
                <div class="admin-button">
                    <a href="view-properties.php">View Properties</a>
                </div>
            </div>

            <div class="admin-stats">
                <div class="admin-title"></div>
                <div class="admin-manage-content">
                    <h1>Edit website content</h1>
                    <?php if ($updateMessage): ?>
                        <p style="color:green;"><?php echo htmlspecialchars($updateMessage); ?></p>
                    <?php endif; ?>
                    <form method="post" action="">
                        <label for="page">Select Page:</label>
                        <select name="page" id="page" onchange="this.form.submit()">
                            <option value="about" <?php if ($page == 'about') echo 'selected'; ?>>About</option>
                            <option value="contact" <?php if ($page == 'contact') echo 'selected'; ?>>Contact</option>
                        </select><br><br>

                        <label for="content">Content:</label><br>
                        <textarea name="content" rows="5" cols="50" class="content-box"><?php echo htmlspecialchars($content); ?></textarea><br><br>

                        <button type="submit" name="update" class="admin-update-button">Update Content</button>
                    </form>
                </div>
            </div>
        </main>

        <footer>
            <p>Core Commercial Real Estate</p>
        </footer>
    </div>
</body>

</html>
