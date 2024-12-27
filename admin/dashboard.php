<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// get totals for dashboard
$total_property_types = $pdo->query("SELECT COUNT(*) FROM property_types")->fetchColumn();
$total_countries = $pdo->query("SELECT COUNT(*) FROM countries")->fetchColumn();
$total_states = $pdo->query("SELECT COUNT(*) FROM states")->fetchColumn();
$total_cities = $pdo->query("SELECT COUNT(*) FROM cities")->fetchColumn();
$total_agents = $pdo->query("SELECT COUNT(*) FROM agents")->fetchColumn();
$total_owners = $pdo->query("SELECT COUNT(*) FROM users WHERE type = 'owner'")->fetchColumn();
$total_customers = $pdo->query("SELECT COUNT(*) FROM users WHERE type = 'customer'")->fetchColumn();
$total_properties = $pdo->query("SELECT COUNT(*) FROM properties")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Admin Dashboard</title>
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


        <main class="admin-overview">
            <!-- buttons -->
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
                <div class="admin-button">
                    <a href="view-properties.php">View Properties</a>
                </div>
            </div>
            <div class="admin-stats">
                <div class="admin-title">
                    
                </div>
                <!-- statistics -->
                <div class="admin-main-content">
                    <div class="stat-data">
                        <img src="../assets/logos/property.png">
                        <p>Total Properties: <?php echo htmlspecialchars($total_properties); ?></p>
                    </div>

                    <div class="stat-data">
                        <img src="../assets/logos/property-type.png">
                        <p>Total Property Types: <?php echo htmlspecialchars($total_property_types); ?></p>
                    </div>

                    <div class="stat-data">
                        <img src="../assets/logos/agent.png">
                        <p>Total Agents: <?php echo htmlspecialchars($total_agents); ?></p>
                    </div>

                    <div class="stat-data">
                        <img src="../assets/logos/owner.png">
                        <p>Total Owners: <?php echo htmlspecialchars($total_owners); ?></p>
                    </div>

                    <div class="stat-data">
                        <img src="../assets/logos/customer.png">
                        <p>Total Customers: <?php echo htmlspecialchars($total_customers); ?></p>
                    </div>

                    <div class="stat-data">
                        <img src="../assets/logos/country.png">
                        <p>Total Countries: <?php echo htmlspecialchars($total_countries); ?></p>
                    </div>

                    <div class="stat-data">
                        <img src="../assets/logos/state.png">
                        <p>Total States: <?php echo htmlspecialchars($total_states); ?></p>
                    </div>

                    <div class="stat-data">
                        <img src="../assets/logos/city.png">
                        <p>Total Cities: <?php echo htmlspecialchars($total_cities); ?></p>
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
