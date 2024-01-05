<?php
/**
 * Created for plugin-component-purpose
 * Date: 28.12.2023
 * @author: Timur Kasumov (XAKEPEHOK)
 */

namespace SalesRender\Plugin\Components\Purpose;

class PbxPluginClass extends PluginClass
{

    const CLASS_SIP = 'SIP';
    const CLASS_WEBHOOK = 'WEBHOOK';

    public static function values(): array
    {
        return [
            self::CLASS_SIP,
            self::CLASS_WEBHOOK,
        ];
    }

}