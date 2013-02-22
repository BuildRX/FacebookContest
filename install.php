<?php
require('config.php');
$errors = array();

if(FACEBOOK_APP_ID == '') $errors[] = 'Facebook Application ID Missing.';
if(FACEBOOK_SECRET_KEY == '') $errors[] = 'Facebook Secret Key Missing.';
if(BASE_URL == '') $errors[] = 'Base URL Missing.';
if(DATABASE_HOST == '') $errors[] = 'Database Host Missing.';
if(DATABASE_USERNAME == '') $errors[] = 'Database Username Missing.';
if(DATABASE_PASSWORD == '') $errors[] = 'Database Password Missing.';
if(DATABASE_NAME == '') $errors[] = 'Database Name Missing.';
if(PRELIKE_BLOCK == '') $errors[] = 'Pre Like Block File Name Missing.';
if(ENTRYFORM_BLOCK == '') $errors[] = 'Entry Form Block File Name Missing.';
if(POSTLIKE_BLOCK == '') $errors[] = 'Post Like Block File Name Missing.';
if(mysql_real_escape_string(DATABASE_PREFIX) != DATABASE_PREFIX) $errors[] = 'Invalid Database Prefix';
processErrors($errors);

$testAuthUrl = 'https://graph.facebook.com/oauth/access_token?client_id='.FACEBOOK_APP_ID.'&client_secret='.FACEBOOK_SECRET_KEY.'&grant_type=client_credentials';
$potentialAuthError = json_decode($string,true);
if(json_last_error() == JSON_ERROR_NONE) {
	$errors[] = 'Facebook: '.$potentialAuthError['error']['message'];
}

$mysqli = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
if (mysqli_connect_errno()) {
	$errors[] = 'Database Connection Error: '.mysqli_connect_error();
}
processErrors();

$check_table_result = $mysqli->query('SHOW TABLES LIKE '.DATABASE_PREFIX.'entries');
if($check_table_result->num_rows == 1) {
	$errors[] = 'The script has already been installed.  Please delete install.php.'
}
processErrors();

$installSql = 
'CREATE TABLE IF NOT EXISTS `'.DATABASE_PREFIX.'entries` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL,
  `subscribe` tinyint(1) NOT NULL,
  `timestamp` bigint(20) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';
$mysqli->query($installSql);

echo '<h1>Success</h1>';
echo '<h3>Please delete install.php.</h3>';
mysqli_close($mysqli);


function processErrors($errors) {
	if(sizeof($errors) > 0) {
		echo '<h1>Error Encountered</h1>';
		echo '<h3>Please fix the following errors then re-run this script.</h3>';
		echo '<ul>';
		for($i=0;$i<sizeof($errors);$i++) {
			echo '<li>'.$errors[$i].'</li>';
		}
		echo '</ul>';
		die();
	}
}
?>