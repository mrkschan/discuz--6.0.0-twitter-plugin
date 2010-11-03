<?php

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once(DISCUZ_ROOT . './plugins/twitter/twitteroauth/twitteroauth.php');
require_once(DISCUZ_ROOT . './plugins/twitter/config.php');

class TwitterClient {

	protected $conn;
	private $t_strlen = 140;  // length threshold for twitter

	function __construct() {
		$this->conn = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET);
	}

	public function is_valid_tweet($stuff) {
		return (strlen($stuff) <= $this->t_strlen);
	}

	public function tweet($stuff) {
		$this->conn->post("statuses/update", array('status' => $stuff));
	}
}

class URLShortener {
	// http://is.gd/api_info.php

	public function mask($url) {
		// create curl resource
	        $ch = curl_init();

		// set url
		curl_setopt($ch, CURLOPT_URL, 'http://is.gd/api.php?longurl=' . $url);

		//return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// $output contains the output string
		$output = curl_exec($ch);

		// close curl resource to free up system resources
		curl_close($ch);

		if (false == preg_match('/^Error/i', $output)) $url = $output;

		return $url;
	}
}

function tweet($subject, $url) {
	$tc = new TwitterClient();
	$shortener = new URLShortener();

	$suffix = ' - ' . $shortener->mask($url);
	$stuff = $subject . $suffix;

	if ($tc->is_valid_tweet($stuff)) {
		$tc->tweet($stuff);
	}
}

?>
