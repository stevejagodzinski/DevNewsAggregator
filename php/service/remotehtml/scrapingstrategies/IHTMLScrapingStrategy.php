<?php


interface IHTMLScrapingStrategy {
    public function applies(RemoteHTMLContent $remoteHTMLContent);
} 