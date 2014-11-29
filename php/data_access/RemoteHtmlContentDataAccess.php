<?php

require_once 'php/model/remotehtml/RemoteHTMLContent.php';
require_once 'php/model/remotehtml/dateparsing/DateFieldInformationFactory.php';

class RemoteHtmlContentDataAccess {
    public static function getAll() {
        $connection = pg_connect("host=localhost port=5432 dbname=DevNewsAggregator user=DevNews password=DevNews") or die("Could not connect to Postgres");
        $result = pg_query($connection, 'SELECT * FROM "DevNewsAggregatorConfiguration_htmlcontent" WHERE enabled = true') or die("Could not execute query");

        $remoteHTMLContent = array();

        while($row = pg_fetch_array($result)) {
            $dateFieldInformation = DateFieldInformationFactory::create($row);
            $remoteHTMLContent[] =  new RemoteHTMLContent($row['url'], $row['name'], $row['scraping_strategy'], $row['outer_content_selector'], $row['inner_content_selector'], $row['title_selector'],
                $dateFieldInformation, $row['ignore_first_n_posts'], $row['ignore_last_n_posts']);
        }

        pg_close($connection);

        return $remoteHTMLContent;
    }
} 