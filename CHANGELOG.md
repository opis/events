# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## v5.0.x-dev
### Added
- Support for PHP 7.0.x
- Source files were moved from `lib` to `src`
- `Opis\Events\EventDispatcher` class

### Removed
- Support for PHP 5.x
- `Opis\Events\Router` class
- `Opis\Events\EventHandler` class

### Changed
- Updated `opis/routing` dependency to version `5.0.x-dev`
- All classes were updated in order to reflect changes
- `Opis\Events\Event` is now extending `Opis\Routing\Context`

### Fixed
- Nothing

## v4.1.1 - 2016.01.16
### Added
- Nothing

### Removed
- Nothing

### Changed
- Nothing

### Fixed
- Fixed a bug in `EventHanlder::getCompiler`

## v4.1.0 - 2016.01.16
### Added
- Nothing

### Removed
- Nothing

### Changed
- Updated `opis/routing` library dependency to version `^4.1.0`

### Fixed
- Fixed sorting

## v4.0.0 - 2016.01.14
### Added
- Tests
- `Opis\Events\EventTarget::emit` method

### Removed
- `branch-alias` property from `composer.json` file
- `Opis\Events\Event::dispatch` method
- `Opis\Events\Event::eventTarget` method
- `Opis\Events\EventTarget` argument form `Opis\Events\Event` 
- `Opis\Events\EventTarget::create` method

### Changed
- Updated `opis/routing` library dependency to version `4.0.*`
- Changed the way events are dispatched

### Fixed
- Fixed CS

## v3.0.0 - 2015.07.31
### Added
- Nothing

### Removed
- Nothing

### Changed
- Updated `opis/routing` library dependency to version `3.0.*`
- Updated all classes to reflect `opis/routing`'s changes

### Fixed
- Nothing

## v2.5.0 - 2015.03.20
### Added
- Nothing

### Removed
- Nothing

### Changed
- Updated `opis/routing` library dependency to version `2.5.*`

### Fixed
- Nothing

## v2.4.1 - 2014.11.25
### Added
- Autoload file

### Removed
- Nothing

### Changed
- Nothing

### Fixed
- Nothing

## v2.4.0 - 2014.10.23
### Added
- Nothing

### Removed
- Nothing

### Changed
- Updated `opis/routing` dependency to version `2.4.*`

### Fixed
- Nothing

## v2.3.1 - 2014.06.11
### Added
- Nothing

### Removed
- Nothing

### Changed
- Nothing

### Fixed
- Fixed a bug in `Opis\Events\EventHandler`.

## v2.3.0 - 2014.06.11
### Added
- Nothing

### Removed
- Nothing

### Changed
- Updated `opis/routing` library dependency to version `2.3.*`
- Updated `Opis\Events\EventHandler` to reflect changes in `opis/routing`

### Fixed
- Nothing

## v2.2.0 - 2014.06.04
### Added
- Changelog file

### Removed
- Nothing

### Changed
- Updated `opis/routing` dependency to version `2.2.*`

### Fixed
- Nothing
