<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'vendor/autoload.php ';

header('Content-type: text/html; charset=utf-8');

$url = 'https://mobile-review.com/review/samsung-galaxy-s10-plus.shtml';
//$url = 'http://forum.php.su/topic.php?forum=71&topic=16207';
$file = file_get_contents($url);
$pattern = '#<iframe[^<]+src="[^<]+www.youtube.com/embed/(\w+)[^<]+</iframe>#is';
preg_match_all($pattern, $file, $matches, PREG_PATTERN_ORDER);

$api_key = 'AIzaSyDSNud-QsEcx1vZlQyqEhgLzwq6jg1mMCQ';

    for ($i = 0; $i < 3; $i++) {
        $video_url = $matches[1][$i];
        $api_url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&id=' . $video_url . '&key=' . $api_key;
        $data = json_decode(file_get_contents($api_url));

        echo '<strong>Title: </strong>' . $data->items[0]->snippet->title . '<br>';
        echo '<strong>publishedAt: </strong>' . $data->items[0]->snippet->publishedAt . '<br>';
        echo '<strong>Duration: </strong>' . $data->items[0]->statistics->viewCount . '<br>';
}

class YouTubeVideo
{
    public $id; //id видео
    private $apiKey = 'AIzaSyDSNud-QsEcx1vZlQyqEhgLzwq6jg1mMCQ';
    private $youtube;

    public function __construct()
    {
        $client = new Google_Client();
        $client->setDeveloperKey($this->apiKey);
        $this->youtube = new Google_Service_YouTube($client);
    }


    /*
    * Получение данных видео по их id
    */
    public function videosByIds(string $ids)
    {
        return $this->youtube->videos->listVideos('snippet, statistics, contentDetails', [
            'id' => $ids,
        ]);
    }
}