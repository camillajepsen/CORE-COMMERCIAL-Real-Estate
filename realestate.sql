-- creating database
CREATE DATABASE IF NOT EXISTS core_commercial;
USE core_commercial;

-- creating users table 
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  
    phone VARCHAR(20),
    type ENUM('customer', 'owner', 'admin') NOT NULL
);

-- creating agents table
CREATE TABLE IF NOT EXISTS agents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20)
);

-- creating property_types table
CREATE TABLE IF NOT EXISTS property_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL
);

-- creating properties table 
CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL,
    agent_id INT,
    type_id INT NOT NULL,
    address VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    status ENUM('For Sale', 'For Rent', 'Sold') NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    country VARCHAR(50) NOT NULL,
    image VARCHAR(255),  
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE, 
    FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE SET NULL,
    FOREIGN KEY (type_id) REFERENCES property_types(id) ON DELETE CASCADE
);

-- creating reviews table 
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, 
    review_text TEXT NOT NULL,
    status ENUM('awaiting', 'approved', 'rejected') NOT NULL DEFAULT 'awaiting',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- creating content for 'about' and 'contact' pages
CREATE TABLE IF NOT EXISTS page_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page ENUM('about', 'contact') NOT NULL,
    content TEXT NOT NULL
);

-- inserting default content for 'about' and 'contact' pages
INSERT INTO page_content (page, content) VALUES ('about', 'CORE COMMERCIAL Real Estate is one of the leading and most independent Real Estate Agencies in the Northern and Western Suburbs. With our head office in South Morang, CORE COMMERCIAL Real Estate can assist you with all your buying, selling, renting and leasing needs. Whether it involves commercial or residential real estate, the CORE COMMERCIAL team is here to provide buyers, sellers, tenants and landlords with a friendly and professional service.
The team at CORE COMMERCIAL prides itself on our enthusiastic, ‘can-do’ attitude, which is why we are recognised as the leading Real Estate Agency in the Northern and Western Suburbs. Our staff are continuously updating themselves with all the current market information by attending educational and motivational services and seminars.
As a true independent real estate, we are not restricted to “in-house only” listings. If it is out there, we will find it! We use every resource available to stay up to date and current.
At CORE COMMERCIAL Real Estate, our company culture is anchored by integrity, professionalism, abundance, personal growth, learning and selling.');
INSERT INTO page_content (page, content) VALUES ('contact', 'Please fill out the form below to contact us.');

-- creating enquiries table
CREATE TABLE IF NOT EXISTS enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    user_id INT NOT NULL,
    enquiry_text TEXT NOT NULL,
    response TEXT,
    response_date DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- creating countries table
CREATE TABLE IF NOT EXISTS countries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- creating states table
CREATE TABLE IF NOT EXISTS states (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    country_id INT NOT NULL,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE
);

-- creating cities table
CREATE TABLE IF NOT EXISTS cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    state_id INT NOT NULL,
    FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE CASCADE
);

-- inserting data for countries, states, and cities
INSERT INTO countries (name) VALUES ('Australia'), ('Denmark');

INSERT INTO states (name, country_id) VALUES ('Victoria', 1), ('New South Wales', 1), ('Syddanmark', 2), ('Midtjylland', 2);

INSERT INTO cities (name, state_id) VALUES ('Melbourne', 1), ('Sydney', 2), ('Aarhus', 3), ('Copenhagen', 4);

-- inserting data for property types 
INSERT INTO property_types (type) VALUES ('House'), ('Apartment');

-- inserting data for users 

-- Admin user
INSERT INTO users (id, name, email, password, phone, type)
VALUES (1, 'Admin Kent', 'admin@corecommercial.com', 'pass1234', '0455666770', 'admin');


-- Owner users 

INSERT INTO users (id, name, email, password, phone, type) VALUES
(10, 'Alissa Kent', 'alissa@owner.com', 'pass1234', '0400000000', 'owner'),
(11, 'Erica Tammy', 'erica@owner.com', 'pass1234', '0411111111', 'owner'),
(12, 'Chris Kent', 'chris@owner.com', 'pass1234', '0400222222', 'owner');


-- inserting data for agents
INSERT INTO agents (id, name, email, phone)
VALUES
(1, 'Zoey Kent', 'zoey@agent.com', '0440123456'),
(2, 'Mell Smith', 'mell@agent.com', '0412345678');

-- inserting data for properties
INSERT INTO properties (owner_id, agent_id, type_id, address, price, status, city, state, country, image)
VALUES
(10, 1, 1, '101 Queen St', 550000.00, 'For Sale', 'Melbourne', 'Victoria', 'Australia', 'uploads/property1.jpg'),
(10, 2, 1, '1 King St', 600000.00, 'For Sale', 'Sydney', 'New South Wales', 'Australia', 'uploads/property2.jpg'),
(10, 1, 2, '44 Oak St', 750000.00, 'For Rent', 'Melbourne', 'Victoria', 'Australia', 'uploads/property3.jpg'),
(10, 2, 2, '778 Kent St', 450000.00, 'For Rent', 'Melbourne', 'Victoria', 'Australia', 'uploads/property4.jpg'),
(11, 1, 1, '6 Flower St', 800000.00, 'For Sale', 'Melbourne', 'Victoria', 'Australia', 'uploads/property5.jpg'),
(11, 2, 2, '25 Sydney St', 620000.00, 'For Sale', 'Sydney', 'New South Wales', 'Australia', 'uploads/property6.jpg'),
(11, 1, 1, '7 Kensington St', 540000.00, 'For Sale', 'Melbourne', 'Victoria', 'Australia', 'uploads/property7.jpg'),
(11, 2, 1, '78 Box Hill St', 500000.00, 'For Sale', 'Sydney', 'New South Wales', 'Australia', 'uploads/property8.jpg'),
(12, 1, 2, '99 Richmond St', 770000.00, 'For Rent', 'Melbourne', 'Victoria', 'Australia', 'uploads/property9.jpg'),
(12, 2, 2, '111 St Kilda St', 900000.00, 'For Rent', 'Melbourne', 'Victoria', 'Australia', 'uploads/property10.jpg');

-- for displaying and testing reviews:
-- inserting  data into users table 
INSERT INTO users (id, name, email, password, phone, type) VALUES
(20, 'Liam Johnson', 'liamjohnson@customer.com', 'pass1234', '0412345678', 'customer'),
(21, 'Olivia Taylor', 'oliviataylor@customer.com', 'pass1234', '0498765432', 'customer'),
(22, 'Ethan Harris', 'ethanharris@customer.com', 'pass1234', '0411122233', 'customer'),
(23, 'Mia Wilson', 'miawilson@customer.com', 'pass1234', '0400456789', 'customer'),
(24, 'Noah Smith', 'noahsmith@customer.com', 'pass1234', '0423456789', 'customer'),
(25, 'Ruby Brown', 'rubybrown@customer.com', 'pass1234', '0412345789', 'customer'),
(26, 'Jack White', 'jackwhite@customer.com', 'pass1234', '0487654321', 'customer'),
(27, 'Zoe Green', 'zoe@customer.com', 'pass1234', '0411112223', 'customer'),
(28, 'Max Evans', 'maxevans@customer.com', 'pass1234', '0499998887', 'customer');


-- sample enquiries:
INSERT INTO enquiries (property_id, user_id, enquiry_text, response, response_date, created_at) VALUES
(1, 20, 'I am very interested in this property. Could I schedule a visit to see it?', NULL, NULL, '2024-10-01 10:30:00'),
(1, 21, 'Is there any chance of negotiatiating the price?', NULL, NULL, '2024-10-01 11:00:00'),
(1, 22, 'Can you provide more details about the surrounding neighboorhood?', NULL, NULL, '2024-10-01 12:15:00'),
(1, 23, 'I would like to know if pets are allowed (cats).', NULL, NULL, '2024-10-01 14:45:00'),
(1, 24, 'Are there any additional costs besides the listing price?', NULL, NULL, '2024-10-01 15:30:00'),
(1, 25, 'Is the property still available? Please let me know how to proceed.', NULL, NULL, '2024-10-01 16:00:00');

-- inserting sample data into reviews table
INSERT INTO reviews (user_id, review_text, status, created_at) VALUES
(20, 'The team was very professional and helped me throughout the entire process.', 'approved', '2023-09-15 09:30:00'),
(21, 'Great experience! Found my new home quick.', 'approved', '2023-09-17 11:00:00'),
(22, 'Highly recommended service! Quick and efficient.', 'approved', '2023-09-19 13:45:00'),
(23, 'The website had some glitches, and the process took longer than expected.', 'rejected', '2023-09-20 14:20:00'),
(24, 'I am  still awaiting more information on my property request.', 'awaiting', '2023-09-21 08:30:00'),
(25, 'The response time could be improved. Waiting for feedback.', 'awaiting', '2023-09-22 10:10:00'),
(26, 'Had some trouble with the site being down.', 'awaiting', '2023-09-23 16:50:00'),
(27, 'Looking forward to looking for my property here.', 'awaiting', '2023-09-24 12:00:00'),
(28, 'The owner was helpful but took a while to respond.', 'awaiting', '2023-09-25 14:40:00');
