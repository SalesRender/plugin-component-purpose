<?php
/**
 * Created for plugin-component-purpose
 * Date: 06.01.2024
 * @author: Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Plugin\Components\Purpose;

use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

class PluginClassTest extends TestCase
{

    /**
     * @dataProvider classesDataProvider
     * @param string|PluginClass $class
     * @return void
     */
    public function testFactory(string $class)
    {
        foreach ($class::values() as $value) {
            $instance = PluginClass::factory($value);
            $this->assertInstanceOf($class, $instance);
            $this->assertSame($value, $instance->get());
        }
    }

    public function testFactoryInvalidValue()
    {
        $this->expectException(OutOfBoundsException::class);
        PluginClass::factory('qwerty');
    }

    private function classesDataProvider(): array
    {
        return [
            [MacrosPluginClass::class],
            [LogisticPluginClass::class],
            [PbxPluginClass::class],
        ];
    }

}
