<?php
session_start();
include '../includes/db.php';

// check if user is owner 
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'owner') {
    header('Location: ../login.php');
    exit();
}

// get the properties belonging to the logged-in owner to show on dashboard
$owner_id = $_SESSION['user_id'];
$statement = $pdo->prepare("SELECT * FROM properties WHERE owner_id = :owner_id");
$statement->execute(['owner_id' => $owner_id]);
$properties = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>My Properties</title>
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

        <!--Owner menu-->
        <main class="admin-overview">
            <div class="admin-menu">
                <div class="owner-dash-topbox"></div>

                <div class="admin-button" style="background-color: #d3eff6;">
                    <a href="dashboard.php" style="color: black">My Properties</a>
                </div>

                <div class="admin-button">
                    <a href="add-property.php">Add Property</a>
                </div>

                <div class="admin-button">
                    <a href="my-enquiries.php">My Enquiries</a>
                </div>
            </div>

            <div class="admin-stats">
                <div class="owner-title">

                </div>
                <div class="admin-main-content">
                    <!-- Owner property list -->
                    <h1>Your Listed Properties</h1>

                    <div class="properties-container">
                        <?php
                        if (!empty($properties)) {
                            foreach ($properties as $property) {
                                ?>
                                <div class="property-card">
                                    <a href="property-details.php?id=<?php echo htmlspecialchars($property['id']); ?>"
                                        class="property-link">
                                        <div class="image">
                                            <img src="../<?php echo htmlspecialchars($property['image']); ?>"
                                                title="<?php echo htmlspecialchars($property['address']); ?>"
                                                alt="<?php echo htmlspecialchars($property['address']); ?>">
                                        </div>
                                        <div class="extra">
                                            <?php echo htmlspecialchars($property['address']) . ' - AUD ' . number_format($property['price'], 2) . ' - ' . htmlspecialchars($property['status']); ?>
                                        </div>
                                    </a>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p>You have not listed any properties yet.</p>";
                        }
                        ?>
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