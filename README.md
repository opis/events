Opis Events
===========
[![Latest Stable Version](https://poser.pugx.org/opis/events/version.png)](https://packagist.org/packages/opis/events)
[![Latest Unstable Version](https://poser.pugx.org/opis/events/v/unstable.png)](//packagist.org/packages/opis/events)
[![License](https://poser.pugx.org/opis/events/license.png)](https://packagist.org/packages/opis/events)

Dispatch and intercept events
-------------------------

###Installation

This library is available on [Packagist](https://packagist.org/packages/opis/events) and can be installed using [Composer](http://getcomposer.org)

```json
{
    "require": {
        "opis/events": "2.4.*"
    }
}
```

###Documentation

###Examples

```php
use \Opis\Events\EventTarget;

$target = new EventTarget();

$target->handle('system.{name}', function($event){
    print $event->name();
})->where('name', 'load|init');

$target->handle('system.init', function(){
    print 'System.Init event';
});

$target->create('system.load')->dispatch();
$target->create('system.init')->dispatch();

```