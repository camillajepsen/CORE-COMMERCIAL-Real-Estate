<?php

// FUNCTIONS TO VIEW PROPERTIES FOR USERS AND NON LOGGED IN PEOPLE 

// to get all countries
function getCountries($pdo)
{
    $statement = $pdo->query("SELECT id, name FROM countries");
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// to get all states
function getStates($pdo)
{
    $statement = $pdo->query("SELECT id, name FROM states");
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// to get all cities
function getCities($pdo)
{
    $statement = $pdo->query("SELECT id, name FROM cities");
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// to get all propertytypes
function getPropertyTypes($pdo)
{
    $statement = $pdo->query("SELECT id, type FROM property_types");
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

// to add a property
function addProperty($pdo, $data)
{
    $statement = $pdo->prepare("INSERT INTO properties (owner_id, type_id, address, price, status, city, state, country, image) 
                           VALUES (:owner_id, :type_id, :address, :price, :status, :city, :state, :country, :image)");

    return $statement->execute([
        'owner_id' => htmlspecialchars($data['owner_id']),
        'type_id' => htmlspecialchars($data['type_id']),
        'address' => htmlspecialchars($data['address']),
        'price' => htmlspecialchars($data['price']),
        'status' => htmlspecialchars($data['status']),
        'city' => htmlspecialchars($data['city']),
        'state' => htmlspecialchars($data['state']),
        'country' => htmlspecialchars($data['country']),
        'image' => htmlspecialchars($data['image'])
    ]);
}


//ADMIN MANAGING PLACES:

// CITIES

// adding a new city
if (isset($_POST['add_city'])) {
    $cityName = trim($_POST['city_name']);
    $stateId = $_POST['state_id'];

    if (!empty($cityName) && !empty($stateId)) {
        $statement = $pdo->prepare("INSERT INTO cities (name, state_id) VALUES (:city_name, :state_id)");
        $statement->execute(['city_name' => htmlspecialchars($cityName), 'state_id' => htmlspecialchars($stateId)]);
        header("Location: manage-cities.php");
        exit();
    }
}

// updating a city name
if (isset($_POST['update_city'])) {
    $cityId = $_POST['city_id'];
    $newCityName = trim($_POST['new_city_name']);
    if (!empty($cityId) && !empty($newCityName)) {
        $statement = $pdo->prepare("UPDATE cities SET name = :new_city_name WHERE id = :city_id");
        $statement->execute(['new_city_name' => htmlspecialchars($newCityName), 'city_id' => htmlspecialchars($cityId)]);
        header("Location: manage-cities.php");
        exit();
    }
}

// deleting a city
if (isset($_POST['delete_city'])) {
    $cityId = $_POST['city_id'];
    $statement = $pdo->prepare("DELETE FROM cities WHERE id = :city_id");
    $statement->execute(['city_id' => htmlspecialchars($cityId)]);
    header("Location: manage-cities.php");
    exit();
}

// getting all cities
$statement = $pdo->query("SELECT * FROM cities");
$cities = $statement->fetchAll(PDO::FETCH_ASSOC);


// STATES

// adding a new state
if (isset($_POST['add_state'])) {
    $stateName = trim($_POST['state_name']);
    $countryId = $_POST['country_id'];

    if (!empty($stateName) && !empty($countryId)) {
        $statement = $pdo->prepare("INSERT INTO states (name, country_id) VALUES (:state_name, :country_id)");
        $statement->execute(['state_name' => htmlspecialchars($stateName), 'country_id' => htmlspecialchars($countryId)]);
        header("Location: manage-states.php");
        exit();
    }
}

// updating a state name
if (isset($_POST['update_state'])) {
    $stateId = $_POST['state_id'];
    $newStateName = trim($_POST['new_state_name']);
    if (!empty($stateId) && !empty($newStateName)) {
        $statement = $pdo->prepare("UPDATE states SET name = :new_state_name WHERE id = :state_id");
        $statement->execute(['new_state_name' => htmlspecialchars($newStateName), 'state_id' => htmlspecialchars($stateId)]);
        header("Location: manage-states.php");
        exit();
    }
}

// deleting a state
if (isset($_POST['delete_state'])) {
    $stateId = $_POST['state_id'];
    $statement = $pdo->prepare("DELETE FROM states WHERE id = :state_id");
    $statement->execute(['state_id' => htmlspecialchars($stateId)]);
    header("Location: manage-states.php");
    exit();
}

// all the states
$statement = $pdo->query("SELECT * FROM states");
$states = $statement->fetchAll(PDO::FETCH_ASSOC);


// COUNTRIES

// adding a new country
if (isset($_POST['add_country'])) {
    $countryName = trim($_POST['country_name']);

    if (!empty($countryName)) {
        $statement = $pdo->prepare("INSERT INTO countries (name) VALUES (:country_name)");
        $statement->execute(['country_name' => htmlspecialchars($countryName)]);
        header("Location: manage-countries.php");
        exit();
    }
}

// updating a country name
if (isset($_POST['update_country'])) {
    $countryId = $_POST['country_id'];
    $newCountryName = trim($_POST['new_country_name']);

    if (!empty($countryId) && !empty($newCountryName)) {
        $statement = $pdo->prepare("UPDATE countries SET name = :new_country_name WHERE id = :country_id");
        $statement->execute(['new_country_name' => htmlspecialchars($newCountryName), 'country_id' => htmlspecialchars($countryId)]);
        header("Location: manage-countries.php");
        exit();
    }
}

// deleting a country
if (isset($_POST['delete_country'])) {
    $countryId = $_POST['country_id'];
    $statement = $pdo->prepare("DELETE FROM countries WHERE id = :country_id");
    $statement->execute(['country_id' => htmlspecialchars($countryId)]);
    header("Location: manage-countries.php");
    exit();
}

