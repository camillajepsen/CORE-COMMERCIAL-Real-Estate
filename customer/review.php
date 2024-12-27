<?php
session_start();
include '../includes/db.php';
require_once('../includes/view-functions.php');

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'customer') {
    header('Location: ../login.php');
    exit();
}

// handling review submission
$reviewSuccess = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['review_text'])) {
    $reviewText = htmlspecialchars($_POST['review_text']);
    $userId = htmlspecialchars($_SESSION['user_id']);

    // inserting the review into the database with 'awaiting' status
    $statement = $pdo->prepare("INSERT INTO reviews (user_id, review_text, status) VALUES (:user_id, :review_text, 'awaiting')");
    if ($statement->execute(['user_id' => $userId, 'review_text' => $reviewText])) {
        $reviewSuccess = true;
    }
}

// getting approved reviews
$statement = $pdo->prepare("SELECT * FROM reviews WHERE status = 'approved'");
$statement->execute();
$reviews = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Leave a Review</title>
</head>

<body class="register-page">
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
                    <li><a href="../about.php">About Us</a></li>
                    <li><a href="properties.php">Properties</a></li>
                    <li><a href="../contact.php">Contact Us</a></li>
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

    <!-- Review input form -->
    <main class="info-container">
        <div class="inner-info">
            <h2>Leave a Review for Core Commercial Real Estate</h2><br>
            <p>
                At Core Commercial Real Estate we value your opinion.<br>
                We aim to provide the best service possible and aim for 100% customer satisfaction.<br>
                If you have benefitted from our help please let us know in the box below.<br><br>
            </p>

            <?php if ($reviewSuccess): ?>
                <p>Your review has been submitted successfully. Thank you!</p>
            <?php endif; ?>

            <form action="review.php" method="POST">
                <textarea name="review_text" rows="5" cols="50" placeholder=" Share your experience with us..."
                    required></textarea>
                <br><br>
                <button type="submit" class="submit-button">Submit Review</button>
            </form>
        </div>
    </main>

    <footer>
        <p>Core Commercial Real Estate</p>
    </footer>
</body>

</html>