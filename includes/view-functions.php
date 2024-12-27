<?php

// FUNCTIONS TO VIEW PROPERTIES FOR USERS AND NON LOGGED IN PEOPLE 

require_once('../includes/config.php');

try {
    $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(htmlspecialchars($e->getMessage()));
}

// cities, statuses, and property types for dropdowns
function getDropdownData($pdo)
{
    $data = [];

    $statement = $pdo->query('SELECT DISTINCT city FROM properties');
    $data['cities'] = $statement->fetchAll();

    $statement = $pdo->query('SELECT DISTINCT status FROM properties');
    $data['statuses'] = $statement->fetchAll();

    $statement = $pdo->query('SELECT * FROM property_types');
    $data['types'] = $statement->fetchAll();

    return $data;
}

// get properties based on search filters
function getProperties($pdo, $filters)
{
    $sql = "SELECT * FROM properties WHERE 1=1";

    if (!empty($filters['city'])) {
        $sql .= " AND city = :city";
    }
    if (!empty($filters['status'])) {
        $sql .= " AND status = :status";
    }
    if (!empty($filters['type_id'])) {
        $sql .= " AND type_id = :type_id";
    }

    $statement = $pdo->prepare($sql);

    // search based on user input 
    if (!empty($filters['city'])) {
        $statement->bindValue(':city', htmlspecialchars($filters['city']));
    }
    if (!empty($filters['status'])) {
        $statement->bindValue(':status', htmlspecialchars($filters['status']));
    }
    if (!empty($filters['type_id'])) {
        $statement->bindValue(':type_id', htmlspecialchars($filters['type_id']));
    }

    $statement->execute();
    return $statement->fetchAll();
}

// to get details of a single property
function getPropertyDetails($pdo, $property_id)
{
    $statement = $pdo->prepare('SELECT * FROM properties WHERE id = :id');
    $statement->execute(['id' => htmlspecialchars($property_id)]);
    return $statement->fetch(PDO::FETCH_ASSOC);
}

// function to send an enquiry
function submitEnquiry($pdo, $propertyId, $userId, $enquiryText)
{
    try {
        $statement = $pdo->prepare("INSERT INTO enquiries (property_id, user_id, enquiry_text) VALUES (:property_id, :user_id, :enquiry_text)");
        $statement->execute([
            'property_id' => htmlspecialchars($propertyId),
            'user_id' => htmlspecialchars($userId),
            'enquiry_text' => htmlspecialchars($enquiryText)
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// admin functions to search properties:
function searchPropertiesForAdmin($pdo, $filters)
{
    // Base query to get all properties and their owners
    $sql = "SELECT p.*, u.name AS owner_name, u.phone AS owner_mobile
            FROM properties p
            LEFT JOIN users u ON p.owner_id = u.id
            WHERE u.type = 'owner'";

    $parameters = [];

    // apply filters only if they are provided
    if (!empty($filters['property_id'])) {
        $sql .= " AND p.id = :property_id";
        $parameters[':property_id'] = htmlspecialchars($filters['property_id']);
    }
    if (!empty($filters['owner_name'])) {
        $sql .= " AND u.name LIKE :owner_name";
        $parameters[':owner_name'] = '%' . htmlspecialchars($filters['owner_name']) . '%';
    }
    if (!empty($filters['owner_mobile'])) {
        $sql .= " AND u.phone = :owner_mobile";
        $parameters[':owner_mobile'] = htmlspecialchars($filters['owner_mobile']);
    }

    // statement:
    $statement = $pdo->prepare($sql);
    foreach ($parameters as $key => $value) {
        $statement->bindValue($key, $value);
    }

    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

