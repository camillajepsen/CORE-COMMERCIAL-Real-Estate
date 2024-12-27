<?php
include 'includes/db.php';
session_start();

// get the 3 most recent reviews
$reviewStatement = $pdo->query("SELECT review_text, created_at, (SELECT name FROM users WHERE users.id = reviews.user_id) AS reviewer_name FROM reviews WHERE status = 'approved' ORDER BY created_at DESC LIMIT 3");
$recentReviews = $reviewStatement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Core Commercial Real Estate</title>
</head>

<body>
    <div class="container">

        <!--Header with nav bar-->
        <header class="navbar">
            <nav class="nav-bar-container">
                <div class="nav-left">
                    <h1><a href="index.php"><b>CORE COMMERCIAL</b> • Real Estate</a></h1>
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
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="customer/properties.php">Properties</a></li>
                        <li><a href="contact.php">Contact Us</a></li>

                        <!-- admin nav links -->
                        <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin'): ?>
                            <li><a href="admin/dashboard.php">My Dashboard</a></li>

                            <!-- owner nav links -->
                        <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'owner'): ?>
                            <li><a href="owner/dashboard.php">My Dashboard</a></li>

                            <!-- customer nav links -->
                        <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'customer'): ?>
                            <li><a href="customer/review.php">Review Us</a></li>
                        <?php endif; ?>
                    </div>

                    <div class="right-hand-links">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="my_account.php">My Account</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        <?php else: ?>
                            <li><a href="login.php" class="accesslink">LOGIN</a></li>
                            <li><a href="signup.php" class="accesslink">REGISTER</a></li>
                        <?php endif; ?>
                    </div>
                </ul>
            </nav>
        </header>


        <!--Background video-->
        <video autoplay muted loop id="index_video">
            <source src="assets/videos/index-video3.mp4" type="video/mp4">
        </video>


        <div class="main-content">
            <div class="reviews">
                <div class="review-header">
                    <h2>What People Say</h2>
                </div>
                <div class="horizontal-review-grid">
                    <?php if (!empty($recentReviews)): ?>
                        <?php foreach ($recentReviews as $review): ?>
                            <div class="review-box">
                                <p><img src="assets/logos/reviewer.png" id="review-logo"></p><br>
                                <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                                <p><em><?php echo htmlspecialchars(date('F j, Y', strtotime($review['created_at']))); ?></em>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No recent reviews found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>


        <footer>
            <p>Core Commercial Real Estate</p>
        </footer>

    </div>

</body>

</html>