# salesrender/plugin-component-purpose

Plugin purpose classification component for the SalesRender plugin ecosystem. Provides enum-like value objects that categorize plugins by their functional class (what a plugin does) and the entity type it operates on (what data it handles).

## Installation

```bash
composer require salesrender/plugin-component-purpose
```

## Requirements

| Requirement | Version |
|---|---|
| PHP | >= 7.4.0 |
| ext-json | * |
| xakepehok/enum-helper | ^0.1.0 |

## Overview

Every SalesRender plugin declares its **purpose** -- a combination of a **class** (the kind of work it performs) and an **entity** (the data scope it operates on). The purpose is set once, during plugin bootstrap, and is used by the platform to route orders to the correct plugin.

The hierarchy is:

```
PluginPurpose
  +-- PluginClass (abstract)
  |     +-- MacrosPluginClass   (EXPORTER, HANDLER, IMPORTER)
  |     +-- LogisticPluginClass (DELIVERY, FULFILLMENT)
  |     +-- PbxPluginClass      (SIP, WEBHOOK)
  |     +-- ResalePluginClass   (RESALE)
  +-- PluginEntity              (ORDER, UNSPECIFIED)
```

## Key Classes

### PluginPurpose

Container that combines a `PluginClass` and a `PluginEntity`. Implements `JsonSerializable`.

| Method | Signature | Description |
|---|---|---|
| `__construct` | `__construct(PluginClass $class, PluginEntity $entity)` | Creates a new purpose from a class and entity pair |
| `getClass` | `getClass(): PluginClass` | Returns the plugin class |
| `getEntity` | `getEntity(): PluginEntity` | Returns the plugin entity |
| `isEquals` | `isEquals(?self $purpose): bool` | Compares two purposes for equality (both class and entity must match) |
| `jsonSerialize` | `jsonSerialize(): array` | Serializes to `['class' => ..., 'entity' => ...]` |
| `factory` | `static factory(array $data): self` | Creates an instance from an associative array with `class` and `entity` keys |

### PluginClass (abstract)

Abstract base for all plugin class categories. Extends `EnumHelper`.

| Method | Signature | Description |
|---|---|---|
| `__construct` | `__construct(string $entity)` | Creates an instance; throws `OutOfEnumException` if the value is invalid |
| `get` | `get(): string` | Returns the string value of the class |
| `isEquals` | `isEquals(?self $class): bool` | Compares two class instances for equality |
| `factory` | `static factory(string $value): PluginClass` | Automatically determines the correct subclass and instantiates it |
| `values` | `static values(): array` | *(abstract, defined in subclasses)* Returns all allowed values |

### Concrete PluginClass Subclasses

| Class | Constants | Description |
|---|---|---|
| `MacrosPluginClass` | `CLASS_EXPORTER`, `CLASS_HANDLER`, `CLASS_IMPORTER` | Data processing macros: export, handle (transform), or import orders |
| `LogisticPluginClass` | `CLASS_DELIVERY`, `CLASS_FULFILLMENT` | Logistics: delivery carrier integration or end-to-end fulfillment |
| `PbxPluginClass` | `CLASS_SIP`, `CLASS_WEBHOOK` | Telephony: SIP protocol integration or webhook-based call tracking |
| `ResalePluginClass` | `CLASS_RESALE` | Resale / partner network integration |

### PluginEntity

Defines the data entity the plugin operates on. Extends `EnumHelper`.

| Method | Signature | Description |
|---|---|---|
| `__construct` | `__construct(string $entity)` | Creates an instance; throws `OutOfEnumException` if the value is invalid |
| `get` | `get(): string` | Returns the string value |
| `isEquals` | `isEquals(?self $entity): bool` | Compares two entity instances for equality |
| `values` | `static values(): array` | Returns `['UNSPECIFIED', 'ORDER']` |

| Constant | Value | Description |
|---|---|---|
| `ENTITY_ORDER` | `'ORDER'` | Plugin operates on orders |
| `ENTITY_UNSPECIFIED` | `'UNSPECIFIED'` | Entity type is not specified |

## Usage Examples

All examples below are taken from real production plugins.

### Macros handler plugin (order processing)

From `plugin-macros-fields-filler-from-excel/bootstrap.php`:

```php
use SalesRender\Plugin\Components\Purpose\MacrosPluginClass;
use SalesRender\Plugin\Components\Purpose\PluginEntity;
use SalesRender\Plugin\Components\Purpose\PluginPurpose;

$purpose = new PluginPurpose(
    new MacrosPluginClass(MacrosPluginClass::CLASS_HANDLER),
    new PluginEntity(PluginEntity::ENTITY_ORDER)
);
```

### Macros exporter plugin (data export)

From `plugin-macros-excel/bootstrap.php`:

```php
use SalesRender\Plugin\Components\Purpose\MacrosPluginClass;
use SalesRender\Plugin\Components\Purpose\PluginEntity;
use SalesRender\Plugin\Components\Purpose\PluginPurpose;

$purpose = new PluginPurpose(
    new MacrosPluginClass(MacrosPluginClass::CLASS_EXPORTER),
    new PluginEntity(PluginEntity::ENTITY_ORDER)
);
```

### Macros importer plugin (data import)

From `plugin-macros-importer-excel/bootstrap.php`:

```php
use SalesRender\Plugin\Components\Purpose\MacrosPluginClass;
use SalesRender\Plugin\Components\Purpose\PluginEntity;
use SalesRender\Plugin\Components\Purpose\PluginPurpose;

$purpose = new PluginPurpose(
    new MacrosPluginClass(MacrosPluginClass::CLASS_IMPORTER),
    new PluginEntity(PluginEntity::ENTITY_ORDER)
);
```

### Logistic delivery plugin

From `plugin-logistic-bluedart/bootstrap.php` (note: this plugin passes purpose as an array to `Info::config`):

```php
use SalesRender\Plugin\Components\Purpose\LogisticPluginClass;
use SalesRender\Plugin\Components\Purpose\PluginEntity;

Info::config(
    new PluginType(PluginType::LOGISTIC),
    fn() => 'BlueDart',
    fn() => 'Most-used eCommerce logistics and shipping software solution',
    [
        'class' => LogisticPluginClass::CLASS_DELIVERY,
        'entity' => PluginEntity::ENTITY_ORDER,
    ],
    new Developer('SalesRender', 'support@salesrender.com', 'salesrender.com')
);
```

### Logistic fulfillment plugin

From `plugin-logistic-dir/bootstrap.php`:

```php
use SalesRender\Plugin\Components\Purpose\LogisticPluginClass;
use SalesRender\Plugin\Components\Purpose\PluginEntity;

[
    'class' => LogisticPluginClass::CLASS_FULFILLMENT,
    'entity' => PluginEntity::ENTITY_ORDER,
]
```

### PBX webhook plugin

From `plugin-pbx-sipsim/bootstrap.php`:

```php
use SalesRender\Plugin\Components\Purpose\PbxPluginClass;
use SalesRender\Plugin\Components\Purpose\PluginEntity;

Info::config(
    new PluginType(PluginType::PBX),
    fn() => 'SipSim',
    fn() => 'SipSim telephony provider',
    [
        'class' => PbxPluginClass::CLASS_WEBHOOK,
        'entity' => PluginEntity::ENTITY_UNSPECIFIED,
    ],
    new Developer('SalesRender', 'support@salesrender.com', 'salesrender.com')
);
```

### Restoring purpose from serialized data

```php
use SalesRender\Plugin\Components\Purpose\PluginPurpose;

$data = ['class' => 'HANDLER', 'entity' => 'ORDER'];
$purpose = PluginPurpose::factory($data);

// $purpose->getClass()->get()  === 'HANDLER'
// $purpose->getEntity()->get() === 'ORDER'
```

### Comparing purposes

```php
$purposeA = new PluginPurpose(
    new MacrosPluginClass(MacrosPluginClass::CLASS_HANDLER),
    new PluginEntity(PluginEntity::ENTITY_ORDER)
);

$purposeB = PluginPurpose::factory(['class' => 'HANDLER', 'entity' => 'ORDER']);

$purposeA->isEquals($purposeB); // true
$purposeA->isEquals(null);      // false
```

## API Reference

### Namespace

```
SalesRender\Plugin\Components\Purpose
```

### All Constants Summary

| Class | Constant | Value |
|---|---|---|
| `MacrosPluginClass` | `CLASS_EXPORTER` | `'EXPORTER'` |
| `MacrosPluginClass` | `CLASS_HANDLER` | `'HANDLER'` |
| `MacrosPluginClass` | `CLASS_IMPORTER` | `'IMPORTER'` |
| `LogisticPluginClass` | `CLASS_DELIVERY` | `'DELIVERY'` |
| `LogisticPluginClass` | `CLASS_FULFILLMENT` | `'FULFILLMENT'` |
| `PbxPluginClass` | `CLASS_SIP` | `'SIP'` |
| `PbxPluginClass` | `CLASS_WEBHOOK` | `'WEBHOOK'` |
| `ResalePluginClass` | `CLASS_RESALE` | `'RESALE'` |
| `PluginEntity` | `ENTITY_ORDER` | `'ORDER'` |
| `PluginEntity` | `ENTITY_UNSPECIFIED` | `'UNSPECIFIED'` |

### JSON Serialization

`PluginPurpose` implements `JsonSerializable`. The output format is:

```json
{
    "class": "HANDLER",
    "entity": "ORDER"
}
```

## Dependencies

| Package | Version | Purpose |
|---|---|---|
| `xakepehok/enum-helper` | ^0.1.0 | Base class for enum-like value objects with guard validation |

## See Also

- [salesrender/plugin-component-form](https://github.com/SalesRender/plugin-component-form) -- form system used to configure plugin settings
- [salesrender/plugin-component-info](https://github.com/SalesRender/plugin-component-info) -- plugin metadata (name, description, developer); uses `PluginPurpose` in `Info::config()`
- [salesrender/plugin-component-settings](https://github.com/SalesRender/plugin-component-settings) -- settings persistence; stores form data submitted by users
