<!DOCTYPE html>
<head>
    <title>GROUP Z's ZESTY DVD SHOP</title>
    <style>
        body {
            font-family: helvetica, sans-serif;
            margin: 0 auto;
            max-width: 600px;
            background: #777999;
            text-align: center;
        }
        div {
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: white;
            font-size: 60px;
        }
        img {
            margin: 40px 0px 0px 0px;
            border: 7px solid black;
            border-radius: 20px;
        }
        ul {
            padding: 10px;
            background: rgba(0,0,0,0.5);
        }
        li {
            display: inline;
            padding: 0px 10px 0px 10px;
        }
        a {
            color: white;
        }
        p {
            font-size: 15px;
        }
        .main{
            color: orange;
        }

    </style>
</head>

<body>

<div class="logo">
    <img src="http://cdn.shopify.com/s/files/1/0955/6214/products/NeonSignsInUS-free-ship1726-dvd-rental-oval-purple-neon-sign-big_large.jpg?v=1474543438">
</div>

<h1></h1>
<h1></h1>
<h1></h1>

</body>


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
    $message = "Welcome " . ucwords(strtolower($row['first_name'])) . "! You are now successfully logged in.";
}
mysqli_free_result($result);
mysqli_close($conn);

//Display the message on the webpage
echo $message;
?>

