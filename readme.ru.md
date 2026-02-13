# salesrender/plugin-component-purpose

Компонент классификации назначения плагинов в экосистеме SalesRender. Предоставляет enum-подобные value-объекты, которые категоризируют плагины по их функциональному классу (что плагин делает) и типу сущности (с какими данными работает).

## Установка

```bash
composer require salesrender/plugin-component-purpose
```

## Требования

| Требование | Версия |
|---|---|
| PHP | >= 7.4.0 |
| ext-json | * |
| xakepehok/enum-helper | ^0.1.0 |

## Обзор

Каждый плагин SalesRender объявляет своё **назначение** (purpose) -- комбинацию **класса** (вид выполняемой работы) и **сущности** (область данных, с которой он работает). Назначение задаётся единожды при инициализации плагина в bootstrap и используется платформой для маршрутизации заказов к нужному плагину.

Иерархия классов:

```
PluginPurpose
  +-- PluginClass (abstract)
  |     +-- MacrosPluginClass   (EXPORTER, HANDLER, IMPORTER)
  |     +-- LogisticPluginClass (DELIVERY, FULFILLMENT)
  |     +-- PbxPluginClass      (SIP, WEBHOOK)
  |     +-- ResalePluginClass   (RESALE)
  +-- PluginEntity              (ORDER, UNSPECIFIED)
```

## Основные классы

### PluginPurpose

Контейнер, объединяющий `PluginClass` и `PluginEntity`. Реализует `JsonSerializable`.

| Метод | Сигнатура | Описание |
|---|---|---|
| `__construct` | `__construct(PluginClass $class, PluginEntity $entity)` | Создаёт назначение из пары класс + сущность |
| `getClass` | `getClass(): PluginClass` | Возвращает класс плагина |
| `getEntity` | `getEntity(): PluginEntity` | Возвращает сущность плагина |
| `isEquals` | `isEquals(?self $purpose): bool` | Сравнивает два назначения (должны совпадать и класс, и сущность) |
| `jsonSerialize` | `jsonSerialize(): array` | Сериализует в `['class' => ..., 'entity' => ...]` |
| `factory` | `static factory(array $data): self` | Создаёт экземпляр из ассоциативного массива с ключами `class` и `entity` |

### PluginClass (abstract)

Абстрактный базовый класс для всех категорий плагинов. Наследуется от `EnumHelper`.

| Метод | Сигнатура | Описание |
|---|---|---|
| `__construct` | `__construct(string $entity)` | Создаёт экземпляр; выбрасывает `OutOfEnumException` при невалидном значении |
| `get` | `get(): string` | Возвращает строковое значение класса |
| `isEquals` | `isEquals(?self $class): bool` | Сравнивает два экземпляра класса |
| `factory` | `static factory(string $value): PluginClass` | Автоматически определяет нужный подкласс и создаёт его экземпляр |
| `values` | `static values(): array` | *(абстрактный, определяется в подклассах)* Возвращает массив допустимых значений |

### Конкретные подклассы PluginClass

| Класс | Константы | Описание |
|---|---|---|
| `MacrosPluginClass` | `CLASS_EXPORTER`, `CLASS_HANDLER`, `CLASS_IMPORTER` | Макросы обработки данных: экспорт, обработка (трансформация) или импорт заказов |
| `LogisticPluginClass` | `CLASS_DELIVERY`, `CLASS_FULFILLMENT` | Логистика: интеграция с курьерскими службами или комплексный фулфилмент |
| `PbxPluginClass` | `CLASS_SIP`, `CLASS_WEBHOOK` | Телефония: интеграция по протоколу SIP или отслеживание звонков через webhook |
| `ResalePluginClass` | `CLASS_RESALE` | Интеграция с партнёрской сетью / перепродажа |

### PluginEntity

Определяет сущность данных, с которой работает плагин. Наследуется от `EnumHelper`.

| Метод | Сигнатура | Описание |
|---|---|---|
| `__construct` | `__construct(string $entity)` | Создаёт экземпляр; выбрасывает `OutOfEnumException` при невалидном значении |
| `get` | `get(): string` | Возвращает строковое значение |
| `isEquals` | `isEquals(?self $entity): bool` | Сравнивает два экземпляра сущности |
| `values` | `static values(): array` | Возвращает `['UNSPECIFIED', 'ORDER']` |

| Константа | Значение | Описание |
|---|---|---|
| `ENTITY_ORDER` | `'ORDER'` | Плагин работает с заказами |
| `ENTITY_UNSPECIFIED` | `'UNSPECIFIED'` | Тип сущности не указан |

## Примеры использования

Все приведённые ниже примеры взяты из реальных production-плагинов.

### Плагин-макрос для обработки заказов (handler)

Из `plugin-macros-fields-filler-from-excel/bootstrap.php`:

```php
use SalesRender\Plugin\Components\Purpose\MacrosPluginClass;
use SalesRender\Plugin\Components\Purpose\PluginEntity;
use SalesRender\Plugin\Components\Purpose\PluginPurpose;

$purpose = new PluginPurpose(
    new MacrosPluginClass(MacrosPluginClass::CLASS_HANDLER),
    new PluginEntity(PluginEntity::ENTITY_ORDER)
);
```

### Плагин-макрос для экспорта данных

Из `plugin-macros-excel/bootstrap.php`:

```php
use SalesRender\Plugin\Components\Purpose\MacrosPluginClass;
use SalesRender\Plugin\Components\Purpose\PluginEntity;
use SalesRender\Plugin\Components\Purpose\PluginPurpose;

$purpose = new PluginPurpose(
    new MacrosPluginClass(MacrosPluginClass::CLASS_EXPORTER),
    new PluginEntity(PluginEntity::ENTITY_ORDER)
);
```

### Плагин-макрос для импорта данных

Из `plugin-macros-importer-excel/bootstrap.php`:

```php
use SalesRender\Plugin\Components\Purpose\MacrosPluginClass;
use SalesRender\Plugin\Components\Purpose\PluginEntity;
use SalesRender\Plugin\Components\Purpose\PluginPurpose;

$purpose = new PluginPurpose(
    new MacrosPluginClass(MacrosPluginClass::CLASS_IMPORTER),
    new PluginEntity(PluginEntity::ENTITY_ORDER)
);
```

### Логистический плагин доставки

Из `plugin-logistic-bluedart/bootstrap.php` (назначение передаётся массивом в `Info::config`):

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

### Логистический плагин фулфилмента

Из `plugin-logistic-dir/bootstrap.php`:

```php
use SalesRender\Plugin\Components\Purpose\LogisticPluginClass;
use SalesRender\Plugin\Components\Purpose\PluginEntity;

[
    'class' => LogisticPluginClass::CLASS_FULFILLMENT,
    'entity' => PluginEntity::ENTITY_ORDER,
]
```

### Плагин телефонии (PBX) через webhook

Из `plugin-pbx-sipsim/bootstrap.php`:

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

### Восстановление назначения из сериализованных данных

```php
use SalesRender\Plugin\Components\Purpose\PluginPurpose;

$data = ['class' => 'HANDLER', 'entity' => 'ORDER'];
$purpose = PluginPurpose::factory($data);

// $purpose->getClass()->get()  === 'HANDLER'
// $purpose->getEntity()->get() === 'ORDER'
```

### Сравнение назначений

```php
$purposeA = new PluginPurpose(
    new MacrosPluginClass(MacrosPluginClass::CLASS_HANDLER),
    new PluginEntity(PluginEntity::ENTITY_ORDER)
);

$purposeB = PluginPurpose::factory(['class' => 'HANDLER', 'entity' => 'ORDER']);

$purposeA->isEquals($purposeB); // true
$purposeA->isEquals(null);      // false
```

## Справочник API

### Namespace

```
SalesRender\Plugin\Components\Purpose
```

### Сводная таблица констант

| Класс | Константа | Значение |
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

### Сериализация в JSON

`PluginPurpose` реализует `JsonSerializable`. Формат вывода:

```json
{
    "class": "HANDLER",
    "entity": "ORDER"
}
```

## Зависимости

| Пакет | Версия | Назначение |
|---|---|---|
| `xakepehok/enum-helper` | ^0.1.0 | Базовый класс для enum-подобных value-объектов с валидацией |

## Смотрите также

- [salesrender/plugin-component-form](https://github.com/SalesRender/plugin-component-form) -- система форм для конфигурации настроек плагина
- [salesrender/plugin-component-info](https://github.com/SalesRender/plugin-component-info) -- метаданные плагина (название, описание, разработчик); использует `PluginPurpose` в `Info::config()`
- [salesrender/plugin-component-settings](https://github.com/SalesRender/plugin-component-settings) -- хранение настроек; сохраняет данные форм, заполненных пользователями
