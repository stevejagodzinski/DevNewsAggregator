<?php

require_once 'php/service/remotehtml/RemoteHTMLContentRequester.php';
require_once 'php/service/remotehtml/scrapingstrategies/ElementPerNewsEntryHTMLParser.php';
require_once 'php/service/remotehtml/scrapingstrategies/NewsEntriesListedInContainingElementParser.php';

class RemoteHTMLContentFetcher {
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

    public function fetch(RemoteHTMLContent $remoteHTMLContent) {
        $response = RemoteHTMLContentRequester::fetch($remoteHTMLContent->getURL());
        return $this->scrapeResponse($response, $remoteHTMLContent);
    }

    private function scrapeResponse($response, RemoteHTMLContent $remoteHTMLContent) {
        foreach($this->scrapingStrategies as $scrapingStrategy) {
            if ($scrapingStrategy->applies($remoteHTMLContent)) {
                return $scrapingStrategy->parse($remoteHTMLContent, $response);
            }
        }
    }
}
?>