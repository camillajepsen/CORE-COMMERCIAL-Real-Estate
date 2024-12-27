<?php
session_start();
include '../includes/db.php';
include '../includes/content-functions.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../login.php');
    exit();
}

// handling approval, rejection, or deletion of reviews
if (isset($_POST['action']) && isset($_POST['review_id'])) {
    $reviewId = htmlspecialchars($_POST['review_id']);
    $action = htmlspecialchars($_POST['action']);
    handleReviewAction($pdo, $reviewId, $action);
}

// getting awaiting reviews and approved reviews
$awaitingReviews = getAwaitingReviews($pdo);
$approvedReviews = getApprovedReviews($pdo);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Manage Reviews</title>
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

        <!-- Admin menu buttons -->
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
                <div class="admin-button">
                    <a href="manage-states.php">Manage States</a>
                </div>
                <div class="admin-button">
                    <a href="manage-countries.php">Manage Countries</a>
                </div>
                <div class="admin-button">
                    <a href="manage-content.php">Manage About/Contact</a>
                </div>
                <div class="admin-button" style="background-color: #d3eff6;">
                    <a href="manage-reviews.php" style="color: black">Manage Reviews</a>
                </div>
                <div class="admin-button">
                    <a href="manage-property-types.php">Manage Property Types</a>
                </div>
                <div class="admin-button">
                    <a href="view-properties.php">View Properties</a>
                </div>
            </div>

            <!-- Review overview -->
            <div class="admin-stats">
                <div class="admin-title"></div>
                <div class="object-list">
                    <h2>Manage Reviews</h2>
                    <hr><br>
                    <h3>Awaiting Action</h3>
                    <div class="reviews-grid">
                        <?php foreach ($awaitingReviews as $review): ?>
                            <div class="review-item">
                                <p><strong><?php echo htmlspecialchars($review['name']); ?>:</strong>
                                    <?php echo htmlspecialchars($review['review_text']); ?></p>
                                <form method="POST" class="review-actions">
                                    <input type="hidden" name="review_id"
                                        value="<?php echo htmlspecialchars($review['id']); ?>">
                                    <button type="submit" name="action" value="approve"
                                        class="admin-review-button">Approve</button>
                                    <button type="submit" name="action" value="reject"
                                        class="admin-review-button">Reject</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <h2>Manage Reviews</h2>
                    <hr><br>
                    <h3>Approved Reviews</h3>
                    <div class="reviews-grid">
                        <?php foreach ($approvedReviews as $review): ?>
                            <div class="review-item">
                                <p><strong><?php echo htmlspecialchars($review['name']); ?>:</strong>
                                    <?php echo htmlspecialchars($review['review_text']); ?></p>
                                <form method="POST" class="review-actions">
                                    <input type="hidden" name="review_id"
                                        value="<?php echo htmlspecialchars($review['id']); ?>">
                                    <button type="submit" name="action" value="delete"
                                        class="admin-review-button">Delete</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
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