# Prominado Redirect

Модуль для [1С-Битрикс](http://1c-bitrix.ru), позволяющий настраивать редиректы в админке.

После установки в меню Настройки появится пункт "Prominado: Редиректы"

Добавление редиректов осуществляется, как через админку, так и с помощью API

```php

/**
* $fields array Список полей редиректа
* OLD_URL - Старый URL (относительно корня сайта - /about.html)
* NEW_URL - Новый URL (относительно корня сайта - /about/)
* CODE - HTTP-код редиректа (по-умолчанию - 301)
**/
Prominado\Redirect\RedirectTable::add($fields)

/**
* $primary - Идентификатор записи
**/
Prominado\Redirect\RedirectTable::update($primary, $fields)


Prominado\Redirect\RedirectTable::delete($primary)
```