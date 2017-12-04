<!DOCTYPE html>
<head>
	<title>GROUP Z's ZESTY DVD SHOP</title>
	<style>
	body {
		font-family: helvetica, sans-serif;
		margin: 0 auto;
		max-width: 600px;
		background: #777999;
		text-align: left;
		
		
		
		
		padding: 0px;
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
	
	<h1>GROUP Z's ZESTY DVD SHOP</h1>
	
	<div class="menu_list">
		<ul>
		<li><a href="DVD_rental.php">DVD Rental</a></li>
		<li><a href="DVD_returns.php">DVD Returns</a></li>
		</ul>
	</div>

	<div class="main">
		<h2>Hello! Welcome to the DVD shop</h2>
		<p>Lorem ipsum dolor sit amet, id est menandri corrumpit assueverit. Mel tempor praesent te, in iuvaret petentium has. Eam et nemore vivendum omittantur, eu eam tollit recusabo. Sea in posse epicurei, quem autem molestie ut vix, ne sed adhuc altera quaerendum. An nemore detraxit his, ad vel graece graeco necessitatibus. His ei amet justo.</p>
	</div>
	
	<form id="email" method="POST" action="login2.php">
		<br />E-mail:
		<input type="email" placeholder="Please enter your email" name="inputtedEmail">
		<button onclick="">Submit Here!</button>
		<h1></h1>
	</form>

	<script src="myscript.js"></script>

</body>


<?php
require("connect.php");

?>
