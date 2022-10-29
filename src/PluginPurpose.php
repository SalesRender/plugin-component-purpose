<?php
/**
 * Datetime: 31.07.2019 16:54
 * @author Timur Kasumov aka XAKEPEHOK
 */

namespace Leadvertex\Plugin\Components\Purpose;


use JsonSerializable;

class PluginPurpose implements JsonSerializable
{

    /**
     * @var PluginClass
     */
    private $class;
    /**
     * @var PluginEntity
     */
    private $entity;

    public function __construct(PluginClass $class, PluginEntity $entity)
    {
        $this->class = $class;
        $this->entity = $entity;
    }

    /**
     * @return PluginClass
     */
    public function getClass(): PluginClass
    {
        return $this->class;
    }

    /**
     * @return PluginEntity
     */
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
}