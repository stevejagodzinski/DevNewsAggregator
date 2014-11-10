<?php

require_once 'php/service/remotehtml/RemoteHTMLContentRequester.php';
require_once 'php/service/remotehtml/RemoteHTMLResponseParser.php';

class RemoteHTMLContentFetcher {
    public static function fetch(RemoteHTMLContent $remoteHTMLContent) {
        $response = RemoteHTMLContentRequester::fetch($remoteHTMLContent->getURL());
        return RemoteHTMLResponseParser::getInstance()->parse($remoteHTMLContent, $response);
    }
}
?>