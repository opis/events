Opis Events
===========
[![Build Status](https://travis-ci.org/opis/events.svg?branch=master)](https://travis-ci.org/opis/events)
[![Latest Stable Version](https://poser.pugx.org/opis/events/version.png)](https://packagist.org/packages/opis/events)
[![Latest Unstable Version](https://poser.pugx.org/opis/events/v/unstable.png)](//packagist.org/packages/opis/events)
[![License](https://poser.pugx.org/opis/events/license.png)](https://packagist.org/packages/opis/events)

Events library
--------------
**Opis Events** is a library that can be used for dispatching and intercepting events.

The library is built on top of the [Opis Routing](https://www.opis.io/routing) library,
 and provides a full range of features like filters and priorities.

## Documentation

Examples and documentation can be found [here](https://www.opis.io/events).

## License

**Opis Events** is licensed under the [Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0).

## Requirements

* PHP 7.0.* or higher
* [Opis Routing](https://www.opis.io/routing) 5.0.x-dev

## Installation

This library is available on [Packagist](https://packagist.org/packages/opis/events)
 and can be installed from a command line interface by using [Composer](http://getcomposer.org).

```bash
composer require opis/events
```

Or you could directly reference it into your `composer.json` file as a dependency

```json
{
    "require": {
        "opis/events": "5.0.x-dev"
    }
}
```

If you are unable to use [Composer](http://getcomposer.org) you can download the
[tar.gz](https://github.com/opis/events/archive/master.tar.gz) or the [zip](https://github.com/opis/events/archive/master.zip)
archive file, extract the content of the archive and include de `autoload.php` file into your project. 
