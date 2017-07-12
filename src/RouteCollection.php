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

use Opis\Closure\SerializableClosure;
use Opis\Routing\Compiler;
use Opis\Routing\Route;
use Opis\Routing\RouteCollection as BaseCollection;

class RouteCollection extends BaseCollection
{
    /** @var    boolean */
    protected $dirty = false;

    /**
     * RouteCollection constructor
     */
    public function __construct()
    {
        parent::__construct(new Compiler([
            Compiler::TAG_SEPARATOR => '.',
            Compiler::CAPTURE_MODE => (Compiler::CAPTURE_LEFT | Compiler::CAPTURE_TRAIL),
        ]));
    }

    /**
     * Sort event handlers
     */
    public function sort()
    {
        if(!$this->dirty){
            return;
        }

        $this->regex = null;
        uasort($this->routes, function(Route $a, Route $b){
            return $a->get('priority', 0) <= $b->get('priority', 0) ? 1 : -1;
        });
        $this->dirty = false;
    }

    /**
     * @param Route $route
     * @return RouteCollection
     */
    public function addRoute(Route $route): parent
    {
        $this->dirty = true;
        return parent::addRoute($route);
    }

    /**
     * Serialize
     * @return  string
     */
    public function serialize()
    {
        SerializableClosure::enterContext();

        $object = serialize(array(
            'dirty' => $this->dirty,
            'parent' => parent::serialize(),
        ));

        SerializableClosure::exitContext();

        return $object;
    }

    /**
     * Unserialize
     * 
     * @param   string  $data
     */
    public function unserialize($data)
    {
        $object = unserialize($data);
        $this->dirty = $object['dirty'];
        parent::unserialize($object['parent']);
    }
}