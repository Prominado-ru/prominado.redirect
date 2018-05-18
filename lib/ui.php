<?php

namespace Prominado\Redirect;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class UI
{
    function onGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
    {
        $aModuleMenu[] = [
            'parent_menu' => 'global_menu_settings',
            'section' => 'prominado_redirect',
            'sort' => 100,
            'url' => '',
            'text' => Loc::getMessage('PROMINADO_REDIRECT_MODULE_NAME'),
            'title' => Loc::getMessage('PROMINADO_REDIRECT_MODULE_NAME'),
            'icon' => 'form_menu_icon',
            'page_icon' => 'form_page_icon',
            'items_id' => 'menu_prominado_redirect',
            'items' => [
                [
                    'parent_menu' => '',
                    'section' => 'prominado_redirect',
                    'sort' => 10,
                    'url' => 'prominado_redirect_list.php',
                    'text' => Loc::getMessage('PROMINADO_REDIRECT_LIST'),
                    'title' => Loc::getMessage('PROMINADO_REDIRECT_LIST'),
                    'items_id' => 'menu_prominado_redirect',
                ],
                [
                    'parent_menu' => '',
                    'section' => 'prominado_redirect',
                    'sort' => 20,
                    'url' => 'prominado_redirect_edit.php',
                    'text' => Loc::getMessage('PROMINADO_REDIRECT_NEW'),
                    'title' => Loc::getMessage('PROMINADO_REDIRECT_NEW'),
                    'items_id' => 'menu_prominado_redirect',
                ],
            ]
        ];
    }
}