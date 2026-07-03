<?php
/**
 * Datetime: 31.07.2019 16:54
 * @author Timur Kasumov aka XAKEPEHOK
 */

namespace SalesRender\Plugin\Components\Purpose;


use JsonSerializable;
use XAKEPEHOK\EnumHelper\Exception\OutOfEnumException;

class PluginPurpose implements JsonSerializable
{

    private PluginClass $class;

    private PluginEntity $entity;

    public function __construct(PluginClass $class, PluginEntity $entity)
    {
        $this->class = $class;
        $this->entity = $entity;
    }

    public function getClass(): PluginClass
    {
        return $this->class;
    }

    public function getEntity(): PluginEntity
    {
        return $this->entity;
    }

    public function isEquals(?self $purpose): bool
    {
        if ($purpose === null) {
            return false;
        }

        $isSameClass = $this->class->isEquals($purpose->getClass());
        $isSameEntity = $this->entity->isEquals($purpose->getEntity());
        return $isSameClass && $isSameEntity;
    }

    public function jsonSerialize(): array
    {
        return [
            'class' => $this->class->get(),
            'entity' => $this->entity->get(),
        ];
    }

    /**
     * @throws OutOfEnumException
     */
    public static function factory(array $data): self
    {
        return new PluginPurpose(
            PluginClass::factory($data['class'] ?? ''),
            new PluginEntity($data['entity'] ?? '')
        );
    }
}