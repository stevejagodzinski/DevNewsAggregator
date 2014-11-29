<?php

require_once 'php/data_access/RemoteHtmlContentDataAccess.php';
require_once 'php/model/news/NewsEntry.php';
require_once 'php/model/news/NewsEntryComparator.php';
require_once 'php/model/remotehtml/RemoteHTMLContent.php';
require_once 'php/service/remotehtml/RemoteHTMLContentFetcher.php';
require_once 'php/view/remotehtml/HTMLResponseBuilder.php';

class ErrorReportingRemoteHTMLContentBuilder {

    public static function getRemoteHTMLContent() {
        $allStories = array();

        foreach(RemoteHtmlContentDataAccess::getAll() as $remoteHTMLContent) {
            $fetchedStories = RemoteHTMLContentFetcher::getInstance()->fetch($remoteHTMLContent);
            $allStories = array_merge($allStories, $fetchedStories);
        }

        uasort($allStories, "NewsEntryComparator::compare");

        return self::toHtml($allStories);
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