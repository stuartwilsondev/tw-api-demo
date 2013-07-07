<?php
/**
 * @author Stuart Wilson <stiuart@stuartwilsondev.com>
 */

class Twitter {


    private $consumerKey;
    private $consumerSecret;
    private $requestTokenUrl;
    private $authorizeUrl;
    private $accessTokenUrl;
    private $accessToken;
    private $accessTokenSecret;

    function __construct(
        $accessToken,
        $accessTokenSecret,
        $accessTokenUrl,
        $authorizeUrl,
        $consumerKey,
        $consumerSecret,
        $requestTokenUrl
    ){
        $this->accessToken = $accessToken;
        $this->accessTokenSecret = $accessTokenSecret;
        $this->accessTokenUrl = $accessTokenUrl;
        $this->authorizeUrl = $authorizeUrl;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->requestTokenUrl = $requestTokenUrl;
    }


    public function getUserTimelineAction()
    {
        $tweets = $this->twitterTimeline(
            $this->getRequest()->getParam('twit_username'),
            $this->getRequest()->getParam('tweet_count')
        );


        $response = new stdClass();
        if($tweets){
            $response->status = 'success';
            $response->tweets = $tweets;
        }else{
            $response->status = 'failure';
        }

        $this->_outputResponse($response, $encodeObjectToJSON = true);
    }

    private function twitterTimeline($twitterUsername, $tweet_count=10)
    {

        // Get Token
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, 'https://api.twitter.com/oauth2/token');
        curl_setopt($ch,CURLOPT_POST, true);
        $data = array();
        $data['grant_type'] = "client_credentials";
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch,CURLOPT_USERPWD, $consumer_key . ':' . $consumer_secret);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $bearer_token = json_decode($result);
        $bearer = $bearer_token->{'access_token'}; // this is your app token

        // Get Tweets
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, 'https://api.twitter.com/1.1/statuses/user_timeline.json?count='.$tweet_count.'&screen_name='.$twitterUsername);
        curl_setopt($ch,CURLOPT_HTTPHEADER,array('Authorization: Bearer ' . $bearer));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response,true);

        if(!$response){
            return false;
        }
        if(array_key_exists('errors',$response)){
            //we have a problem
            return false;
        }else{
            $results = array();
            if(is_array($response)){
                foreach($response as $result){
                    $results[] = array(
                        'text'      => $result['text'],
                        'image'     => $result['user']['profile_image_url'],
                        'author'    => $result['user']['name']
                    );
                }
            }else{
                $results = false;
            }
            return $results;
        }

    }


}