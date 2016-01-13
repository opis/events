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

use Opis\Events\EventTarget;

class EventsTest extends PHPUnit_Framework_TestCase
{
    protected $target;
    
    public function setUp()
    {
        $this->target = new EventTarget();
    }

    public function testBasicEvent()
    {
        $this->target->handle('ok', function($event){
            print $event->name();
        });
        
        $this->expectOutputString('ok');
        $this->target->emit('ok');
    }
    
    public function testParams()
    {
        $this->target->handle('foo.{bar}', function($event){
            print $event->name();
        })->where('bar', 'x');
        
        $this->expectOutputString('foo.x');
        $this->target->emit('foo.y');
        $this->target->emit('foo.x');
    }
}
