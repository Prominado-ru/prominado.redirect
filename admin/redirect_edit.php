<?

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Prominado\Redirect\RedirectTable;
use Prominado\Redirect\Constant;

require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog.php');

Loc::loadMessages(__FILE__);
Loader::includeSharewareModule('prominado.redirects');

$ID = ($_REQUEST['ID'] > 0) ? $_REQUEST['ID'] : 0;

$APPLICATION->SetTitle(($ID > 0) ? Loc::getMessage('PROMINADO_REDIRECT_REDIRECT_EDIT') : Loc::getMessage('PROMINADO_REDIRECT_REDIRECT_NEW'));
require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_admin_after.php');

$tabs = [];
$tabs[] = [
	'DIV'   => 'edit1',
	'TAB'   => Loc::getMessage('PROMINADO_REDIRECT_REDIRECT'),
	'ICON'  => 'main_user_edit',
	'TITLE' => Loc::getMessage('PROMINADO_REDIRECT_REDIRECT_SETTINGS')
];

$tabControl = new \CAdminForm('prominado_redirect_edit', $tabs);

$errorText = '';

if (
	($_SERVER['REQUEST_METHOD'] == 'POST') &&
	($_POST['save'] <> '' || $_POST['apply'] <> '' || $_POST['save_and_add'] <> '') &&
	check_bitrix_sessid()
) {
	$fields = [
		'OLD_URL' => $_POST['OLD_URL'],
		'NEW_URL' => $_POST['NEW_URL'],
		'CODE'    => $_POST['CODE']
	];

	if ($ID > 0) {
		$el = RedirectTable::update($ID, $fields);
	} else {
		$el = RedirectTable::add($fields);
	}

	if ($el->isSuccess()) {
		$redirect_url_list = '/bitrix/admin/prominado_redirect_list.php?lang=' . LANG;
		$redirect_url_edit = '/bitrix/admin/prominado_redirect_edit.php?lang=' . LANG;

		if ($_POST['save'] <> '') {
			LocalRedirect($redirect_url_list);
		} elseif ($_POST["apply"] <> '') {
			LocalRedirect($redirect_url_edit . '&ID=' . $el->getId() . '&' . $tabControl->ActiveTabParam());
		} elseif ($_POST["save_and_add"] <> '') {
			LocalRedirect($redirect_url_edit . '&ID=0&' . $tabControl->ActiveTabParam());
		}
	} else {
		$errorText = implode('<br />', $el->getErrorMessages());
	}
}

$menu = [];
$menu[] = [
	'TEXT'  => Loc::getMessage('PROMINADO_REDIRECT_REDIRECT_LIST'),
	'LINK'  => '/bitrix/admin/yaroslavl_redirect_list.php?lang=' . LANG,
	'ICON'  => 'btn_list',
	'TITLE' => Loc::getMessage('PROMINADO_REDIRECT_REDIRECT_LIST'),
];

$context = new \CAdminContextMenu($menu);
$context->Show();

if ($errorText) {
	$e = new CAdminException([['text' => $errorText]]);
	$message = new CAdminMessage(Loc::getMessage('PROMINADO_REDIRECT_ERROR'), $e);
	echo $message->Show();
}

$tabControl->BeginPrologContent();
$tabControl->EndPrologContent();
$tabControl->BeginEpilogContent();
?>
<?= bitrix_sessid_post() ?>
<?
$tabControl->EndEpilogContent();
$tabControl->Begin(['FORM_ACTION' => $APPLICATION->GetCurPage() . '?ID=' . $ID . '&lang=' . LANG]);

$tabControl->BeginNextFormTab();

$fields = [];
if ($ID > 0) {
	$fields = RedirectTable::getById($ID)->fetch();
} else {
	$fields = $_POST;
}

$tabControl->AddEditField('OLD_URL', Loc::getMessage('PROMINADO_REDIRECT_OLD_URL'), true, ['size' => 30],
	$fields['OLD_URL']);
$tabControl->AddEditField('NEW_URL', Loc::getMessage('PROMINADO_REDIRECT_NEW_URL'), true, ['size' => 30],
	$fields['NEW_URL']);
$tabControl->AddDropDownField('CODE', Loc::getMessage('PROMINADO_REDIRECT_CODE'), false, Constant::HTTP_CODES,
	$fields['CODE']);

$tabControl->Buttons([
	"disabled"      => false,
	"btnSave"       => true,
	"btnCancel"     => true,
	"btnSaveAndAdd" => true,
]);

$tabControl->Show();
$tabControl->ShowWarnings($tabControl->GetName(), $message);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");