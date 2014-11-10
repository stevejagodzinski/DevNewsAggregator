<?php

require_once 'php/model/remotehtml/dateparsing/DateParsingStrategy.php';
require_once 'php/model/remotehtml/dateparsing/IDateFieldInformation.php';
require_once 'php/model/remotehtml/dateparsing/SeparateDateTimeFields.php';
require_once 'php/service/remotehtml/dateparsing/IDateParsingStrategy.php';
require_once 'php/service/remotehtml/jquery/JQueryToPhpQuery.php';

class SeparateDateTimeFieldsParsingStrategy implements IDateParsingStrategy {
    public static function getInstance() {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    protected function __construct() {
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    public function applies(IDateFieldInformation $dateFieldInformation) {
        return $dateFieldInformation->getStrategy() == DateParsingStrategy::SEPARATE_DATE_TIME_FIELDS;
    }

    public function parse($post, IDateFieldInformation $dateFieldInformation, $n) {
        return $this->doParse($post, $dateFieldInformation, $n);
    }

    private function doParse($post, SeparateDateTimeFields $dateFieldInformation, $n) {
        $dateFunction = '$date = $post' . JQueryToPhpQuery::toPhpQuery($dateFieldInformation->getDateSelector()) . ";";
        eval($dateFunction);

        if ($dateFieldInformation->getTimeSelector() != null) {
            $timeFunction = '$time = $post' . JQueryToPhpQuery::toPhpQuery($dateFieldInformation->getTimeSelector()) . ";";
            eval($timeFunction);
            return strtotime($date . ' ' . $time);
        } else {
            return strtotime($date);
        }
    }
}