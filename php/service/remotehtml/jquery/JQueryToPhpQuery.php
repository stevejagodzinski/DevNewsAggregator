<?php


class JQueryToPhpQuery {
    /**
     * Converts a jQuery expression into a chain of equivalent phpQuery method calls
     *
     * This assumes that phpQuery will support all jQuery functions. This utility simply converts function calls made via the dot operator (.) in jquery to
     * use the arrow (->) operator in php.
     *
     * @param $jquery A jquery expression (chain of jQuery method calls)
     * @return a string representing the php method chain in the phpQuery library
     */
    public static function toPhpQuery($jquery) {
        $phpQuery = str_replace(").", ")->", $jquery);

        if(strpos($phpQuery, ")") && strpos($phpQuery, ".") === 0) {
            $phpQuery[0] = '>';
            $phpQuery = "-" . $phpQuery;
        }

        return $phpQuery;
    }
} 