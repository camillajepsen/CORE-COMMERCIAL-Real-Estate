<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// delete of a customer
if (isset($_POST['delete_user_id'])) {
    $userId = htmlspecialchars($_POST['delete_user_id']);
    $deleteStatement = $pdo->prepare("DELETE FROM users WHERE id = :user_id AND type = 'customer'");
    $deleteStatement->execute(['user_id' => $userId]);
    header("Location: manage-users.php");
    exit();
}

// get all customers
$customersStatement = $pdo->query("SELECT * FROM users WHERE type = 'customer'");
$customers = $customersStatement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Manage Customers</title>
</head>

<body>

    <div class="admin-dashboard">
        <!--Header with nav bar-->
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

        <!-- Admin menu buttons -->
        <main class="admin-overview">
            <div class="admin-menu">
                <div class="admin-button" style="background-color: #d3eff6;">
                    <a href="manage-users.php" style="color: black">Manage Users</a>
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
                <div class="admin-button">
                    <a href="manage-content.php">Manage About/Contact</a>
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
                <div class="admin-title">
                </div>

                <div class="object-list">
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $customer): ?>
                            <div class="object-card">
                                <img src="../assets/logos/customer.png" alt="Customer Logo" class="object-logo">
                                <h3><?php echo htmlspecialchars($customer['name']); ?></h3>
                                <p>Email: <?php echo htmlspecialchars($customer['email']); ?></p>
                                <p>Phone: <?php echo htmlspecialchars($customer['phone']); ?></p>

                                <form method="POST" action="manage-users.php"
                                    onsubmit="return confirm('Are you sure you want to remove this customer?');">
                                    <input type="hidden" name="delete_user_id"
                                        value="<?php echo htmlspecialchars($customer['id']); ?>">
                                    <button type="submit" class="delete-button">Remove</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No customers found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>

        <footer>
            <p>Core Commercial Real Estate</p>
        </footer>
    </div>
</body>

</html>