<?php
/**
 * Created for plugin-component-purpose
 * Date: 28.12.2023
 * @author: Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Plugin\Components\Purpose;

use XAKEPEHOK\EnumHelper\EnumHelper;
use XAKEPEHOK\EnumHelper\Exception\OutOfEnumException;

abstract class PluginClass extends EnumHelper
{

    /** @var string */
    private $class;

    /**
     * PluginClass constructor.
     * @param string $entity
     * @throws OutOfEnumException
     */
    public function __construct(string $entity)
    {
        self::guardValidValue($entity);
        $this->class = $entity;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->class;
    }

    public function isEquals(?self $class): bool
    {
        if ($class === null) {
            return false;
        }
        return $this->get() === $class->get();
    }

}