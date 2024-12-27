<?php
session_start();
include '../includes/db.php';
include '../includes/manage-places-functions.php'; 

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// getting all property types 
$propertyTypes = getPropertyTypes($pdo);

//  add new property type
if (isset($_POST['add_property_type'])) {
    $typeName = htmlspecialchars(trim($_POST['type_name']));
    if (!empty($typeName)) {
        $statement = $pdo->prepare("INSERT INTO property_types (type) VALUES (:type_name)");
        $statement->execute(['type_name' => $typeName]);
        header("Location: manage-property-types.php");
        exit();
    }
}

// updating a property type
if (isset($_POST['update_property_type'])) {
    $typeId = htmlspecialchars($_POST['type_id']);
    $newTypeName = htmlspecialchars(trim($_POST['new_type_name']));
    if (!empty($typeId) && !empty($newTypeName)) {
        $statement = $pdo->prepare("UPDATE property_types SET type = :new_type_name WHERE id = :type_id");
        $statement->execute(['new_type_name' => $newTypeName, 'type_id' => $typeId]);
        header("Location: manage-property-types.php");
        exit();
    }
}

//deleting a property type
if (isset($_POST['delete_property_type'])) {
    $typeId = htmlspecialchars($_POST['type_id']);
    $statement = $pdo->prepare("DELETE FROM property_types WHERE id = :type_id");
    $statement->execute(['type_id' => $typeId]);
    header("Location: manage-property-types.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Manage Property Types</title>
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

            <!--  Admin buttons -->
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
                <div class="admin-button" style="background-color: #d3eff6;">
                    <a href="manage-property-types.php" style="color: black">Manage Property Types</a>
                </div>
                <div class="admin-button">
                    <a href="view-properties.php">View Properties</a>
                </div>
            </div>

            <div class="admin-stats">
            <div class="admin-title"></div>

                <div class="places-manage-content">
                    <!--  existing property types -->
                    <h2>Existing Property Types</h2>
                    <div class="places-list">
                        <?php foreach ($propertyTypes as $type): ?>
                            <div class="place-card">
                                <p><?php echo htmlspecialchars($type['type']); ?></p>

                                <div class="button-group"> 
                                    <!-- update form -->
                                    <form method="POST" action="manage-property-types.php" class="update-form">
                                        <input type="hidden" name="type_id" value="<?php echo htmlspecialchars($type['id']); ?>">
                                        <input type="text" name="new_type_name" placeholder="<?php echo htmlspecialchars($type['type']); ?>" required>
                                        <button type="submit" name="update_property_type" class="location-button">Update</button>
                                    </form>

                                    <!-- delete form -->
                                    <form method="POST" action="manage-property-types.php" class="delete-form">
                                        <input type="hidden" name="type_id" value="<?php echo htmlspecialchars($type['id']); ?>">
                                        <button type="submit" name="delete_property_type" class="location-button">Delete</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!--  add new property type  -->
                    <div class="add-place">
                        <h2>Add New Property Type</h2><br>
                        <form method="POST" action="manage-property-types.php">
                            <input type="text" name="type_name" placeholder="Property type name" required>
                            <button type="submit" name="add_property_type" class="location-button">Add Type</button>
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
