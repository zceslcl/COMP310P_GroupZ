<?php
require 'connect.php';

$email = "";
$message = "";

// Get the user submitted information
$email = $_POST["inputtedEmail"];

/*// Connect to the database
$connection = connect();*/

//SQL to find the email
$sql = "SELECT first_name, last_name FROM customer WHERE email='$email'";

//Execute query and get the result
$result = mysqli_query($conn, $sql);

//Get the first row of the result as an array
$row = mysqli_fetch_array($result);

//If the user doesn't exist there will be no rows in the $result
if (mysqli_num_rows($result) === 0) {
    $message = "Sorry, there is no record of that email address in our database";
} else {
    $message = "Welcome " . $row['first_name'];
}
mysqli_free_result($result);
mysqli_close($conn);

//Display the message on the webpage
echo $message;
?>

