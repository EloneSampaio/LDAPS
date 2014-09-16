<?php

function convert_AD_date($ad_date) {

    if ($ad_date == 0) {
        return '0000-00-00';
    }

    $secsAfterADEpoch = $ad_date / (10000000);
    $AD2Unix = ((1970 - 1601) * 365 - 3 + round((1970 - 1601) / 4) ) * 86400;


    $unixTimeStamp = intval($secsAfterADEpoch - $AD2Unix);
    $myDate = date("Y-m-d H:i:s", $unixTimeStamp); 

    return $myDate;
}

?>