<?php
session_start();
include '../includes/db.php';
include '../includes/view-functions.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// form submission for admin property search
$filters = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filters['property_id'] = isset($_POST['property_id']) ? htmlspecialchars(trim($_POST['property_id'])) : '';
    $filters['owner_name'] = isset($_POST['owner_name']) ? htmlspecialchars(trim($_POST['owner_name'])) : '';
    $filters['owner_mobile'] = isset($_POST['owner_mobile']) ? htmlspecialchars(trim($_POST['owner_mobile'])) : '';
}

// get properties based on the filters for the admin
$properties = searchPropertiesForAdmin($pdo, $filters);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Manage Properties</title>
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

                        <!-- admin nav links -->
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
                <div class="admin-button">
                    <a href="manage-content.php">Manage About/Contact</a>
                </div>
                <div class="admin-button">
                    <a href="manage-reviews.php">Manage Reviews</a>
                </div>
                <div class="admin-button">
                    <a href="manage-property-types.php">Manage Property Types</a>
                </div>
                <div class="admin-button" style="background-color: #d3eff6;">
                    <a href="view-properties.php" style="color: black">View Properties</a>
                </div>
            </div>

            <div class="admin-stats">

                <div class="admin-property">
                    <!-- Section for searching properties -->
                    <div class="properties-container">
                        <div class="properties-inner-container">
                            <h1>Search Properties</h1>
                            <br>
                            <form method="POST" action="view-properties.php" class="properties-form">
                                <label>Property ID:</label>
                                <input type="text" name="property_id"
                                    value="<?php echo isset($_POST['property_id']) ? htmlspecialchars($_POST['property_id']) : ''; ?>">

                                <label>Owner Name:</label>
                                <input type="text" name="owner_name"
                                    value="<?php echo isset($_POST['owner_name']) ? htmlspecialchars($_POST['owner_name']) : ''; ?>">

                                <label>Owner Mobile:</label>
                                <input type="text" name="owner_mobile"
                                    value="<?php echo isset($_POST['owner_mobile']) ? htmlspecialchars($_POST['owner_mobile']) : ''; ?>"><br>

                                <br><input type="submit" value="SEARCH" class="property-search-button">
                            </form>
                            <br>
                            <hr>
                        </div>

                        <div class="ui-segment">
                            <div class="properties-container">
                                <?php
                                if (!empty($properties)) {
                                    foreach ($properties as $property) {
                                        ?>
                                        <!-- Property card -->
                                        <div class="property-card">
                                            <a href="property-details.php?id=<?php echo htmlspecialchars($property['id']); ?>"
                                                class="property-link">
                                                <div class="image">
                                                    <img src="../<?php echo htmlspecialchars($property['image']); ?>"
                                                        title="<?php echo htmlspecialchars($property['address']); ?>"
                                                        alt="<?php echo htmlspecialchars($property['address']); ?>">
                                                </div>
                                                <div class="extra">
                                                    <?php echo htmlspecialchars($property['address']) . ' - AUD$ ' . number_format($property['price'], 2) . ' - ' . htmlspecialchars($property['status']); ?>
                                                    <br>Owner: <?php echo htmlspecialchars($property['owner_name']); ?>, Mobile:
                                                    <?php echo htmlspecialchars($property['owner_mobile']); ?>
                                                </div>
                                            </a>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    echo "<p>No properties found.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer>
            <p>Core Commercial Real Estate</p>
        </footer>
    </div>
</body>

</html>