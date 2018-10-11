<?php
/* ===========================================================================
 * Copyright 2018 Zindex Software
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
use Opis\Routing\{
    Context, Route, Router
};

class EventDispatcher implements Serializable
{
    /** @var RouteCollection */
    protected $collection;

    /** @var Router */
    protected $router;

    /**
     * @param RouteCollection|null $collection
     */
    public function __construct(RouteCollection $collection = null)
    {
        if ($collection === null) {
            $collection = new RouteCollection();
        }

        $this->collection = $collection;
    }

    /**
     * Handle an event
     *
     * @param string $event Event's name
     * @param callable $callback Callback
     * @param int $priority (optional) Event's priority
     *
     * @return Route
     */
    public function handle(string $event, callable $callback, int $priority = 0): Route
    {
        return $this->collection->createRoute($event, $callback)->set('priority', $priority);
    }

    /**
     * Emits an event
     *
     * @param string $name Event's name
     * @param boolean $cancelable (optional) Cancelable event
     *
     * @return Event
     */
    public function emit(string $name, bool $cancelable = false): Event
    {
        return $this->dispatch(new Event($name, $cancelable));
    }

    /**
     * Dispatch an event
     *
     * @param Event $event Event
     *
     * @return Event
     */
    public function dispatch(Event $event): Event
    {
        return $this->getRouter()->route(new Context($event->name(), $event));
    }

    /**
     * Serialize
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->collection);
    }

    /**
     * Unserialize
     *
     * @param string $data
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
            $this->router = new Router($this->collection, new Dispatcher());
        }

        return $this->router;
    }
}
