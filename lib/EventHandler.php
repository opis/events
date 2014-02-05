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

use Opis\Events\Contracts\EventHandlerInterface;
use Opis\Events\Contracts\EventInterface;

class EventHandler implements EventHandlerInterface
{
    
    protected $callback;
    
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }
    
    public function handle(EventInterface $event)
    {
       $this->callback($event);
    }
    
    public function serialize()
    {
        return serialize($this->callback);
    }
    
    public function unserialize($data)
    {
        $this->callback = unserialize($data);
    }
}