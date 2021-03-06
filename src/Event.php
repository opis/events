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

class Event
{
    /** @var  string  Event's name */
    private $eventName;

    /** @var boolean Cancelable event */
    private $cancelable;

    /** @var boolean Canceled event */
    private $isCanceled = false;

    /**
     * Constructor
     *
     * @param   string $name Event's name
     * @param   boolean $cancelable (optional) Cancelable event
     */
    public function __construct(string $name, bool $cancelable = false)
    {
        $this->eventName = $name;
        $this->cancelable = $cancelable;
    }

    /**
     * Event's name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->eventName;
    }

    /**
     * Tells if event was canceled
     *
     * @return boolean
     */
    public function canceled(): bool
    {
        return $this->isCanceled;
    }

    /**
     * Cancel this event
     */
    public function stop()
    {
        if ($this->cancelable === true) {
            $this->isCanceled = true;
        }
    }

    /**
     * Tells if event is cancellable
     *
     * @return bool
     */
    public function isCancelable(): bool
    {
        return $this->cancelable;
    }
}
