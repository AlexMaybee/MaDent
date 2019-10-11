<?php
class crmgenesis_hidetelephoneweekends extends CModule{

    var $MODULE_ID = "crmgenesis.hidetelephoneweekends";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME = 'CRM GENESIS';
    var $PARTNER_URI = 'https://crmgenesis.com/';

    public function crmgenesis_hidetelephoneweekends(){
        $arModuleVersion = [];

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = 'Модуль скрытия части настроек телефонии';
        $this->MODULE_DESCRIPTION = "После установки создастся группа \"Настройка выходных дней телефонии\", участники которой смогут выбирать
         выходные дни в настройке телефонии на странице /telephony/edit.php. Остальные этот блок видеть не будут.";
    }

    public function InstallFiles(){

        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/".$this->MODULE_ID."/install/js",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/js/", true, true);

        //создаем группу и сохраняем ее в cOption
        $this->createNewTelAdminGroup();

        return true;
    }

    public function UnInstallFiles(){

        DeleteDirFilesEx("/bitrix/js/crmgenesis/editTelephonyCard");

        //удаление папки itlogic из компонентов, если в ней пусто после удаления своего компонента
        if(!glob($_SERVER['DOCUMENT_ROOT'].'/bitrix/js/crmgenesis/*')) DeleteDirFilesEx("/bitrix/js/crmgenesis");

        //Удаление группы
        $this->delTelAdminGroup();

        return true;
    }

    public function DoInstall()
    {
        global $APPLICATION;
        $this->InstallFiles();
        RegisterModule($this->MODULE_ID);

        //привязка js-файла при загрузке страницы
        RegisterModuleDependences("main", "OnBeforeProlog", $this->MODULE_ID, "CustomSettings", "addHideFunctionToPage");

    }

    public function DoUninstall()
    {
        global $APPLICATION;
        $this->UnInstallFiles();

        //отвязка функции от события создания компании
        UnRegisterModuleDependences("main", "OnBeforeProlog", $this->MODULE_ID, "CustomSettings", "addHideFunctionToPage");

        UnRegisterModule($this->MODULE_ID);

    }

    private function createNewTelAdminGroup(){
        $group = new CGroup;
        $arFields = Array(
            "ACTIVE"       => "Y",
            "C_SORT"       => 15,
            "NAME"         => "Настройка выходных дней телефонии",
            "DESCRIPTION"  => "Члены группы смогут выбирать выходные дни для телефонии на странице /telephony/edit.php",
            "USER_ID"      => [1],
            "STRING_ID"      => "WEEKEND_TELEPHONY_ADMIN_GROUP"
        );
        $NEW_GROUP_ID = $group->Add($arFields);
        if (strlen($group->LAST_ERROR)>0) ShowError($group->LAST_ERROR);
        else{
            COption::SetOptionInt($this->MODULE_ID, "telephonyWeekendsAdminID", $NEW_GROUP_ID);
        }
    }

    private function delTelAdminGroup(){
        $chiefGroupId = COption::GetOptionInt($this->MODULE_ID, "telephonyWeekendsAdminID");
        if($chiefGroupId){
            COption::RemoveOption($this->MODULE_ID, "telephonyWeekendsAdminID");
            $group = new CGroup;
            $group->Delete($chiefGroupId);
        }

    }

}