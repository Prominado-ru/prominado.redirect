<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Prominado\Redirect\RedirectTable;
use Prominado\Redirect\Constant;

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

Loc::loadMessages(__FILE__);
Loader::includeSharewareModule('prominado.redirect');

$tableId = 'prominado_redirect_list';

$sort = new \CAdminSorting($tableId, 'ID', 'DESC');
$lAdmin = new \CAdminUiList($tableId, $sort);

if ($lAdmin->EditAction()) {
    foreach ($_POST['FIELDS'] as $id => $fields) {
        RedirectTable::update($id, $fields);
    }
}

if ($ids = $lAdmin->GroupAction()) {
    foreach ($ids as $id) {
        switch ($_POST['action_button_' . $tableId]) {
            case 'delete':
                RedirectTable::delete($id);
                break;
        }
    }
}

$headers = [
    ['id' => 'ID', 'content' => 'ID', 'sort' => 'ID', 'default' => true],
    [
        'id'      => 'OLD_URL',
        'content' => Loc::getMessage('PROMINADO_REDIRECT_OLD_URL'),
        'sort'    => 'OLD_URL',
        'default' => true
    ],
    [
        'id'      => 'NEW_URL',
        'content' => Loc::getMessage('PROMINADO_REDIRECT_NEW_URL'),
        'sort'    => 'NEW_URL',
        'default' => true
    ],
    ['id' => 'CODE', 'content' => Loc::getMessage('PROMINADO_REDIRECT_CODE'), 'sort' => 'CODE', 'default' => true],
];

$lAdmin->AddHeaders($headers);

$filterFields = [
    ['id' => 'ID', 'name' => 'ID'],
    ['id' => 'OLD_URL', 'name' => Loc::getMessage('PROMINADO_REDIRECT_OLD_URL')],
    ['id' => 'NEW_URL', 'name' => Loc::getMessage('PROMINADO_REDIRECT_NEW_URL')],
    ['id' => 'CODE', 'name' => Loc::getMessage('PROMINADO_REDIRECT_CODE')],
];

$arFilter = [];
$lAdmin->AddFilter($filterFields, $arFilter);

$filter = [];
foreach ($arFilter as $k => $v) {
    switch ($k) {
        case 'OLD_URL':
        case 'NEW_URL':
            $filter[$k] = '%' . $v . '%';
            break;
        default:
            $filter[$k] = $v;
            break;
    }
}

$res = RedirectTable::getList([
    'filter' => $filter,
    'order'  => [$sort->getField() => $sort->getOrder()],
    'select' => $lAdmin->GetVisibleHeaderColumns()
]);
$data = new \CAdminUiResult($res, $tableId);
$data->NavStart();
$lAdmin->SetNavigationParams($data);

while ($ar = $data->Fetch()) {
    $row =& $lAdmin->AddRow($ar['ID'], $ar);

    $row->AddInputField('OLD_URL');
    $row->AddInputField('NEW_URL');
    $row->AddSelectField('CODE', Constant::HTTP_CODES);

    $actions = [];
    $actions[] = [
        'ICON'    => 'edit',
        'TEXT'    => Loc::getMessage('PROMINADO_REDIRECT_EDIT'),
        'LINK'    => 'prominado_redirect_edit.php?lang=' . LANGUAGE_ID . '&ID=' . $ar['ID'],
        'DEFAULT' => true
    ];
    $actions[] = [
        'ICON'   => 'delete',
        'TEXT'   => Loc::getMessage('PROMINADO_REDIRECT_DELETE'),
        'ACTION' => 'if(confirm(\'' . Loc::getMessage('PROMINADO_REDIRECT_ARE_U_SURE') . '\')) ' . $lAdmin->ActionDoGroup($ar['ID'],
                'delete')
    ];
    $row->AddActions($actions);
}

$context = [];
$context[] = [
    'TEXT'  => Loc::getMessage('PROMINADO_REDIRECT_NEW'),
    'LINK'  => 'prominado_redirect_edit.php?lang=' . LANGUAGE_ID,
    'TITLE' => Loc::getMessage('PROMINADO_REDIRECT_NEW'),
    'ICON'  => "btn_new"
];

$lAdmin->AddAdminContextMenu($context);

$lAdmin->AddGroupActionTable([
    'edit'   => true,
    'delete' => true,
]);

$lAdmin->CheckListMode();

$APPLICATION->SetTitle(Loc::getMessage('PROMINADO_REDIRECT_LIST'));

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

$lAdmin->DisplayFilter($filterFields);
$lAdmin->DisplayList();

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php';