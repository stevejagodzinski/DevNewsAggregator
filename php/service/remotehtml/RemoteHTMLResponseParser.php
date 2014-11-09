<?php

require('lib/phpQuery/phpQuery.php');
require 'php/model/news/NewsEntry.php';

class RemoteHTMLResponseParser {
    public static function parse(RemoteHTMLContent $remoteHTMLContent, $response) {
        $newsStories = array();

        $doc = phpQuery::newDocument($response);

        $posts = pq($remoteHTMLContent->getOuterContentSelector(), $doc);
        $numberOfPosts = count($posts->elements);
        for ($i=0; $i<$numberOfPosts; $i++) {
            $newsEntry = new NewsEntry();
            $post = $posts->eq($i);
            $newsEntry->setTitle($post->find($remoteHTMLContent->getTitleSelector())->html());
            $newsEntry->setContent($post->find($remoteHTMLContent->getInnerContentSelector())->html());
            // TODO: Set Date
            $newsStories[] = $newsEntry;
        }

        return $newsStories;
    }
} 