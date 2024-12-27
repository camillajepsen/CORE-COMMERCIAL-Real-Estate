<?php

// SIGN UP FUNCTIONS

// checking if user is already registered
function checkIfEmailExists($pdo, $email) {
    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $statement->execute(['email' => htmlspecialchars($email)]);
    return $statement->rowCount() > 0;
}

// if not already registered, create new user 
function registerUser($pdo, $name, $email, $password, $phone, $user_type) {
    $statement = $pdo->prepare("INSERT INTO users (name, email, password, phone, type) 
                                VALUES (:name, :email, :password, :phone, :user_type)");
    $statement->execute([
        'name' => htmlspecialchars($name),
        'email' => htmlspecialchars($email),
        'password' => htmlspecialchars($password), 
        'phone' => htmlspecialchars($phone),
        'user_type' => htmlspecialchars($user_type)
    ]);
}

function validateUserType($user_type) {
    return in_array($user_type, ['customer', 'owner', 'admin']);
}


// LOGIN FUNCTIONS
function authenticateUser($pdo, $email, $password) {
    // get the user by email
    $statement = $pdo->prepare("SELECT * FROM users WHERE LOWER(TRIM(email)) = LOWER(TRIM(:email))");
    $statement->execute(['email' => htmlspecialchars($email)]);

    if ($statement->rowCount() > 0) {
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // password check
        if ($password === $user['password']) {
            return $user;
        }
    }
    return false;
}

function loginUser($user) {
    // keep user information in the session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = htmlspecialchars($user['name']);
    $_SESSION['user_type'] = htmlspecialchars($user['type']);

    // redirect based on user type
    if ($user['type'] == 'admin') {
        header('Location: admin/dashboard.php');
        exit();
    } elseif ($user['type'] == 'owner') {
        header('Location: owner/dashboard.php');
        exit();
    } else {
        header('Location: customer/properties.php');
        exit();
    }
}


// get function for user data
function getUserById($pdo, $userId) {
    $statement = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
    $statement->execute(['user_id' => htmlspecialchars($userId)]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

// function to update the user account details
function updateUserAccount($pdo, $userId, $name, $email, $password, $phone) {
    $updateFields = [];
    $updateData = ['user_id' => htmlspecialchars($userId)];
    // checking which fields the user has modified
    if (!empty($name)) {
        $updateFields[] = 'name = :name';
        $updateData['name'] = htmlspecialchars($name);
    }

    if (!empty($email)) {
        $updateFields[] = 'email = :email';
        $updateData['email'] = htmlspecialchars($email);
    }

    if (!empty($password)) {
        $updateFields[] = 'password = :password'; 
        $updateData['password'] = htmlspecialchars($password);
    }

    if (!empty($phone)) {
        $updateFields[] = 'phone = :phone';
        $updateData['phone'] = htmlspecialchars($phone);
    }

    if (!empty($updateFields)) {
        // updating info
        $sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE id = :user_id";
        $statement = $pdo->prepare($sql);
        return $statement->execute($updateData);
    }

    return false;
}


// FUNCTIONS FOR MY ACCOUNT

// handling form submission
if (isset($_POST['update_account'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = !empty($_POST['password']) ? $_POST['password'] : ''; 
    $phone = trim($_POST['phone']);
    $currentPassword = $_POST['current_password'];

    // Get the current user data to verify the current password
    $userData = getUserById($pdo, $_SESSION['user_id']);

    // see if the current password matches the one in the database
    if (!empty($password) && $currentPassword !== $userData['password']) {
        $message = htmlspecialchars("Current password is incorrect.");
    } else {
        //  update the account if the current password is correct or password change isn't requested
        if (updateUserAccount($pdo, $_SESSION['user_id'], $name, $email, $password, $phone)) {
            $_SESSION['name'] = htmlspecialchars($name); 
            $message = htmlspecialchars("Account was updated successfully.");
        } else {
            $message = htmlspecialchars("Failed to update account.");
        }
    }
}


// FUNCTIONS FOR RESETTING PASSWORD

function getUserByEmail($pdo, $email) {
    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $statement->execute(['email' => htmlspecialchars($email)]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

function updatePassword($pdo, $userId, $newPassword) {
    $statement = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
    return $statement->execute(['password' => htmlspecialchars($newPassword), 'id' => htmlspecialchars($userId)]);
}

function validatePassword($password, $confirmPassword) {
    return $password === $confirmPassword;
}



// function to send reset mail
function sendPasswordResetLink($pdo, $email) {
    // see if the email exists in the database
    if (checkIfEmailExists($pdo, $email)) {
        // sample reset link 
        $reset_link = "https://corecommercialrealestate.com/new-password?email=" . urlencode($email);

        //  the email content
        $subject = "Password Reset Request";
        $email_content = "You requested to reset your password. Click the link below to reset your password:\n\n" 
            . htmlspecialchars($reset_link) 
            . "\n\nIf this wasn't you, please ignore this message.";

        return "Reset link successfully generated for " . htmlspecialchars($email) . ".\n\n";
    } else {
        // if the email is not registered
        return "The email you entered is not registered with us.";
    }
}

