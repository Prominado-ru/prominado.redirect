<?php

namespace Prominado\Redirect;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\Validator\Length;
use Bitrix\Main\Entity\Validator\Unique;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);

class RedirectTable extends DataManager
{
    public static function getTableName()
    {
        return 'b_prominado_redirects';
    }

    public static function getMap()
    {
        try {
            return [
                new IntegerField('ID', [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('PROMINADO_REDIRECT_ID')
                ]),

                new StringField('OLD_URL', [
                    'required' => true,
                    'title' => Loc::getMessage('PROMINADO_REDIRECT_OLD_URL'),
                    'validation' => function () {
                        return [
                            new Unique(),
                            new Length(1, 1000),
                        ];
                    }
                ]),

                new StringField('NEW_URL', [
                    'required' => true,
                    'title' => Loc::getMessage('PROMINADO_REDIRECT_NEW_URL'),
                    'validation' => function () {
                        return [
                            new Length(1, 1000),
                        ];
                    }
                ]),

                new IntegerField('CODE', [
                    'default' => 301,
                    'title' => Loc::getMessage('PROMINADO_REDIRECT_CODE')
                ]),
            ];
        } catch (SystemException $e) {
        }

        return [];
    }
}