<?php
session_start();
include '../includes/db.php';
require_once('../includes/view-functions.php');

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'customer') {
    header('Location: ../login.php');
    exit();
}

// get property details
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM properties WHERE id = :id");
    $stmt->execute(['id' => htmlspecialchars($_GET['id'])]);
    $property = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$property) {
        die("Property not found.");
    }
} else {
    die("Invalid property ID.");
}

// handle enquiry submission
$enquirySuccess = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['enquiry_text'])) {
    $enquiryText = htmlspecialchars($_POST['enquiry_text']);
    $userId = htmlspecialchars($_SESSION['user_id']);
    $propertyId = $property['id'];

    // function to store the enquiry in the database
    if (submitEnquiry($pdo, $propertyId, $userId, $enquiryText)) {
        $enquirySuccess = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Property Details</title>
</head>

<body class="properties">
    <!--Header with nav bar-->
    <header class="navbar">
            <nav class="nav-bar-container">
                <div class="nav-left">
                    <h1><b>CORE COMMERCIAL</b> Real Estate</h1>
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
                            
                        <!-- customer nav links -->
                        <li><a href="review.php">Review Us</a></li>
                    </div>

                    <div class="right-hand-links">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="../my_account.php">My Account</a></li>
                            <li><a href="../logout.php">Logout</a></li>
                        <?php else: ?>
                            <li><a href="login.php" class="accesslink">LOGIN</a></li>
                            <li><a href="signup-customer-admin.php" class="accesslink">REGISTER</a></li>
                        <?php endif; ?>
                    </div>
                </ul>
            </nav>
        </header>

    <main class="property-details-container">

            <div class="property-details-img">
                <h2><?php echo htmlspecialchars($property['address']); ?></h2>
                <div class="property-image">
                    <img src="../<?php echo htmlspecialchars($property['image']); ?>" alt="<?php echo htmlspecialchars($property['address']); ?>" style="width: 100%; max-width: 600px; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                </div>
            </div>
            
            <!-- Property specifics view -->
            <div class="property-details-info">
                <p>Price: AUD <?php echo number_format($property['price'], 2); ?></p>
                <p>Status: <?php echo htmlspecialchars($property['status']); ?></p>
                <p>City: <?php echo htmlspecialchars($property['city']); ?></p>
                <p>State: <?php echo htmlspecialchars($property['state']); ?></p>
                <p>Country: <?php echo htmlspecialchars($property['country']); ?></p>
                
                <!-- Enquiry form -->
                <h3>Send an Enquiry</h3>
                <?php if ($enquirySuccess): ?>
                    <p>Your enquiry has been submitted successfully!</p>
                <?php endif; ?>
                <form action="property-details.php?id=<?php echo htmlspecialchars($property['id']); ?>" method="POST">
                    <textarea name="enquiry_text" rows="5" cols="50" placeholder="Type your enquiry here..." required></textarea>
                    <br>
                    <button type="submit" class="details-button">SUBMIT</button>
                </form>
                <a href="properties.php" class="details-button">Back to Properties</a><br>
            </div>

            
            <br>

    </main>
    
    <footer>
        <p>Core Commercial Real Estate</p>
    </footer>
</body>

</html>
