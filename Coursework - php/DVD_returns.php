<!DOCTYPE html>
<head>
	<title>DVD RETURNS</title>
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
			font-size: 20px;
		}
		h1 {
			text-align: center;
			color: white;
			font-size: 60px;
		}
		img {
			margin: 5px 0px 0px 0px;
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

	</style>
</head>
<body>

<div class="logo">
	<img src="http://cdn.shopify.com/s/files/1/0955/6214/products/NeonSignsInUS-free-ship1726-dvd-rental-oval-purple-neon-sign-big_large.jpg?v=1474543438">
</div>

<h1>DVD RETURNS</h1>

<div class="menu_list">
	<ul>
		<li><a href="homepage.php">Home Page</a></li>
		<li><a href="DVD_rental.php">DVD Rental</a></li>
	</ul>
</div>

<div class="main">
	<h2>Here you will find details about DVD returns.</h2>
	<p>Lorem ipsum dolor sit amet, id est menandri corrumpit assueverit. Mel tempor praesent te, in iuvaret petentium has. Eam et nemore vivendum omittantur, eu eam tollit recusabo. Sea in posse epicurei, quem autem molestie ut vix, ne sed adhuc altera quaerendum. An nemore detraxit his, ad vel graece graeco necessitatibus. His ei amet justo.</p>
</div>


<form id="dvdTitle">
	<br /><h3>Search a film</h3>
	<input type="text" placeholder="Please enter the DVD title" name="dvdTitle">
	<button onclick="submitDvdTitle();">Submit Here!</button>
</form>


<form id="returnEmail">
	<br /><h3>Enter your email:</h3>
	<input type="email" placeholder="Please enter your email" name="inputtedReturnEmail">
	<button onclick="submitReturnEmail();">Submit Here!</button>
</form>


<form id="dateOfReturn">
	<br /><h3>Please enter the date of return (today's date):</h3>
	<input type="date" name="returnDate" id="returnDate">
	<button onclick="submitDateOfReturn();">Submit Here!</button>
	<h1></h1>
</form>



<script src="myscript.js"></script>

</body>

<?php
require("connect.php");

?>
