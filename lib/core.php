<?php

namespace Prominado\Redirect;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Context;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Event;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;

class Core
{
    function init()
    {
        $url = Context::getCurrent()->getRequest()->getRequestUri();
        $cache = Cache::createInstance();
        if ($cache->initCache(36000000, 'prominado-redirect-' . md5($url))) {
            $page = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            try {
                $page = RedirectTable::getList([
                    'filter' => ['=OLD_URL' => Context::getCurrent()->getRequest()->getRequestUri()]
                ])->fetch();

                $cache->endDataCache($page);
            } catch (ObjectPropertyException $e) {
            } catch (ArgumentException $e) {
            } catch (SystemException $e) {
            }
        }

        if ($page['NEW_URL']) {
            $event = new Event('prominado.redirect', 'onBeforeRedirect', $page);
            $event->send();

            LocalRedirect($page['NEW_URL'], false, Constant::HTTP_CODES[$page['CODE']]);
        }
    }
}