<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013-2014 Marius Sarca
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================================ */

namespace Opis\Events;

use Closure;
use InvalidArgumentException;
use Serializable;
use Opis\Routing\Pattern;

class EventTarget implements Serializable
{
    
    protected $collection;
    
    protected $router;
    
    public function __construct(RouteCollection $collection = null)
    {
        if($collection === null)
        {
            $collection = new RouteCollection();
        }
        
        $this->collection = $collection;
        $this->router = new Router($this->collection);
    }
    
    public function handle($event, Closure $callback, $priority = 0)
    {
        $handler = new EventHandler(new Pattern($event), $callback);
        $this->collection[] = $handler;
        return $handler->set('priority', $priority);
    }
    
    public function create($name, $cancelable = false)
    {
        return new Event($this, $name, $cancelable);
    }
    
    public function dispatch(Event $event)
    {
        if($event->target() !== $this)
        {
            throw new InvalidArgumentException('Inavlid target');
        }
        
        $this->collection->sort();
        $handlers = $this->router->route($event);
        
        foreach($handlers as $callback)
        {
            $callback($event);
            
            if($event->canceled())
            {
                break;
            }
        }
        
        return $event;
    }
    
    public function serialize()
    {
        return serialize($this->collection);
    }
    
    public function unserialize($data)
    {
        $this->collection = unserialize($data);
        $this->router = new Router($this->collection);
    }
    
}
