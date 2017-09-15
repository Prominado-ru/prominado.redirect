<?

namespace Prominado\Redirect;

use Bitrix\Main\Context;

class Core
{
	function init()
	{
		$page = RedirectTable::getList([
			'filter' => ['OLD_URL' => Context::getCurrent()->getRequest()->getRequestedPage()],
			'cache'  => [
				'ttl'         => 36000000,
				'cache_joins' => true,
			]
		])->fetch();
		if ($page['NEW_URL']) {
			LocalRedirect($page['NEW_URL'], false, Constant::HTTP_CODES[$page['CODE']]);
		}
	}
}