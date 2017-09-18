<?

namespace Prominado\Redirect;

use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\Validator\Unique;

Loc::loadMessages(__FILE__);

class RedirectTable extends DataManager
{
	public static function getTableName()
	{
		return 'b_prominado_redirects';
	}

	public static function getMap()
	{
		return [
			new IntegerField('ID', [
				'primary'      => true,
				'autocomplete' => true,
				'title'        => Loc::getMessage('PROMINADO_REDIRECT_ID')
			]),

			new TextField('OLD_URL', [
				'required'   => true,
				'title'      => Loc::getMessage('PROMINADO_REDIRECT_OLD_URL'),
				'validation' => function () {
					return [
						new Unique(),
					];
				}
			]),

			new TextField('NEW_URL', [
				'required' => true,
				'title'    => Loc::getMessage('PROMINADO_REDIRECT_NEW_URL')
			]),

			new IntegerField('CODE', [
				'default' => 301,
				'title'   => Loc::getMessage('PROMINADO_REDIRECT_CODE')
			]),
		];
	}
}