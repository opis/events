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

use RuntimeException;
use Opis\Events\Contracts\EventTargetInterface;

class Event
{
    protected $eventName;
    
    protected $cancelable;
    
    protected $isCanceled = false;
    
    protected $eventTarget;
    
    public function __construct(EventTargetInterface $target, $name, $cancelable = false)
    {
        $this->eventTarget = $target;
        $this->eventName = $name;
        $this->cancelable = $cancelable;
    }
    
    public function name()
    {
        return $this->eventName;
    }
    
    public function canceled()
    {
        return $this->isCanceled;
    }
    
    public function stop()
    {
        if($this->cancelable === true)
        {
            $this->isCanceled = true;
        }
    }
    
    public function dispatch()
    {
        return $this->eventTarget->dispatch($this);
    }
    
}