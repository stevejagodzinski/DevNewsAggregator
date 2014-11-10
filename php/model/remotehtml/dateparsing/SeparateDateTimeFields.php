<?php

require_once 'php/model/remotehtml/dateparsing/IDateFieldInformation.php';
require_once 'php/model/remotehtml/dateparsing/DateParsingStrategy.php';

class SeparateDateTimeFields implements IDateFieldInformation {
    private $dateSelector;
    private $timeSelector;

    function __construct($dateSelector, $timeSelector) {
        $this->dateSelector = $dateSelector;
        $this->timeSelector = $timeSelector;
    }

    public function getStrategy() {
        return DateParsingStrategy::SEPARATE_DATE_TIME_FIELDS;
    }

    public function getDateSelector() {
        return $this->dateSelector;
    }

    public function getTimeSelector() {
        return $this->timeSelector;
    }
}