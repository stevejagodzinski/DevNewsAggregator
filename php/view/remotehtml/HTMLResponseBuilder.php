<?php

class HTMLResponseBuilder {
    public static function toHtml(NewsEntry $newsEntry) {
        $html =
            "<div class=\"news-entry\">".
                "<div class=\"news-entry-header\">".
                    "<span class=\"news-entry-title\">".$newsEntry->getTitle()."</span>".
                    "<span class=\"news-entry-date\">".$newsEntry->getDate()."</span>".
                "</div>".
                "<div class=\"news-entry-content\">".$newsEntry->getContent()."</div>".
            "</div>";
        return $html;
    }
} 