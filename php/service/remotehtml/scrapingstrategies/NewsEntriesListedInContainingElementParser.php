<?php

require_once('lib/phpQuery/phpQuery.php');
require_once 'php/model/news/NewsEntry.php';
require_once 'php/model/remotehtml/dateparsing/IDateFieldInformation.php';
require_once 'php/model/remotehtml/RemoteHTMLContent.php';
require_once 'php/model/remotehtml/scrapingstrategy/HTMLScrapingStrategy.php';
require_once 'php/service/remotehtml/dateparsing/SeparateDateTimeFieldsParsingStrategy.php';
require_once 'php/service/remotehtml/jquery/JQueryToPhpQuery.php';
require_once 'php/service/remotehtml/scrapingstrategies/IHTMLScrapingStrategy.php';

class NewsEntriesListedInContainingElementParser {
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

        $n = $remoteHTMLContent->getIgnoreFirstNPosts();

        do {
            $title = $this->evaluateJQuery($posts, $remoteHTMLContent->getTitleSelector(), $n);
            $content = $this->evaluateJQuery($posts, $remoteHTMLContent->getInnerContentSelector(), $n);
            $exists = strlen($title) != 0 || strlen($content) != 0;

            if($exists) {
                $newsEntry = new NewsEntry();
                $newsEntry->setTitle($title);
                $newsEntry->setContent($content);
                $newsEntry->setDate($this->parseDate($posts, $remoteHTMLContent->getDateFieldInformation(), $n));
                $newsStories[] = $newsEntry;

                $n++;
            }
        } while($exists);

        for($i=0; $i<$remoteHTMLContent->getIgnoreLastNPosts(); $i++) {
            array_pop($newsStories);
        }

        return $newsStories;
    }

    // Convention under this strategy is that the provided jQuery expression will use $n to specify the element under iteration
    private function evaluateJQuery($target, $jquery, $n) {
        $assignment = '$value = $target' . JQueryToPhpQuery::toPhpQuery($jquery) . ";";
        eval($assignment);
        return $value;
    }

    private function parseDate($post, IDateFieldInformation $dateFieldInformation, $n) {
        foreach($this->dateParsingStrategies as $dateParsingStrategy) {
            if ($dateParsingStrategy->applies($dateFieldInformation)) {
                return $dateParsingStrategy->parse($post, $dateFieldInformation, $n);
            }
        }
    }

    public function applies(RemoteHTMLContent $remoteHTMLContent) {
        return HTMLScrapingStrategy::NEWS_ENTRIES_LISTED_IN_CONTAINING_ELEMENT == $remoteHTMLContent->getScrapingStrategy();
    }
} 