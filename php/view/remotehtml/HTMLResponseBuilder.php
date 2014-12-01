<?php

class HTMLResponseBuilder {
    public static function toHtml(NewsEntry $newsEntry) {
        $html =
            "<div class=\"news-entry\" data-content-source=\"". $newsEntry->getSource() ."\">".
                "<div class=\"news-entry-header\">".
                    "<span class=\"news-entry-title\">".$newsEntry->getTitle()."</span>".
                    "<span class=\"news-entry-date\" data-iso-date=\"". date("c", $newsEntry->getDate()) ."\">".date("F jS, Y h:i A", $newsEntry->getDate())."</span>".
                "</div>".
                "<div class=\"news-entry-content\">".$newsEntry->getContent()."</div>".
            "</div>";
        return $html;
    }
} 