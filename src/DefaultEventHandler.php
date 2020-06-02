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

use Opis\Utils\RegexBuilder;

final class DefaultEventHandler implements EventHandler
{
    private EventDispatcher $dispatcher;
    private string $pattern;
    /** @var callable */
    private $callback;
    private ?string $regex = null;
    private array $placeholders = [];

    public function __construct(EventDispatcher $dispatcher, string $pattern, callable $callback)
    {
        $this->dispatcher = $dispatcher;
        $this->pattern = $pattern;
        $this->callback = $callback;
    }

    public function getRegex(): string
    {
        if ($this->regex === null) {
            $this->regex = $this->dispatcher->getRegexBuilder()->getRegex($this->pattern, $this->placeholders);
        }

        return $this->regex;
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function where(string $name, string $regex): self
    {
        $this->regex = null;
        $this->placeholders[$name] = $regex;
        return $this;
    }

    /**
     * @param string $name
     * @param string[] $values
     * @return $this
     */
    public function whereIn(string $name, array $values): self
    {
        if (empty($values)) {
            return $this;
        }

        $delimiter = $this->dispatcher->getRegexBuilder()->getOptions()[RegexBuilder::REGEX_DELIMITER];

        $value = implode('|', array_map(function ($value) use ($delimiter) {
            return preg_quote($value, $delimiter);
        }, $values));

        return $this->where($name, $value);
    }

    public function __serialize(): array
    {
        return [
            'dispatcher' => $this->dispatcher,
            'pattern' => $this->pattern,
            'placeholders' => $this->placeholders,
            'regex' => $this->getRegex(),
            'callback' => $this->callback
        ];
    }
}