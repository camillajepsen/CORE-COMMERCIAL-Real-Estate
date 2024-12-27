<?php
session_start();
include '../includes/db.php';
include '../includes/manage-places-functions.php'; 

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// get all cities using the getCities function
$cities = getCities($pdo);

// get all states for the dropdown
$states = getStates($pdo); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Manage Cities</title>
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
                <div class="admin-button" style="background-color: #d3eff6;">
                    <a href="manage-cities.php" style="color: black">Manage Cities</a>
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

                <div class="places-manage-content">
                    <!--  existing cities -->
                    <h2>Existing Cities</h2>
                    <div class="places-list">
                        <?php foreach ($cities as $city): ?>
                            <div class="place-card">
                                <p><?php echo htmlspecialchars($city['name']); ?></p>

                                <div class="button-group"> 
                                    <!-- update form -->
                                    <form method="POST" action="manage-cities.php" class="update-form">
                                        <input type="hidden" name="city_id" value="<?php echo htmlspecialchars($city['id']); ?>">
                                        <input type="text" name="new_city_name" placeholder="New city name" required>
                                        <button type="submit" name="update_city" class="location-button">Update</button>
                                    </form>

                                    <!-- delete form -->
                                    <form method="POST" action="manage-cities.php" class="delete-form">
                                        <input type="hidden" name="city_id" value="<?php echo htmlspecialchars($city['id']); ?>">
                                        <button type="submit" name="delete_city" class="location-button">Delete</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!--  new city  -->
                    <div class="add-place">
                        <h2>Add New City</h2><br>
                        <form method="POST" action="manage-cities.php">
                            <input type="text" name="city_name" placeholder="City name" required>

                            <select name="state_id" required>
                                <option value="">Select State</option>
                                <?php foreach ($states as $state): ?>
                                    <option value="<?php echo htmlspecialchars($state['id']); ?>"><?php echo htmlspecialchars($state['name']); ?></option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" name="add_city" class="location-button">Add City</button>
                        </form>
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
