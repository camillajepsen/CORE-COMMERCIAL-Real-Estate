HOW TO GUIDE

1.	Ensure Apache and MySQL are both running in Xampp.
2.	Open the browser and go to http://localhost/phpmyadmin
3.	Create a new databse called core_commercial. Then select the database and import realestate.sql
4.	Please check the config.php file in the includes and ensure username and password matches yours.
5.	Place the files inside the htdocs folder in Xampp and run the project locally.

Config:

define('DBCONNSTRING', 'mysql:host=localhost;dbname=core_commercial');
define('DBUSER', 'root'); 
define('DBPASS', '');    

Login details for sample users: 
Admin
Username: admin@corecommercial.com
Password: pass123

Owner
Username: alissa@owner.com
Password: pass123

Customer
Username: zoe@customer.com
Password: pass123
