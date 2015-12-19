<?php
/* ===========================================================================
 * Opis Project
 * http://opis.io
 * ===========================================================================
 * Copyright 2013-2015 Marius Sarca
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

use InvalidArgumentException;
use Opis\Closure\SerializableClosure;
use Opis\Routing\Collections\RouteCollection as BaseCollection;

class RouteCollection extends BaseCollection
{
    /** @var    boolean */
    protected $dirty = false;

    /**
     * Sort collection
     */
    public function sort()
    {
        if ($this->dirty) {
            uasort($this->collection, function (&$a, &$b) {
                $v1 = $a->get('priority', 0);
                $v2 = $b->get('priority', 0);
                if ($v1 === $v2) {
                    return 0;
                }
                return $v1 < $v2 ? 1 : -1;
            });

            $this->dirty = false;
        }
    }
    /**
     * Add value to vollection
     * 
     * @param   mixed   $offset
     * @param   mixed   $value
     */
    public function offsetSet($offset, $value)
    {
        $this->dirty = true;
        parent::offsetSet($offset, $value);
    }

    /**
     * Check if the type of $value is supported
     * 
     * @throws InvalidArgumentException
     * 
     * @param   \Opis\Events\EventHandler   $value
     */
    protected function checkType($value)
    {
        if (!($value instanceof EventHandler)) {
            throw new InvalidArgumentException('Expected \Opis\Events\EventHandler');
        }
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
            'collection' => $this->collection,
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
        $object = SerializableClosure::unserializeData($data);
        $this->dirty = $object['dirty'];
        $this->collection = $object['collection'];
    }
}
