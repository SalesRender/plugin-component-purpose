<?php
/**
 * Datetime: 26.06.2019 18:37
 * @author Timur Kasumov aka XAKEPEHOK
 */

namespace SalesRender\Plugin\Components\Purpose;


use XAKEPEHOK\EnumHelper\EnumHelper;
use XAKEPEHOK\EnumHelper\Exception\OutOfEnumException;

class PluginEntity extends EnumHelper
{

    const string ENTITY_UNSPECIFIED = 'UNSPECIFIED';
    const string ENTITY_ORDER = 'ORDER';

    private string $entity;

    /**
     * PluginEntity constructor.
     * @throws OutOfEnumException
     */
    public function __construct(string $entity)
    {
        self::guardValidValue($entity);
        $this->entity = $entity;
    }

    public function get(): string
    {
        return $this->entity;
    }

    public function isEquals(?self $entity): bool
    {
        if ($entity === null) {
            return false;
        }
        return $this->get() === $entity->get();
    }

    public static function values(): array
    {
        return [
            self::ENTITY_UNSPECIFIED,
            self::ENTITY_ORDER,
        ];
    }
}