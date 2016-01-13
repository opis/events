<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013-2016 Marius Sarca
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

use Serializable;
use Opis\Routing\Pattern;
use Opis\Routing\Callback;

class EventTarget implements Serializable
{
    /** @var    \Opis\Events\RouteCollection */
    protected $collection;

    /** @var    \Opis\Events\Router */
    protected $router;

    /**
     * Constructor
     * 
     * @param   \Opis\Events\RouteCollection|null   $collection (optional)
     */
    public function __construct(RouteCollection $collection = null)
    {
        if ($collection === null) {
            $collection = new RouteCollection();
        }

        $this->collection = $collection;
        $this->router = new Router($this->collection);
    }

    /**
     * Handle an event
     * 
     * @param   string      $event      Event's name
     * @param   callable    $callback   Callback
     * @param   int         $priority   (optional) Event's priority
     * 
     * @return  EventHandler
     */
    public function handle($event, $callback, $priority = 0)
    {
        $handler = new EventHandler(new Pattern($event), $callback);
        $this->collection[] = $handler;
        return $handler->set('priority', $priority);
    }

    /**
     * Emits an event
     * 
     * @param   string  $name       Event's name
     * @param   boolean $cancelable (optional) Cancelable event
     * 
     * @return  Event
     */
    public function emit($name, $cancelable = false)
    {
        return $this->dispatch(new Event($name, $cancelable));
    }

    /**
     * Dispatch an event
     * 
     * @param   Event   $event  Event
     * 
     * @return  Event
     */
    public function dispatch(Event $event)
    {
        $this->collection->sort();
        $handlers = $this->router->route($event);

        foreach ($handlers as $callback) {
            $callback = new Callback($callback);
            $callback->invoke(array($event));

            if ($event->canceled()) {
                break;
            }
        }

        return $event;
    }

    /**
     * Serialize
     * 
     * @return  string
     */
    public function serialize()
    {
        return serialize($this->collection);
    }

    /**
     * Unserialize
     * 
     * @param   string  $data
     */
    public function unserialize($data)
    {
        $this->collection = unserialize($data);
        $this->router = new Router($this->collection);
    }
}
