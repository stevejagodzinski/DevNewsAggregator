<?php

require_once 'php/model/remotehtml/dateparsing/DateParsingStrategy.php';
require_once 'php/model/remotehtml/dateparsing/SeparateDateTimeFields.php';

class DateFieldInformationFactory {
    public static function create($htmlContentRow){
        $dateParsingStrategy = $htmlContentRow['date_parsing_strategy'];

        switch($dateParsingStrategy) {
            case DateParsingStrategy::SEPARATE_DATE_TIME_FIELDS:
                $dateFieldInformation = new SeparateDateTimeFields($htmlContentRow['date_selector'], $htmlContentRow['time_selector']);
                break;
            default:
                throw new Exception("No IDateFieldInformation type has been implemented for date_parsing_strategy type " . $dateParsingStrategy);
        }

        return $dateFieldInformation;
    }
}