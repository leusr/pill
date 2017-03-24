<?php

/**
 * Get latest tweets
 *
 * @param  string $username Twitter account name
 * @param  int $count Number of tweets to get
 *
 * @return string
 */
function get_tweets( $username, $count = 5 ) {
	// Fetch data and process response
	require_once 'twitteroauth/twitteroauth.php';

	$consumerkey       = 'RIRWEV5PiefwQr9U4jIvQ';
	$consumersecret    = 'XXNCpMS40mcmiFJwEF8MDz6uXq0eHGhIfA4gsKWKLJg';
	$accesstoken       = '1515810254-82OAyiOx4SLyzkZ5dMsVly8XsDliyfLBUoh8KnQ';
	$accesstokensecret = '5YOz783kL45Xxzi7LJtvJ9fk4ef9QHBMogdzKsFXY';

	$connection = new TwitterOAuth( $consumerkey, $consumersecret, $accesstoken, $accesstokensecret );
	$request    = "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=$username&count=$count";
	$data       = $connection->get( $request );

	if ( empty( $data ) ) {
		return "<ul><li>No tweets available.</li></ul>\n";
	}

	// Start output building
	$liformat = str_repeat( "\t", 7 )
	            . '<li><a class="author" href="https://twitter.com/%1$s" rel="external">@%1$s</a>'
	            . ' %2$s <time title="%3$s">%4$s</time></li>' . "\n";

	$links_pattern = '@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@';
	$links_replace = '<a href="$1" rel="external">$1</a>';

	$out = "<ul>\n";
	foreach ( $data as $status ) {
		$name          = $status->user->screen_name;
		$tweet         = preg_replace( $links_pattern, $links_replace, $status->text );
		$time          = strtotime( $status->created_at );
		$time_title    = date_i18n( 'Y. F j. l, H:i', $time );
		$time_since    = ml_time_since( $time );
		$out .= sprintf( $liformat, $name, $tweet, $time_title, $time_since );
	}
	$out .= str_repeat( "\t", 6 ) . "</ul>\n";

	return $out;
}
