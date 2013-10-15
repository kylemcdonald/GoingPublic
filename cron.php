<?php
/**
 * @file
 * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
 */

/* Load required lib files. */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);

/* If method is set change API call made. Test is called by default. */
$content = $connection->get('account/verify_credentials');

/* direct_messages/new */
$method = 'direct_messages';
$max_msgs = 10;
$control_char = '~';
$response = (array) $connection->get($method, array('count' => $max_msgs));

print_r($response);

for ($i = 0; $i < count($response); $i++) {
	$arr = get_object_vars($response[$i]);
	$id = $arr['id'];
	$text = $arr['text'];
	
	if(substr($text, 0, 1) == $control_char) {
		// $connection->post('statuses/update', array('status' => $text));
		// $connection->post('direct_messages/destroy', array('id' => $id));		
		echo "tweeting " . $id;
	} else {
		echo "not tweeting " . $id;
	}
}