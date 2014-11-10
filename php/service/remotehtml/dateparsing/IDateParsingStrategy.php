<?php

interface IDateParsingStrategy {
    public function applies(IDateFieldInformation $dateFieldInformation);

    public function parse($post, IDateFieldInformation $dateFieldInformation, $n);
} 