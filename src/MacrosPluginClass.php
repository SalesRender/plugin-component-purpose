<?php
/**
 * Datetime: 26.06.2019 18:37
 * @author Timur Kasumov aka XAKEPEHOK
 */

namespace SalesRender\Plugin\Components\Purpose;


class MacrosPluginClass extends PluginClass
{

    const CLASS_EXPORTER = 'EXPORTER';
    const CLASS_HANDLER = 'HANDLER';
    const CLASS_IMPORTER = 'IMPORTER';

    public static function values(): array
    {
        return [
            self::CLASS_EXPORTER,
            self::CLASS_HANDLER,
            self::CLASS_IMPORTER,
        ];
    }
}