##Opis Events##

```php
use \Opis\Events\Event;
use \Opis\Events\Listener;

Listener::add('load', function(Event $event){
    print "Event A\n";
});

Listener::add('load', function(Event $event){
  print "Event B\n";
});

Event::init('load')->dispatch();

```