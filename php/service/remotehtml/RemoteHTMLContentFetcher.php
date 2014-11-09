<?php

require 'php/service/remotehtml/RemoteHTMLContentRequester.php';
require 'php/service/remotehtml/RemoteHTMLResponseParser.php';

class RemoteHTMLContentFetcher {
    public static function fetch(RemoteHTMLContent $remoteHTMLContent) {
        $response = RemoteHTMLContentRequester::fetch($remoteHTMLContent->getURL());
        return RemoteHTMLResponseParser::parse($remoteHTMLContent, $response);
    }
}
?>