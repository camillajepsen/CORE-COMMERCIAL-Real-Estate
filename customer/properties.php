<?php 
require_once('../includes/view-functions.php');


session_start();

// getting dropdown options and properties data
$dropdownData = getDropdownData($pdo);
$properties = getProperties($pdo, array_map('htmlspecialchars', $_GET));

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Properties</title>
</head>
<body class="properties">
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
                        <li><a href="properties.php">Properties</a></li>
                        <li><a href="../contact.php">Contact Us</a></li>

                        <!-- admin nav links -->
                        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'): ?>
                            <li><a href="../admin/dashboard.php">My Dashboard</a></li>

                        <!-- owner nav links -->
                        <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'owner'): ?>
                            <li><a href="../owner/dashboard.php">My Dashboard</a></li>
                            
                        <!-- customer nav links -->
                        <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'customer'): ?>
                            <li><a href="review.php">Review Us</a></li>
                        <?php endif; ?>
                    </div>

                    <div class="right-hand-links">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="../my_account.php">My Account</a></li>
                            <li><a href="../logout.php">Logout</a></li>
                        <?php else: ?>
                            <li><a href="../login.php" class="accesslink">LOGIN</a></li>
                            <li><a href="../signup-customer-admin.php" class="accesslink">REGISTER</a></li>
                        <?php endif; ?>
                    </div>
                </ul>
            </nav>
        </header> 
    <main class="properties-container">  
        <div class="properties-inner-container">
            <!-- Property search options -->
            <h1>Search Properties</h1>
            <br>
            <form method="get" action="properties.php" class="properties-form">
                <label>City:</label>
                <select name="city">
                    <option value="">Select a city</option>
                    <?php
                    foreach ($dropdownData['cities'] as $city) {
                        echo '<option value="' . htmlspecialchars($city['city']) . '"';
                        if (isset($_GET['city']) && $city['city'] == htmlspecialchars($_GET['city']))
                            echo ' selected ';
                        echo '>' . htmlspecialchars($city['city']) . '</option>';
                    }
                    ?>
                </select>
    
                <label>Status:</label>
                <select name="status">
                    <option value="">Select status</option>
                    <?php
                    foreach ($dropdownData['statuses'] as $status) {
                        echo '<option value="' . htmlspecialchars($status['status']) . '"';
                        if (isset($_GET['status']) && $status['status'] == htmlspecialchars($_GET['status']))
                            echo ' selected ';
                        echo '>' . htmlspecialchars($status['status']) . '</option>';
                    }
                    ?>
                </select>
    
                <label>Property Type:</label>
                <select name="type_id">
                    <option value="">Select a type</option>
                    <?php
                    foreach ($dropdownData['types'] as $type) {
                        echo '<option value="' . htmlspecialchars($type['id']) . '"';
                        if (isset($_GET['type_id']) && $type['id'] == htmlspecialchars($_GET['type_id']))
                            echo ' selected ';
                        echo '>' . htmlspecialchars($type['type']) . '</option>';
                    }
                    ?>
                </select>
    
                <input type="submit" value="SEARCH" class="property-search-button">
            </form>
            <br>
            <hr>
        </div>
    
        <!-- Property grid -->
        <div class="ui-segment">
            <div class="properties-container">
                <?php
                if (!empty($properties)) {
                    foreach ($properties as $property) {
                        ?>
                        <!-- Property card -->
                        <div class="property-card">
                            <a href="property-details.php?id=<?php echo htmlspecialchars($property['id']); ?>" class="property-link">
                                <div class="image">
                                    <img src="../<?php echo htmlspecialchars($property['image']); ?>" title="<?php echo htmlspecialchars($property['address']); ?>"
                                        alt="<?php echo htmlspecialchars($property['address']); ?>">
                                </div>
                                <div class="extra">
                                    <?php echo htmlspecialchars($property['address']) . ' - AUD$ ' . number_format($property['price'], 2) . ' - ' . htmlspecialchars($property['status']); ?>
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
    </main>
    
    <footer>
        <p>Core Commercial Real Estate</p>
    </footer>

    </body>
    
    </html>
