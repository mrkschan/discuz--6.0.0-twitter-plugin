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
		return (strlen($stuff) <= $t_strlen);
	}

	public function tweet($stuff) {
		$this->conn->post("statuses/update", array('status' => $stuff));
	}
}

class URLShortener {
	// TODO
	function __construct() {
	}

	public function mask($url) {
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
