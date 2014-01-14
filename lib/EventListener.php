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

class EventListener
{
    protected static $listeners = array();
    
    protected static $cache = array();
    
    protected function __construct()
    {
        
    }
    
    protected static function buildCache($name)
    {
        if(!isset(static::$cache[$name]))
        {
            static::$cache[$name] = array();
            
            foreach(static::$listeners as &$listener)
            {
                if(preg_match($listener['match'], $name))
                {
                    static::$cache[$name][] = $listener;
                }
            }
            
            uasort(static::$cache[$name], function(&$a, &$b){
                if($a['priority'] === $b['priority'])
                {
                    return 0;
                }
                return $a['priority'] < $b['priority'] ? 1 : -1;
            });
        }
    }
    
    protected static function clearCache($name)
    {
        $keys = array_keys(static::$cache);
        
        foreach($keys as $key)
        {
            if(preg_match($name, $key))
            {
                unset(static::$cache[$key]);
            }
        }
    }
    
    
    public static function add($name, Closure $callback, $priority = 0)
    {
        $match = '`^' . str_replace('\*','.*', preg_quote($name, '`')) . '$`u';
        
        static::$listeners[] = array(
            'match' => $match,
            'callback' => $callback,
            'priority' => $priority,
        );
        
        static::clearCache($match);
    }
    
    public static function &get($name)
    {
        static::buildCache($name);
        
        return static::$cache[$name];
    }
    
    public static function has($name)
    {
        static::buildCache($name);
        return isset(static::$cache[$name]) && !empty(static::$cache[$name]);
    }
    
    public static function remove($name)
    {
        unset(static::$cache[$name]);
        
        $keys = array_keys(static::$listeners);
        
        foreach($keys as $key)
        {
            if(preg_match(static::$listeners[$key]['match'], $name))
            {
                unset(static::$listeners[$key]);
            }
        }
    }
    
    public static function clear()
    {
        static::$listeners = array();
        static::$cache = array();
    }
    
}