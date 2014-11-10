<?php

require_once('lib/phpQuery/phpQuery.php');
require_once 'php/model/news/NewsEntry.php';
require_once 'php/model/remotehtml/dateparsing/IDateFieldInformation.php';
require_once 'php/model/remotehtml/RemoteHTMLContent.php';
require_once 'php/service/remotehtml/dateparsing/SeparateDateTimeFieldsParsingStrategy.php';

class RemoteHTMLResponseParser {
    private $dateParsingStrategies;

    public static function getInstance() {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    protected function __construct() {
        $this->dateParsingStrategies=array();
        $this->dateParsingStrategies[] = SeparateDateTimeFieldsParsingStrategy::getInstance();
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    public function parse(RemoteHTMLContent $remoteHTMLContent, $response) {
        $newsStories = array();

        $doc = phpQuery::newDocument($response);

        $posts = pq($remoteHTMLContent->getOuterContentSelector(), $doc);
        $numberOfPosts = count($posts->elements);
        for ($i=0; $i<$numberOfPosts; $i++) {
            $newsEntry = new NewsEntry();
            $post = $posts->eq($i);
            $newsEntry->setTitle($post->find($remoteHTMLContent->getTitleSelector())->html());
            $newsEntry->setContent($post->find($remoteHTMLContent->getInnerContentSelector())->html());
            $newsEntry->setDate($this->parseDate($post, $remoteHTMLContent->getDateFieldInformation()));
            $newsStories[] = $newsEntry;
        }

        return $newsStories;
    }

    private function parseDate($post, IDateFieldInformation $dateFieldInformation) {
        foreach($this->dateParsingStrategies as $dateParsingStrategy) {
            if ($dateParsingStrategy->applies($dateFieldInformation)) {
                return $dateParsingStrategy->parse($post, $dateFieldInformation);
            }
        }
    }
} 