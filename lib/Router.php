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

use Opis\Routing\Path;
use Opis\Routing\PathFilter;
use Opis\Routing\Router as BaseRouter;
use Opis\Routing\Collections\FilterCollection;

class Router extends BaseRouter
{

    /**
     * Constructor
     * 
     * @param   \Opis\Events\RouteCollection    $routes
     */
    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
        $this->filters = new FilterCollection();
        $this->filters[] = new PathFilter();
    }

    /**
     * Route
     * 
     * @param   Path    $path
     * 
     * @return  mixed
     */
    public function route(Path $path)
    {
        $result = array();

        foreach ($this->routes as $route) {
            if ($this->pass($path, $route)) {
                $result[] = $route->getAction();
            }
        }
        
        return $result;
    }
}
