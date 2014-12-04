<?php

require_once 'php/service/remotehtml/RemoteHTMLContentRequester.php';
require_once 'php/service/remotehtml/scrapingstrategies/ElementPerNewsEntryHTMLParser.php';
require_once 'php/service/remotehtml/scrapingstrategies/NewsEntriesListedInContainingElementParser.php';

class RemoteHTMLContentScraper {
    private $scrapingStrategies;

    public static function getInstance() {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    protected function __construct() {
        $this->scrapingStrategies=array();
        $this->scrapingStrategies[] = ElementPerNewsEntryHTMLParser::getInstance();
        $this->scrapingStrategies[] = NewsEntriesListedInContainingElementParser::getInstance();
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    public function scrape($remoteHTMLContentArray) {
        $responses = self::fetchRemoteContent($remoteHTMLContentArray);
        return $this->scrapeResponses($remoteHTMLContentArray, $responses);
    }

    private function scrapeResponses($remoteHTMLContentArray, $responses) {
        $allStories = array();

        for($i = 0; $i<count($responses); $i++) {
            $fetchedStories = $this->scrapeResponse($responses[$i], $remoteHTMLContentArray[$i]);
            $allStories = array_merge($allStories, $fetchedStories);
        }

        return $allStories;
    }

    private function scrapeResponse($response, RemoteHTMLContent $remoteHTMLContent) {
        foreach($this->scrapingStrategies as $scrapingStrategy) {
            if ($scrapingStrategy->applies($remoteHTMLContent)) {
                return $scrapingStrategy->parse($remoteHTMLContent, $response);
            }
        }
    }

    private static function fetchRemoteContent($remoteHTMLContentArray) {
        // Requests are run in parallel. This function call is not itself asynchronous
        $fetcher = new RemoteHTMLContentRequester(self::toUrlArray($remoteHTMLContentArray));
        return $fetcher->fetchAsynchronously();
    }

    private static function toUrlArray($remoteHTMLContentArray) {
        $urls = array();
        foreach($remoteHTMLContentArray as $remoteHTMLContent) {
            $urls[] = $remoteHTMLContent->getURL();
        }
        return $urls;
    }
}
?>