<?php
/* ===========================================================================
 * Copyright 2020 Zindex Software
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

use Opis\Utils\{RegexBuilder, SortableList};

class EventDispatcher extends SortableList
{
    private RegexBuilder $regexBuilder;

    public function __construct()
    {
        $this->regexBuilder = new RegexBuilder([
            RegexBuilder::SEPARATOR_SYMBOL => '.',
            RegexBuilder::CAPTURE_MODE => RegexBuilder::CAPTURE_LEFT,
        ]);
    }

    public function handle(string $name, callable $callback, int $priority = 0): EventHandler
    {
        $handler = new DefaultEventHandler($this, $name, $callback);
        $this->addItem($handler, $priority);
        return $handler;
    }

    public function getRegexBuilder(): RegexBuilder
    {
        return $this->regexBuilder;
    }

    public function dispatch(Event $event): Event
    {
        if ($event->isCanceled()) {
            return $event;
        }

        $name = $event->name();

        /** @var callable $callback */
        foreach ($this->match($name) as $callback) {
            $callback($event);
            if ($event->isCanceled()) {
                break;
            }
        }

        return $event;
    }

    public function emit(string $name, bool $cancelable = false): Event
    {
        return $this->dispatch(new Event($name, $cancelable));
    }

    private function match(string $name): \Generator
    {
        /** @var DefaultEventHandler $handler */
        foreach ($this->getValues() as $handler) {
            if (preg_match($handler->getRegex(), $name)) {
                yield $handler->getCallback();
            }
        }
    }

    public function __serialize(): array
    {
        return [
            'regexBuilder' => $this->regexBuilder,
            'parent' => parent::__serialize(),
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->regexBuilder = $data['regexBuilder'];
        parent::__unserialize($data['parent']);
    }
}