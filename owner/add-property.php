<?php
session_start();
include '../includes/db.php';
include '../includes/manage-places-functions.php';

// Checking if the user is an owner
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'owner') {
    header('Location: ../login.php');
    exit();
}

// Get all countries, states, cities, and property types for dropdowns
$countries = getCountries($pdo);
$states = getStates($pdo);
$cities = getCities($pdo);
$propertyTypes = getPropertyTypes($pdo);

// Form submission for adding a property
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // for the file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploadDirectory = '../uploads/';
        $fileName = basename($_FILES['image']['name']);
        $uploadFile = $uploadDirectory . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            // rel path
            $imagePath = 'uploads/' . $fileName;
        } else {
            echo htmlspecialchars("File upload failed.");
            $imagePath = '';
        }
    } else {
        $imagePath = '';
    }

    // Collect the property data
    $propertyData = [
        'owner_id' => $_SESSION['user_id'],
        'type_id' => $_POST['type_id'],
        'address' => $_POST['address'],
        'price' => $_POST['price'],
        'status' => $_POST['status'],
        'city' => $_POST['city'],
        'state' => $_POST['state'],
        'country' => $_POST['country'],
        'image' => $imagePath
    ];

    // Add the property to the database
    if (addProperty($pdo, $propertyData)) {
        echo htmlspecialchars("Property added successfully!");
    } else {
        echo htmlspecialchars("Failed to add property.");
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Add Properties</title>
</head>

<body>

    <div class="admin-dashboard">
        <header class="navbar">
            <nav class="nav-bar-container">
                <div class="nav-left">
                    <h1><a href="../index.php"><b>CORE COMMERCIAL</b> • Real Estate</a></h1>
                </div>
                <!-- Header greeting -->
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

                        <!-- Owner links -->
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
                <div class="owner-dash-topbox"></div>

                <div class="admin-button">
                    <a href="dashboard.php">My Properties</a>
                </div>

                <div class="admin-button" style="background-color: #d3eff6;">
                    <a href="add-property.php" style="color: black">Add Property</a>
                </div>

                <div class="admin-button">
                    <a href="my-enquiries.php">My Enquiries</a>
                </div>
            </div>

            <div class="admin-stats">
                <div class="owner-title">
                </div>
                <div class="admin-main-content">

                    <!-- Add Property Form -->
                    <form action="add-property.php" method="POST" enctype="multipart/form-data"
                        class="add-property-form">
                        <h2>Enter property details</h2><br>
                        <div class="form-group">
                            <label for="type_id">Property Type</label>
                            <select name="type_id" required>
                                <option value="">Select Type</option>
                                <?php foreach ($propertyTypes as $type): ?>
                                    <option value="<?php echo htmlspecialchars($type['id']); ?>">
                                        <?php echo htmlspecialchars($type['type']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="address">House number and Street Address</label>
                            <input type="text" name="address" required>
                        </div>

                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" name="price" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" required>
                                <option value="For Sale">For Sale</option>
                                <option value="For Rent">For Rent</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="country">Country</label>
                            <select name="country" required>
                                <option value="">Select Country</option>
                                <?php foreach ($countries as $country): ?>
                                    <option value="<?php echo htmlspecialchars($country['name']); ?>">
                                        <?php echo htmlspecialchars($country['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="state">State</label>
                            <select name="state" required>
                                <option value="">Select State</option>
                                <?php foreach ($states as $state): ?>
                                    <option value="<?php echo htmlspecialchars($state['name']); ?>">
                                        <?php echo htmlspecialchars($state['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="city">City</label>
                            <select name="city" required>
                                <option value="">Select City</option>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?php echo htmlspecialchars($city['name']); ?>">
                                        <?php echo htmlspecialchars($city['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="image">Upload Image</label>
                            <input type="file" name="image" accept="image/*" required>
                        </div>

                        <p>Can't find your location? Please call 0400 000 000 to request adding a new location.</p>
                        <button type="submit" class="location-button">Add Property</button>
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