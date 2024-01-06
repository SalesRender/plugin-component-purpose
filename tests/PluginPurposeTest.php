<?php
/**
 * Created for plugin-component-purpose
 * Date: 06.01.2024
 * @author: Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Plugin\Components\Purpose;

use OutOfBoundsException;
use PHPUnit\Framework\TestCase;
use XAKEPEHOK\EnumHelper\Exception\OutOfEnumException;

class PluginPurposeTest extends TestCase
{

    public function testFactory()
    {
        $data = [
            'class' => MacrosPluginClass::CLASS_EXPORTER,
            'entity' => PluginEntity::ENTITY_ORDER
        ];

        $instance = PluginPurpose::factory($data);
        $this->assertInstanceOf(PluginPurpose::class, $instance);
        $this->assertInstanceOf(MacrosPluginClass::class, $instance->getClass());
        $this->assertSame(MacrosPluginClass::CLASS_EXPORTER, $instance->getClass()->get());
        $this->assertInstanceOf(PluginEntity::class, $instance->getEntity());
        $this->assertSame(PluginEntity::ENTITY_ORDER, $instance->getEntity()->get());
    }

    public function testWithoutClass()
    {
        $this->expectException(OutOfBoundsException::class);
        PluginPurpose::factory(['entity' => PluginEntity::ENTITY_ORDER]);
    }

    public function testWithoutEntity()
    {
        $this->expectException(OutOfEnumException::class);
        PluginPurpose::factory(['class' => MacrosPluginClass::CLASS_EXPORTER]);
    }
}
