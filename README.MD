# What is it for?
This packet is internal representation of possible plugin actions and entities, that can be handled by actions.

## PluginClass
`SalesRender\Plugin\Components\Purpose\PluginClass` - ValueObject, that provide possible plugin classes (actions). 
- `PluginClass::CLASS_EXPORTER` plugin with this class can export some data in to some format

## PluginEntity
`SalesRender\Plugin\Components\Purpose\PluginEntity` - ValueObject, that provide possible entities, that plugin can handle. 
- `PluginEntity::ENTITY_ORDER` mean, that plugin can work with orders

## PluginPurpose
`\SalesRender\Plugin\Components\Purpose\PluginPurpose` - container for PluginClass & PluginEntity

