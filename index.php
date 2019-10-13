<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php ';
header('Content-type: text/html; charset=utf-8');

$api_key = 'AIzaSyDSNud-QsEcx1vZlQyqEhgLzwq6jg1mMCQ';

$url = 'https://mobile-review.com/review/samsung-galaxy-s10-plus.shtml';

$file = file_get_contents($url);

$pattern = '~(?:http|https|)(?::\/\/|)(?:www.|)(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[a-z0-9;:@#?&%=+\/\$_.-]*~i';
preg_match_all($pattern, $file, $matches);

$count_matches = count($matches[1]);
if (!empty($count_matches)) {
    for ($i = 0; $i < $count_matches; $i++) {
        $ids = $matches[1][$i];

        $client = new Google_Client();
        $client->setDeveloperKey($api_key);
        $service = new Google_Service_YouTube($client);

        $results = $service->videos->listVideos('snippet, statistics, contentDetails', ['id' => $ids,]);

        $path = 'result.txt';
        foreach ($results as $item)
            if (!empty($item)) {
                $you = [
                    'Описание: ' . $item['snippet']['description'] . "\n",
                    'Название видео: ' . $item['snippet']['title'] . "\n",
                    'Дата публикации: ' . $item['snippet']['publishedAt'] . "\n",
                    'Просмотров: ' . $item['statistics']['viewCount'] . "\n\n",
                ];
                file_put_contents($path, $you, FILE_APPEND);
            }
    }
}