<?php
/**
 * Created for plugin-component-purpose
 * Date: 28.12.2023
 * @author: Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Plugin\Components\Purpose;

class ResalePluginClass extends PluginClass
{

    const CLASS_RESALE = 'RESALE';

    public static function values(): array
    {
        return [
            self::CLASS_RESALE,
        ];
    }

}