##Opis Events##

```php
use \Opis\Events\Event;
use \Opis\Events\EventHandler;
use \Opis\Events\EventTarget;

$target = new EventTarget();

$eventA = new Event($target, 'A');
$eventB = new Event($target, 'B');

$handler = new EventHandler(function($event){
    print 'Event ' . $event->name();
});

$target->add('A', $handler);
$target->add('B', $handler);

$eventA->dispatch();
$eventB->dispatch();

```
Event A
Event B
```