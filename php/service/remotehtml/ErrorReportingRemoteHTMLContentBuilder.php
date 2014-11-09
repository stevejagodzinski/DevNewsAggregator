<?php
require 'php/view/remotehtml/HTMLResponseBuilder.php';
require 'php/model/remotehtml/RemoteHTMLContent.php';
require 'php/service/remotehtml/RemoteHTMLContentFetcher.php';

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
                $remoteHTMLContent = new RemoteHTMLContent($row['url'], $row['name'], $row['outerContentSelector'], $row['innerContentSelector'], $row['titleSelector']);

                $fetchedStories = RemoteHTMLContentFetcher::fetch($remoteHTMLContent);
                $allStories = array_merge($allStories, $fetchedStories);
            }

            mysqli_close($connection);

            // TODO: sort array

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