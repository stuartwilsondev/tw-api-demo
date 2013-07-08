<?php
/**
 * @author Stuart Wilson <stiuart@stuartwilsondev.com>
 */

require_once('Twitter.php');

$config = parse_ini_file('config.ini');
$twitter = new Twitter(
    $config['access_token'],
    $config['access_token_secret'],
    $config['access_token_url'],
    $config['authorize_url'],
    $config['twitter_consumer_key'],
    $config['twitter_consumer_secret'],
    $config['twitter_request_token_url'],
    $config['twitter_username']
);


switch($_GET['function']){

    case 'getwoeids':

        echo $twitter->getAvailableWoeIds();
        break;

    case 'gettrends':

        echo $twitter->getTrendsByWoeId();
        break;

    case '':

        echo $twitter->twitterTimeline($config['twitter_username'],10);
        break;
}