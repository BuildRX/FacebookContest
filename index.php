<?php
require('config.php');
$data = parse_signed_request($_REQUEST['signed_request'], FACEBOOK_SECRET_KEY);
$page_data=$data['page'];
$page_contents = '';
if($page_data['liked'] == "1"){
	$page_contents = file_get_contents('skin/'.POSTLIKE_BLOCK);
} else {
	$page_contents = file_get_contents('skin/'.PRELIKE_BLOCK);
}
?>
<html>
	<head>
		<title></title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<style type="text/css">
			body {
				padding:0;
				margin:0;
			}
		</style>
	</head>
	<body>
		<div id="content">
			<?php echo $page_contents ?>
		</div>
		<script type="text/javascript">
			$(document).ready(function () {
				$("#submit").click(function () {
					$.ajax({
						type: "GET",
						data: "email="+$('#email').val(),
						url: "submit.php"
					})
					$.get('skin/<?php echo THANKS_BLOCK; ?>', function(data) {
						$('#content').html(data);
					});
				});
			});
		</script>
	</body>
</html>
<?php
function parse_signed_request($signed_request, $secret) {
	list($encoded_sig, $payload) = explode('.', $signed_request, 2);
	$sig = base64_url_decode($encoded_sig);
	$data = json_decode(base64_url_decode($payload), true);
	if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
		error_log('Unknown algorithm. Expected HMAC-SHA256');
		return null;
	}
	$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
	if ($sig !== $expected_sig) {
		error_log('Bad Signed JSON signature!');
		return null;
	}
	return $data;
}
function base64_url_decode($input) {
	return base64_decode(strtr($input, '-_', '+/'));
}
?>