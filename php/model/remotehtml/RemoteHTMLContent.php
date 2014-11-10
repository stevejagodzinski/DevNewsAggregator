<?php

class RemoteHTMLContent {
    private $url;
    private $name;
    private $scrapingStrategy;
    private $outerContentSelector;
    private $titleSelector;
    private $innerContentSelector;
    private $dateFieldInformation;
    private $ignoreFirstNPosts;
    private $ignoreLastNPosts;

    function __construct($url, $name, $scrapingStrategy, $outerContentSelector, $innerContentSelector, $titleSelector, IDateFieldInformation $dateFieldInformation, $ignoreFirstNPosts, $ignoreLastNPosts) {
        $this->innerContentSelector = $innerContentSelector;
        $this->name = $name;
        $this->scrapingStrategy = $scrapingStrategy;
        $this->outerContentSelector = $outerContentSelector;
        $this->titleSelector = $titleSelector;
        $this->url = $url;
        $this->dateFieldInformation = $dateFieldInformation;
        $this->ignoreFirstNPosts = $ignoreFirstNPosts;
        $this->ignoreLastNPosts = $ignoreLastNPosts;
    }

    function getURL() {
        return $this->url;
    }

    public function getInnerContentSelector() {
        return $this->innerContentSelector;
    }

    public function getName() {
        return $this->name;
    }

    public function getOuterContentSelector() {
        return $this->outerContentSelector;
    }

    public function getTitleSelector() {
        return $this->titleSelector;
    }

    public function getDateFieldInformation() {
        return $this->dateFieldInformation;
    }

    public function getIgnoreFirstNPosts() {
        return $this->ignoreFirstNPosts;
    }

    public function getIgnoreLastNPosts() {
        return $this->ignoreLastNPosts;
    }

    public function getScrapingStrategy() {
        return $this->scrapingStrategy;
    }
}
?>