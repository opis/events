<?php
/* ===========================================================================
 * Copyright 2013-2017 The Opis Project
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

use Opis\Routing\Context;
use Opis\Routing\Route;
use Opis\Routing\RouteCollection;
use Opis\Routing\Router;
use Serializable;

class EventTarget implements Serializable
{
    /** @var    RouteCollection */
    protected $collection;

    /** @var    Router */
    protected $router;

    /**
     * Constructor
     *
     * @param   RouteCollection|null $collection (optional)
     */
    public function __construct(RouteCollection $collection = null)
    {
        if ($collection === null) {
            $collection = new RouteCollection();
        }

        if ($collection->getSortKey() === null) {
            $collection->setSortKey('priority');
        }
        $this->collection = $collection;
    }

    /**
     * Handle an event
     *
     * @param   string $event Event's name
     * @param   callable $callback Callback
     * @param   int $priority (optional) Event's priority
     *
     * @return  Route
     * @throws \Exception
     */
    public function handle(string $event, callable $callback, int $priority = 0): Route
    {
        $handler = new Route($event, $callback);
        $this->collection->addRoute($handler);
        return $handler->set('priority', $priority);
    }

    /**
     * Emits an event
     *
     * @param   string $name Event's name
     * @param   boolean $cancelable (optional) Cancelable event
     *
     * @return  Event
     * @throws \Exception
     */
    public function emit(string $name, bool $cancelable = false): Event
    {
        return $this->dispatch(new Event($name, $cancelable));
    }

    /**
     * Dispatch an event
     *
     * @param   Event $event Event
     *
     * @return  Event
     * @throws \Exception
     */
    public function dispatch(Event $event): Event
    {
        return $this->getRouter()->route(new Context($event->name(), $event));
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
     * @param   string $data
     */
    public function unserialize($data)
    {
        $this->collection = unserialize($data);
    }

    /**
     * @return Router
     */
    protected function getRouter(): Router
    {
        if ($this->router === null) {
            $this->router = new Router($this->collection, new EventDispatcher());
        }

        return $this->router;
    }
}
