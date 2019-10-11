<?php
global $APPLICATION;
if( !(strstr($APPLICATION->GetCurPage(), '/bitrix/')) && (strstr($APPLICATION->GetCurPage(), '/telephony/')) ) {
    $APPLICATION->AddHeadScript('/local/lib/js/missed_call.js');
    $APPLICATION->SetAdditionalCSS('/local/lib/css/style.css');
}

?>