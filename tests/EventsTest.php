<?php
/* ===========================================================================
 * Copyright 2013-2018 The Opis Project
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

namespace Opis\Events\Test;

use Opis\Events\Event;
use Opis\Events\EventDispatcher;
use PHPUnit\Framework\TestCase;

class EventsTest extends TestCase
{
    /** @var  EventDispatcher */
    protected $target;

    public function setUp()
    {
        $this->target = new EventDispatcher();
    }

    public function testBasicEvent()
    {
        $this->target->handle('ok', function (Event $event) {
            print $event->name();
        });

        $this->expectOutputString('ok');
        $this->target->emit('ok');
    }

    public function testParams()
    {
        $this->target->handle('foo.{bar}', function (Event $event) {
            print $event->name();
        })->where('bar', 'x');

        $this->expectOutputString('foo.x');
        $this->target->emit('foo.y');
        $this->target->emit('foo.x');
    }

    public function testParams2()
    {
        $this->target->handle('foo.{bar}', function (Event $event) {
            print $event->name();
        })->where('bar', 'x|y');

        $this->expectOutputString('foo.yfoo.x');
        $this->target->emit('foo.y');
        $this->target->emit('foo.x');
    }

    public function testParams3()
    {
        $this->target->handle('foo.{bar=x|y}', function (Event $event) {
            print $event->name();
        });

        $this->expectOutputString('foo.yfoo.x');
        $this->target->emit('foo.y');
        $this->target->emit('foo.x');
    }

    public function testDefaultPriority()
    {
        $this->target->handle('foo', function () {
            print "foo";
        });

        $this->target->handle('foo', function () {
            print "bar";
        });

        $this->expectOutputString("barfoo");
        $this->target->emit('foo');
    }

    public function testExplicitPriority()
    {
        $this->target->handle('foo', function () {
            print "foo";
        }, 1);

        $this->target->handle('foo', function () {
            print "bar";
        });

        $this->expectOutputString("foobar");
        $this->target->emit('foo');
    }

    public function testExplicitPriorityEqual()
    {
        $this->target->handle('foo', function () {
            print "foo";
        }, 1);

        $this->target->handle('foo', function () {
            print "bar";
        }, 1);

        $this->expectOutputString("barfoo");
        $this->target->emit('foo');
    }

    public function testDefaultPriorityNotCancel()
    {
        $this->target->handle('foo', function () {
            print "foo";
        });

        $this->target->handle('foo', function (Event $event) {
            $event->stop();
            print "bar";
        });

        $this->expectOutputString("barfoo");
        $this->target->emit('foo', false);
    }

    public function testDefaultPriorityCancel()
    {
        $this->target->handle('foo', function () {
            print "foo";
        });

        $this->target->handle('foo', function (Event $event) {
            $event->stop();
            print "bar";
        });

        $this->expectOutputString("bar");
        $this->target->emit('foo', true);
    }

    public function testDefaultPriorityCancel2()
    {
        $this->target->handle('foo', function () {
            print "foo";
        });

        $this->target->handle('foo', function (Event $event) {
            $event->stop();
            print "bar";
        });

        $this->target->handle('f{=o{2}}', function () {
            print "baz";
        });

        $this->expectOutputString("bazbar");
        $this->target->emit('foo', true);
    }

    public function testSerializable()
    {
        $this->target->handle('foo', function () {
            print "foo";
        });

        $this->target->handle('foo', function (Event $event) {
            $event->stop();
            print "bar";
        });

        $this->target->handle('foo', function () {
            print 'baz';
        });

        $target = unserialize(serialize($this->target));

        $this->expectOutputString("bazbar");
        $target->emit('foo', true);
    }

    public function testDispatch()
    {
        $this->target->handle('foo', function ($event) {
            /** @noinspection PhpUndefinedMethodInspection */
            print $event->data();
        });

        $event = new class("foo", false, "test-data") extends Event {
            protected $data;

            public function __construct(string $name, bool $cancelable = false, string $data = '')
            {
                $this->data = $data;
                parent::__construct($name, $cancelable);
            }

            public function data(): string
            {
                return $this->data;
            }
        };

        $this->expectOutputString("test-data");
        $this->target->dispatch($event);
    }

    public function testDispatch2()
    {
        $this->target->handle('foo', function () {
            print 'ok';
        });

        $event = new Event("foo", true);
        $event->stop();

        $this->expectOutputString("");
        $this->target->dispatch($event);
    }
}
