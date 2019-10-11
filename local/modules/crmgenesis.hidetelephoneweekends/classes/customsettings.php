<?php

class CustomSettings{

    public function addHideFunctionToPage(){
        global $APPLICATION;
        global $USER;

        //ID группы
        $adminWeekendGroup = COption::GetOptionInt("crmgenesis.hidetelephoneweekends", "telephonyWeekendsAdminID");

        $arGroups = self::getUsersFromGroup($adminWeekendGroup);
        if(!in_array($USER->GetID(),$arGroups)){
            CJSCore::Init(["jquery2"]); //Штатная библиотека
            $APPLICATION->AddHeadScript("/bitrix/js/crmgenesis/editTelephonyCard/script.js");
        }

    }

    private function getUsersFromGroup($group_id){
        $filter = Array("GROUPS_ID"=>$group_id);
        $rsUsers = CUser::GetList(($by="ID"), ($order="asc"), $filter);
        while($arItem = $rsUsers->GetNext())
        {
            //убираем пользователей с пустым именем и фамилией из выпадающего списка
            if($arItem['LAST_NAME'] == '' && $arItem['NAME'] == '' ) continue;
            $users[] = $arItem['ID'];
        }
        return $users;
    }

}