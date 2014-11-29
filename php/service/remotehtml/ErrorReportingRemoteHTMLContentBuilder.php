<?php

require_once 'php/model/news/NewsEntry.php';
require_once 'php/model/news/NewsEntryComparator.php';
require_once 'php/model/remotehtml/RemoteHTMLContent.php';
require_once 'php/model/remotehtml/dateparsing/DateFieldInformationFactory.php';
require_once 'php/service/remotehtml/RemoteHTMLContentFetcher.php';
require_once 'php/view/remotehtml/HTMLResponseBuilder.php';

class ErrorReportingRemoteHTMLContentBuilder {

    public static function getRemoteHTMLContent() {
        $connection = pg_connect("host=localhost port=5432 dbname=DevNewsAggregator user=DevNews password=DevNews") or die("Could not connect to Postgres");
        $result = pg_query($connection, 'SELECT * FROM "DevNewsAggregatorConfiguration_htmlcontent"') or die("Could not execute query");

        $allStories = array();

        while($row = pg_fetch_array($result)) {
            $dateFieldInformation = DateFieldInformationFactory::create($row);
            $remoteHTMLContent = new RemoteHTMLContent($row['url'], $row['name'], $row['scraping_strategy'], $row['outer_content_selector'], $row['inner_content_selector'], $row['title_selector'],
                $dateFieldInformation, $row['ignore_first_n_posts'], $row['ignore_last_n_posts']);

            $fetchedStories = RemoteHTMLContentFetcher::getInstance()->fetch($remoteHTMLContent);
            $allStories = array_merge($allStories, $fetchedStories);
        }

        pg_close($connection);

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