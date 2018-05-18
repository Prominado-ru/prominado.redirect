<?php

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Prominado\Redirect\RedirectTable;

Loc::loadLanguageFile(__FILE__);

class prominado_redirect extends CModule
{
	var $MODULE_ID = 'prominado.redirect';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $PARTNER_NAME;
	var $PARTNER_URI;
	var $MODULE_CSS;

	function prominado_redirect()
	{
		$arModuleVersion = [];
        include __DIR__ . '/version.php';
		if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}
		$this->MODULE_NAME = Loc::getMessage('PROMINADO_REDIRECT_MODULE_NAME');
		$this->MODULE_DESCRIPTION = Loc::getMessage('PROMINADO_REDIRECT_MODULE_DESCRIPTION');
		$this->PARTNER_NAME = Loc::getMessage('PROMINADO_REDIRECT_MODULE_PARTNER');
		$this->PARTNER_URI = Loc::getMessage('PROMINADO_REDIRECT_MODULE_PARTNER_URL');
	}

	function DoInstall()
	{
		if (CheckVersion('17.0.11', ModuleManager::getVersion('main'))) {
			global $APPLICATION;
			$APPLICATION->ThrowException(Loc::getMessage('PROMINADO_REDIRECT_MODULE_ERROR_MAIN'));

			return false;
		}

		$this->InstallDB();
		$this->InstallEvents();
		$this->InstallFiles();

		return true;
	}

	function InstallDB()
	{
		ModuleManager::registerModule($this->MODULE_ID);
		Loader::includeSharewareModule('prominado.redirect');
		$connection = Application::getConnection();
		if (!$connection->isTableExists(RedirectTable::getTableName())) {
            try {
                RedirectTable::getEntity()->createDbTable();
            } catch (\Bitrix\Main\ArgumentException $e) {
            } catch (\Bitrix\Main\SystemException $e) {
            }
        }
	}

	function InstallEvents()
	{
		$eventManager = \Bitrix\Main\EventManager::getInstance();
		$eventManager->registerEventHandler('main', 'OnBuildGlobalMenu', $this->MODULE_ID, '\\Prominado\\Redirect\\UI',
			'onGlobalMenu');
		$eventManager->registerEventHandler('main', 'OnBeforeProlog', $this->MODULE_ID, '\\Prominado\\Redirect\\Core',
			'init');
	}

	function InstallFiles()
	{
		CopyDirFiles(__DIR__ . '/admin/', Application::getDocumentRoot() . '/bitrix/admin/');
	}

	function DoUninstall()
	{
		$this->UnInstallEvents();
		ModuleManager::unRegisterModule($this->MODULE_ID);
		$this->UnInstallFiles();
		$this->UnInstallDB();

		return true;
	}

	function UnInstallDB()
	{
		ModuleManager::unRegisterModule($this->MODULE_ID);
		Loader::includeSharewareModule('prominado.redirect');
		$connection = Application::getConnection();
		if ($connection->isTableExists(RedirectTable::getTableName())) {
            try {
                $connection->dropTable(RedirectTable::getTableName());
            } catch (\Bitrix\Main\Db\SqlQueryException $e) {
            }
        }
	}

	function UnInstallEvents()
	{
		$eventManager = \Bitrix\Main\EventManager::getInstance();
		$eventManager->unRegisterEventHandler('main', 'OnBuildGlobalMenu', $this->MODULE_ID,
			'\\Prominado\\Redirect\\UI', 'onGlobalMenu');
		$eventManager->unRegisterEventHandler('main', 'OnBeforeProlog', $this->MODULE_ID, '\\Prominado\\Redirect\\Core',
			'init');
	}

	function UnInstallFiles()
	{
		DeleteDirFiles(__DIR__ . '/admin/', Application::getDocumentRoot() . '/bitrix/admin/');
	}
}