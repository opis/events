<?php

namespace Opis\Events;

use Opis\Events\Contracts\EventTargetInterface;
use Opis\Events\Contracts\EventHandlerInterface;
use Opis\Events\Contracts\EventInterface;

class EventTarget implements EventTargetInterface
{
    
    protected $eventList = array();
    protected $dirty = false;
    
    protected function sortList()
    {
        if($this->dirty)
        {
            uasort($this->eventList, function(&$a, &$b){
                if($a['priority'] === $b['priority'])
                {
                    return 0;
                }
                return $a['priority'] < $b['priority'] ? 1 : -1;
            });
            $this->dirty = false;
        }
    }
    
    public function add($type, EventHandlerInterface $handler, $priority = 0)
    {
        $this->eventList[$type][] = array(
            'handler' => $handler,
            'priority' => $priority,
        );
        $this->dirty = true;
        
        return $this;
    }
    
    public function remove($type, EventHandlerInterface $handler)
    {
        if(isset($this->eventList[$type]))
        {
            foreach($this->eventList[$type] as $key => $object)
            {
                if($object['handler'] === $handler)
                {
                    unset($this->eventList[$type][$key]);
                    break;
                }
            }
        }
        
        return $this;
    }
    
    public function clear($type = null)
    {
        if($type !== null)
        {
            $this->eventList = array();
        }
        else
        {
            unset($this->eventList[$type]);
        }
        
        return $this;
    }
    
    public function dispatch(EventInterface $event)
    {   
        $this->sortList();
        
        if(isset($this->eventList[$event->name()]))
        {
            foreach($this->eventList[$event->name()] as &$entry)
            {
                $entry['handler']->handle($event);
                if($event->canceled())
                {
                    break;
                }
            }
        }
        
        return $event;
    }
    
    public function serialize()
    {
        return serialize(array(
            'eventList' => $this->eventList,
            'dirty' => $this->dirty,
        ));
    }
    
    public function unserialize($data)
    {
        $object = unserialize($data);
        foreach($object as $key => $value)
        {
            $this->{$key} = $value;
        }
    }
    
}