<?php
/**
 * @author Stuart Wilson <stiuart@stuartwilsondev.com>
 */

require_once('Twitter.php');

if(!file_exists('config.ini')){
    die('No Config');
}
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

header('Content-Type: application/json');
switch($_GET['function']){

    case 'getwoeids':

        echo $twitter->getAvailableWoeIds();
        break;

    case 'gettrends':

        echo $twitter->getTrendsByWoeId($_GET['woeid']);
        break;

    case 'gettimeline':

        echo $twitter->twitterTimeline($_GET['username'],$_GET['nooftweets']);
        break;
}