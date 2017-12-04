<!DOCTYPE html>
<head>
	<title>DVD RENTAL</title>
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
		input{
			border: 0;
			padding: 10px;
			font-size: 15px;
			margin: 5px;
		}
		button{
			border: 0;
			font-size: 15px;
			margin: 5px;
		}
		select{
			border: 0;
			padding: 0px;
		}
	</style>
</head>

<body>

<div class="logo">
	<img src="http://cdn.shopify.com/s/files/1/0955/6214/products/NeonSignsInUS-free-ship1726-dvd-rental-oval-purple-neon-sign-big_large.jpg?v=1474543438">
</div>

<h1>DVD RENTAL</h1>

<div class="menu_list">
	<ul>
		<li><a href="homepage.php">Home Page</a></li>
		<li><a href="DVD_returns.php">DVD Returns</a></li>
	</ul>
</div>

<div class="main">
	<h2>This is the page for DVD Rental, feel free to browse.</h2>
	<p>Lorem ipsum dolor sit amet, id est menandri corrumpit assueverit. Mel tempor praesent te, in iuvaret petentium has. Eam et nemore vivendum omittantur, eu eam tollit recusabo. Sea in posse epicurei, quem autem molestie ut vix, ne sed adhuc altera quaerendum. An nemore detraxit his, ad vel graece graeco necessitatibus. His ei amet justo.</p>
</div>

<form action="">

	<select name="Genre[]" size="10" multiple="GenreSelected" id="GenreSelected">
		<option value="Action">Action</option>
		<option value="Animation">Animation</option>
		<option value="Children">Children</option>
		<option value="Classics">Classics</option>
		<option value="Comedy">Comedy</option>
		<option value="Documentary">Documentary</option>
		<option value="Classics">Classics</option>
		<option value="Drama">Drama</option>
		<option value="Family">Family</option>
		<option value="Foreign">Foreign</option>
		<option value="Games">Games</option>
		<option value="Horror">Horror</option>
		<option value="Music">Music</option>
		<option value="New">New</option>
		<option value="Sci-Fi">Sci-Fi</option>
		<option value="Sports">Sports</option>
		<option value="Travel">Travel</option>
	</select>

	<button type='submit' id="submitGenre" onclick="submitClicked();">Submit</button>

	<br><br>

	<div>
		<input name="film_name" type="text" placeholder="Searching the Film Name" id="film_name">
	</div>

	<br><br>

	<button type='submit' id="submit" onclick="submitClicked_film();">Search</button>

	<script>

        function submitClicked(){
            var selected=document.getElementById("GenreSelected");
            var j=0;
            var array=[];

            for (i=0; i<selected.length; i++){
                currentSelected = selected[i];

                if (currentSelected.selected == true){
                    array[j]=currentSelected.value;
                    j++;
                }
            }

            if(array.length==1) {
                result="You chose the genre of " + array[0] + ".";
            }
            else{
                result="You chose the genres of ";

                for (k=0; k<array.length-1; k++){
                    result += array[k]+", ";
                }
            }
            if(array.length>1) {
                result += "and " + array[k] + ".";
            }
            alert(result);
        }

	</script>

	<script>

        function submitClicked_film(){
            alert("You searched for " + document.getElementById("film_name").value);
        }

	</script>

</form>

<ul>
	<li><a href="table.html">Browse All</a></li>

</ul>

</body>

<?php
require("connect.php");

?>