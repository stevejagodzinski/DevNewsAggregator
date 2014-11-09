<?php

class RemoteHTMLContent {
    private $url;
    private $name;
    private $outerContentSelector;
    private $titleSelector;
    private $innerContentSelector;

    function __construct($url, $name, $outerContentSelector, $innerContentSelector, $titleSelector)
    {
        $this->innerContentSelector = $innerContentSelector;
        $this->name = $name;
        $this->outerContentSelector = $outerContentSelector;
        $this->titleSelector = $titleSelector;
        $this->url = $url;
    }

    function getURL() {
        return $this->url;
    }

    public function getInnerContentSelector()
    {
        return $this->innerContentSelector;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOuterContentSelector()
    {
        return $this->outerContentSelector;
    }

    public function getTitleSelector()
    {
        return $this->titleSelector;
    }
}
?>