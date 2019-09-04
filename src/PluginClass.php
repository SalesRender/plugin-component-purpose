<?php
/**
 * Datetime: 26.06.2019 18:37
 * @author Timur Kasumov aka XAKEPEHOK
 */

namespace Leadvertex\Plugin\Components\Purpose;


use XAKEPEHOK\EnumHelper\EnumHelper;
use XAKEPEHOK\EnumHelper\Exception\OutOfEnumException;

class PluginClass extends EnumHelper
{

    const CLASS_EXPORTER = 'EXPORTER';

    /**
     * @var string
     */
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

    public static function values(): array
    {
        return [
            self::CLASS_EXPORTER,
        ];
    }
}