<?php
	session_start();

	include './Vcode.class.php';
	echo new Vcode('./msyh.ttf');