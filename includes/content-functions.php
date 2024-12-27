<?php

// function for contact page
function sendContactForm($name, $email, $phone, $message) {
    $to = "k230321@student.kent.edu.au";  
    $subject = "New contact from website";
    $body = "Name: " . htmlspecialchars($name) . "\nEmail: " . htmlspecialchars($email) . "\nPhone: " . htmlspecialchars($phone) . "\nMessage:\n" . htmlspecialchars($message);

    // email headers
    $headers = "From: " . htmlspecialchars($email) . "\r\n";
    $headers = "Reply-To: " . htmlspecialchars($email) . "\r\n";

    // Send email
    if (mail($to, $subject, $body, $headers)) {
        return "Message sent successfully!";
    } else {
        return "Failed to send message.";
    }
}


// handle review approval, rejection, or deletion
function handleReviewAction($pdo, $reviewId, $action) {
    if ($action == 'approve') {
        $statement = $pdo->prepare("UPDATE reviews SET status = 'approved' WHERE id = :id");
        $statement->execute(['id' => htmlspecialchars($reviewId)]);
    } elseif ($action == 'reject') {
        $statement = $pdo->prepare("UPDATE reviews SET status = 'rejected' WHERE id = :id");
        $statement->execute(['id' => htmlspecialchars($reviewId)]);
    } elseif ($action == 'delete') {
        $statement = $pdo->prepare("DELETE FROM reviews WHERE id = :id");
        $statement->execute(['id' => htmlspecialchars($reviewId)]);
    }
}

// getting awaiting reviews
function getAwaitingReviews($pdo) {
    $statement = $pdo->prepare("SELECT r.id, r.review_text, u.name, r.status FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.status = 'awaiting'");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// to fetch approved reviews
function getApprovedReviews($pdo) {
    $statement = $pdo->prepare("SELECT r.id, r.review_text, u.name, r.status FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.status = 'approved'");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}


// functions for editing text on contact and about page

function getContentByPage($pdo, $page) {
    $statement = $pdo->prepare("SELECT content FROM page_content WHERE page = :page");
    $statement->execute(['page' => htmlspecialchars($page)]);
    return $statement->fetchColumn();
}

function updateContent($pdo, $page, $content) {
    $statement = $pdo->prepare("UPDATE page_content SET content = :content WHERE page = :page");
    $statement->execute(['content' => htmlspecialchars($content), 'page' => htmlspecialchars($page)]);
}


// functions for owners responding to enquiries

// get enquiries for a specific owner's properties
function getOwnerEnquiries($pdo, $ownerId) {
    $statement = $pdo->prepare("
        SELECT e.id, e.enquiry_text, e.response, p.address, u.name as customer_name, u.email as customer_email
        FROM enquiries e
        JOIN properties p ON e.property_id = p.id
        JOIN users u ON e.user_id = u.id
        WHERE p.owner_id = :owner_id
    ");
    $statement->execute(['owner_id' => htmlspecialchars($ownerId)]);
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// respond to an enquiry and save the response
function respondToEnquiry($pdo, $enquiryId, $response) {
    $statement = $pdo->prepare("UPDATE enquiries SET response = :response, response_date = NOW() WHERE id = :enquiry_id");
    return $statement->execute(['response' => htmlspecialchars($response), 'enquiry_id' => htmlspecialchars($enquiryId)]);
}
