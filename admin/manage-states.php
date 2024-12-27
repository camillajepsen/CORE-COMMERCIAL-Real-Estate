<?php
session_start();
include '../includes/db.php';
include '../includes/manage-places-functions.php'; 

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
// get all countries 
$countries = getCountries($pdo);

// get all states 
$states = getStates($pdo); 

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Manage States</title>
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
                <div class="admin-button" style="background-color: #d3eff6;">
                    <a href="manage-states.php" style="color: black">Manage States</a>
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
                    <!-- Display existing states -->
                    <h2>Existing States</h2>
                    <div class="places-list">
                        <?php foreach ($states as $state): ?>
                            <div class="place-card">
                                <p><?php echo htmlspecialchars($state['name']); ?></p>

                                <div class="button-group"> 
                                    <!-- update form -->
                                    <form method="POST" action="manage-states.php" class="update-form">
                                        <input type="hidden" name="state_id" value="<?php echo htmlspecialchars($state['id']); ?>">
                                        <input type="text" name="new_state_name" placeholder="New state name" required>
                                        <button type="submit" name="update_state" class="location-button">Update</button>
                                    </form>

                                    <!-- delete form -->
                                    <form method="POST" action="manage-states.php" class="delete-form">
                                        <input type="hidden" name="state_id" value="<?php echo htmlspecialchars($state['id']); ?>">
                                        <button type="submit" name="delete_state" class="location-button">Delete</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>


                    <!-- Add new state form -->
                    <div class="add-place">
                        <h2>Add New State</h2><br>
                        <form method="POST" action="manage-states.php">
                            <input type="text" name="state_name" placeholder="State name" required>

                            <select name="country_id" required>
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $country): ?>
                                    <option value="<?php echo htmlspecialchars($country['id']); ?>"><?php echo htmlspecialchars($country['name']); ?></option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" name="add_state" class="location-button">Add State</button>
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
