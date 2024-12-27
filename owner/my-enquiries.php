<?php
session_start();
include '../includes/db.php';
include '../includes/content-functions.php';

// check the user is an owner
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'owner') {
    header('Location: ../login.php');
    exit();
}

// get all owner enquiries
$enquiries = getOwnerEnquiries($pdo, $_SESSION['user_id']);

// enquiry responses
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enquiry_id'])) {
    $response = $_POST['response_text'];
    $enquiryId = $_POST['enquiry_id'];

    if (respondToEnquiry($pdo, $enquiryId, $response)) {
        echo htmlspecialchars("Response emailed successfully!");
    } else {
        echo htmlspecialchars("Failed to send response.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>My Enquiries</title>
</head>

<body>

    <div class="admin-dashboard">
        <header class="navbar">
            <nav class="nav-bar-container">
                <div class="nav-left">
                    <h1><a href="../index.php"><b>CORE COMMERCIAL</b> • Real Estate</a></h1>
                </div>
                <!--Header greeting-->
                <div class="nav-right">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span id="greeting">Hello, <?php echo htmlspecialchars($_SESSION['name']); ?>!</span>
                    <?php endif; ?>
                </div>
            </nav>

            <nav class="nav-links">
                <ul>
                    <!--shared header links-->
                    <div class="centered-links">
                        <li><a href="../about.php">About Us</a></li>
                        <li><a href="../customer/properties.php">Properties</a></li>
                        <li><a href="../contact.php">Contact Us</a></li>

                        <!--owner links-->
                        <li><a href="dashboard.php">My Dashboard</a></li>
                    </div>
                    <!--signed in links-->
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

                <div class="admin-button">
                    <a href="add-property.php">Add Property</a>
                </div>

                <div class="admin-button" style="background-color: #d3eff6;">
                    <a href="my-enquiries.php" style="color: black">My Enquiries</a>
                </div>
            </div>

            <div class="admin-stats">
                <div class="owner-title">
                </div>
                <!-- List of enquiries -->
                <div class="enquiry-list">
                    <?php foreach ($enquiries as $enquiry): ?>
                        <div class="enquiry-card">
                            <h3>Property: <?php echo htmlspecialchars($enquiry['address']); ?></h3>
                            <p><strong>Customer:</strong> <?php echo htmlspecialchars($enquiry['customer_name']); ?>
                                (<?php echo htmlspecialchars($enquiry['customer_email']); ?>)</p>
                            <p><strong>Enquiry:</strong> <?php echo htmlspecialchars($enquiry['enquiry_text']); ?></p>
                            <p><strong>Response:</strong>
                                <?php echo $enquiry['response'] ? htmlspecialchars($enquiry['response']) : 'No response yet'; ?></p>

                            <?php if ($enquiry['response']): ?>
                                <!-- if the owner has responded -->
                                <p>You have responded to this message and your response has been emailed.</p>
                            <?php else: ?>
                                <!-- form to send response if not: -->
                                <form method="POST" action="my-enquiries.php">
                                    <textarea name="response_text" placeholder="Please type your response here..." required></textarea>
                                    <input type="hidden" name="enquiry_id" value="<?php echo htmlspecialchars($enquiry['id']); ?>">
                                    <button type="submit" class="enquiry-button">Send Response</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

        <footer>
            <p>Core Commercial Real Estate</p>
        </footer>
    </div>

</body>

</html>
