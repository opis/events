<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013 Marius Sarca
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
use Opis\Events\Event;

class Listener
{
    protected static $listeners = array();
    
    protected function __construct()
    {
        
    }
    
    protected static function sort($name)
    {
        if(!static::$listeners[$name]['sorted'])
        {
            $list = &static::$listeners[$name]['list'];
            uasort($list, function(&$a, &$b){
                if($a['priority'] === $b['priority'])
                {
                    return 0;
                }
                return $a['priority'] < $b['priority'] ? 1 : -1;
            });
            static::$listeners[$name]['sorted'] = true;
        }
    }
    
    protected static function create($name)
    {
        static::$listeners[$name] = array(
            'sorted' => true,
            'list' => array(),
        );
    }
    
    public static function add($name, Closure $callback, $priority = 0)
    {
        if(!static::has($name))
        {
            static::create($name);
        }
        
        static::$listeners[$name]['sorted'] = false;
        
        static::$listeners[$name]['list'][] = array(
            'priority' => $priority,
            'callback' => $callback,
        );
        
    }
    
    public static function &get($name)
    {
        if(!static::has($name))
        {
            static::create($name);
        }
        
        static::sort($name);
        
        return static::$listeners[$name]['list'];
    }
    
    public static function remove($name)
    {
        if(static::has($name))
        {
            static::$listeners[$name]['sorted'] = true;
            static::$listeners[$name]['list'] = array();
        }
    }
    
    public static function has($name)
    {
        return isset(static::$listeners[$name]);
    }
    
    public static function clear()
    {
        static::$listeners = array();
    }
    
}