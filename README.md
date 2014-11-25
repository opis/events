Opis Events
===========
[![Latest Stable Version](https://poser.pugx.org/opis/events/version.png)](https://packagist.org/packages/opis/events)
[![Latest Unstable Version](https://poser.pugx.org/opis/events/v/unstable.png)](//packagist.org/packages/opis/events)
[![License](https://poser.pugx.org/opis/events/license.png)](https://packagist.org/packages/opis/events)

Events library
--------------
**Opis Events** is a library that can be used for dispatching and intercepting events. This library is
builded on top of the **Opis Routing** library and provides a full range of features like filters and
events' priorities. 

### License

**Opis HTTP Routing** is licensed under the [Apache License, Version 2.0](http://www.apache.org/licenses/LICENSE-2.0). 

### Requirements

* PHP 5.3.* or higher
* [Opis Routing](http://www.opis.io/routing) 2.4.*

### Installation

This library is available on [Packagist](https://packagist.org/packages/opis/events) and can be installed using [Composer](http://getcomposer.org).

```json
{
    "require": {
        "opis/events": "2.4.*"
    }
}
```

If you are unable to use [Composer](http://getcomposer.org) you can download the
[tar.gz](https://github.com/opis/events/archive/2.4.1.tar.gz) or the [zip](https://github.com/opis/events/archive/2.4.1.zip)
archive file, extract the content of the archive and include de `autoload.php` file into your project. 

```php

require_once 'path/to/events-2.4.1/autoload.php';

```

### Documentation

Examples and documentation can be found at http://opis.io/events .