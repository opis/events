##Opis Events##

```php
use \Opis\Events\Event;
use \Opis\Events\EventListener;

EventListener::add('load', function(Event $event){
    print "Event A\n";
});

EventListener::add('load', function(Event $event){
  print "Event B\n";
});

Event::init('load')->dispatch();
```
The output will be

```
Event B
Event A
```

###Creating custom  events###

```php
use \Opis\Events\Event;
use \Opis\Events\EventListener;

class CustomEvent extends Event
{
    protected $eventData;
    
    public function __construct($name, $data = null, $cancelable = false)
    {
        parent::__construct($name, $cancelable);
        $this->eventData = $data;
    }
    
    public static function init($name, $data = null, $cancelable = false)
    {
        return new static($name, $data, $cancelable);
    }
    
    public function data()
    {
        return $this->eventData;
    }
}

EventListener::add('custom', function(CustomEvent $event){
    print $event->data();
});

CustomEvent::init('custom', "Hello World")->dispatch();
```
The output will be

```
Hello World
```