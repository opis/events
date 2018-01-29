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

use Opis\Pattern\Builder;
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
        parent::__construct(new Builder([
            Builder::SEGMENT_DELIMITER => '.',
            Builder::CAPTURE_MODE => (Builder::CAPTURE_LEFT | Builder::CAPTURE_TRAIL),
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
     * @return RouteCollection|BaseCollection
     * @throws \Exception
     */
    public function addRoute(Route $route): parent
    {
        $this->dirty = true;
        return parent::addRoute($route);
    }

    /**
     * @inheritdoc
     */
    protected function getSerialize()
    {
        return [
            'parent' => parent::getSerialize(),
            'dirty' => $this->dirty,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function setUnserialize($object)
    {
        $this->dirty = $object['dirty'];
        parent::setUnserialize($object['parent']);
    }
}
