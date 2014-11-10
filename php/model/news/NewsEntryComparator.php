<?php

require_once 'php/model/news/NewsEntry.php';

class NewsEntryComparator {
    public static function compare(NewsEntry $a, NewsEntry $b) {
        $returnValue = self::compareByDate($a, $b);

        if($returnValue === 0) {
            $returnValue = self::compareByTitle($a, $b);
        }

        return $returnValue;
    }

    private static function compareByDate(NewsEntry $a, NewsEntry $b) {
        if ($a->getDate() === $b->getDate()) {
            return 0;
        } else if ($a->getDate() === NULL) {
            return -1;
        } else if ($b->getDate() === NULL) {
            return 1;
        } else {
            return $b->getDate() - $a->getdate();
        }
    }

    private static function compareByTitle(NewsEntry $a, NewsEntry $b) {
        if ($a->getTitle() === $b->getTitle()) {
            return 0;
        } else if ($a->getTitle() === NULL) {
            return -1;
        } else if ($b->getTitle() === NULL) {
            return 1;
        } else {
            return strcmp ($a->getTitle(), $b->getTitle());
        }
    }
} 