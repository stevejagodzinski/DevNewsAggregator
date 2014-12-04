<?php

require_once 'php/data_access/RemoteHtmlContentDataAccess.php';
require_once 'php/model/news/NewsEntry.php';
require_once 'php/model/news/NewsEntryComparator.php';
require_once 'php/model/remotehtml/RemoteHTMLContent.php';
require_once 'php/service/remotehtml/RemoteHTMLContentScraper.php';
require_once 'php/view/remotehtml/HTMLResponseBuilder.php';

class ErrorReportingRemoteHTMLContentBuilder {
    public static function getRemoteHTMLContent() {
        $allStories = RemoteHTMLContentScraper::getInstance()->scrape(self::getRemoteHtmlContentToScrape());
        uasort($allStories, "NewsEntryComparator::compare");
        return self::toHtml($allStories);
    }

    private static function getRemoteHtmlContentToScrape() {
        if (isset($_GET['name'])) {
            return RemoteHtmlContentDataAccess::getByName($_GET['name']);
        }elseif (isset($_GET['userid'])) {
            return RemoteHtmlContentDataAccess::getForUser($_GET['userid']);
        } else {
            return RemoteHtmlContentDataAccess::getAll();
        }
    }

    private static function toHtml($newsStories) {
        $html = "";
        foreach($newsStories as $newsStory) {
            $html = $html . HTMLResponseBuilder::toHtml($newsStory);
        }
        return $html;
    }
}
?>