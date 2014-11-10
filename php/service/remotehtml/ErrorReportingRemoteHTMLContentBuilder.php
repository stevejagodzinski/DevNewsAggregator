<?php

require_once 'php/model/news/NewsEntry.php';
require_once 'php/model/news/NewsEntryComparator.php';
require_once 'php/model/remotehtml/RemoteHTMLContent.php';
require_once 'php/model/remotehtml/dateparsing/DateFieldInformationFactory.php';
require_once 'php/service/remotehtml/RemoteHTMLContentFetcher.php';
require_once 'php/view/remotehtml/HTMLResponseBuilder.php';

class ErrorReportingRemoteHTMLContentBuilder {

    public static function getRemoteHTMLContent() {
        $connection = mysqli_connect("localhost", "DevNews", "DevNews", "DevNewsAggregator");
        if (mysqli_connect_errno()) {
            $error = "Failed to connect to MySQL: " . mysqli_connect_error();
            error_log($error);
            return error;
        } else {
            $allStories = array();

            $result = mysqli_query($connection, "SELECT * FROM htmlcontent WHERE enabled = 1");
            while($row = mysqli_fetch_array($result)) {
                $dateFieldInformation = DateFieldInformationFactory::create($row);
                $remoteHTMLContent = new RemoteHTMLContent($row['url'], $row['name'], $row['scraping_strategy'], $row['outerContentSelector'], $row['innerContentSelector'], $row['titleSelector'],
                    $dateFieldInformation, $row['ignore_first_n_posts'], $row['ignore_last_n_post']);

                $fetchedStories = RemoteHTMLContentFetcher::getInstance()->fetch($remoteHTMLContent);
                $allStories = array_merge($allStories, $fetchedStories);
            }

            mysqli_close($connection);

            uasort($allStories, "NewsEntryComparator::compare");

            return self::toHtml($allStories);
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