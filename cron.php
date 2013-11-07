<style>
* {
	font-family: sans-serif;
}
</style>
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
$response = $connection->get($method, array('count' => $max_msgs));

echo "<pre>";
print_r($response);
echo "</pre>";

echo "<h1>Tweets available: " . count($response) . "</h1>";

foreach ($response as $dm) {
	$id = $dm->id;
	$text = html_entity_decode($dm->text);
	echo "<div>";
	if(substr($text, 0, 1) == $control_char) {
		$msg = substr($text, 1);
		$connection->post('statuses/update', array('status' => $msg));
		$connection->post('direct_messages/destroy', array('id' => $dm->id));
		echo "<h2>tweeting</h2> <p>" . $msg . "</p>";
	} else {
		echo "<h2>not tweeting</h2> <p>" . $text . "</p>";
	}
	echo "</div>";
}
?>