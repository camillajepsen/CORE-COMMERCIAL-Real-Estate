<?php
session_start();
include 'includes/db.php';
include 'includes/login-functions.php';

if (isset($_POST['signup'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = trim($_POST['phone']);
    $user_type = trim($_POST['user_type']);

    // checking if phone length is valid
    if (strlen($phone) > 50) {
        die(htmlspecialchars("Phone number is too long. Please enter a valid phone number."));
    }

    // check if the user type is valid
    if (!validateUserType($user_type)) {
        die(htmlspecialchars("Invalid user type."));
    }

    // checking if the email is already registered
    if (checkIfEmailExists($pdo, $email)) {
        die(htmlspecialchars("Email is already registered."));
    }

    // check if the passwords match
    if ($password !== $confirm_password) {
        die(htmlspecialchars("Passwords do not match. Please try again."));
    }

    // register the user
    registerUser($pdo, $name, $email, $password, $phone, $user_type);

    if ($user_type === 'admin') {
        echo htmlspecialchars("Your registration as an admin will be reviewed by another admin.");
    } else {
        echo htmlspecialchars("Signup successful");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Sign Up</title>
</head>

<body>
    <div class="register-page">
        <!--Header with nav bar-->
        <header class="navbar">
            <nav class="nav-bar-container">
                <div class="nav-left">
                    <h1><a href="index.php"><b>CORE COMMERCIAL</b> • Real Estate</a></h1>
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
                        <li><a href="about.php">About Us</a></li>
                        <li><a href="customer/properties.php">Properties</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </div>
                    <!--signed in links-->
                    <div class="right-hand-links">
                        <!--links not signed in-->
                        <li><a href="login.php" class="accesslink">LOGIN</a></li>
                        <li><a href="signup.php" class="accesslink">REGISTER</a></li>
                    </div>
                </ul>
            </nav>
        </header>

        <main class="register-form">
            <form action="signup.php" method="POST">
                <h2 id="formh2">NEW USER</h2><br>
                <input type="text" name="name" placeholder="  NAME" required><br><br>

                <input type="email" name="email" placeholder="  EMAIL" required><br><br>

                <input type="password" name="password" placeholder="  PASSWORD" required><br><br>

                <input type="password" name="confirm_password" placeholder="  CONFIRM PASSWORD" required><br><br>

                <input type="text" name="phone" placeholder="  PHONE NUMBER" required><br><br>

                <select name="user_type" required>
                    <option value="">Select user type</option>
                    <option value="customer">Customer</option>
                    <option value="owner">Owner</option>
                    <option value="admin">Admin</option>
                </select><br><br>

                <button type="submit" name="signup" class="signin-button">Sign Up</button>
            </form>
        </main>

        <footer>
            <p>Core Commercial Real Estate</p>
        </footer>
    </div>
</body>

</html>