<?php
require('config.php');
if(isset($_GET['email'])) {
	$mysqli = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
	$subscribe = '1';
	$mysqli->query('INSERT INTO '.DATABASE_PREFIX.'entries (email,subscribe,timestamp) VALUES("'.$mysqli->real_escape_string($_GET['email']).'","'.$subscribe.'","'.time().'")');
}
?>