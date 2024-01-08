<?php
/**
 * Created for plugin-component-purpose
 * Date: 28.12.2023
 * @author: Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Plugin\Components\Purpose;

class LogisticPluginClass extends PluginClass
{

    const CLASS_DELIVERY = 'DELIVERY';
    const CLASS_FULFILLMENT = 'FULFILLMENT';

    public static function values(): array
    {
        return [
            self::CLASS_DELIVERY,
            self::CLASS_FULFILLMENT,
        ];
    }

}