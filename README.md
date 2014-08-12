Enumeration extension for Yii2
=========

This component allows you to define enumeration types in a Yaml definition file and
build PHP classes with class constants corresponding to these types.

Installation
------------
Add the following to your composer.json:
```json
{
	"require": {
		"opus-online/yii2-enum": "*"
	}
}
```

Attach the `EnumController` class to you project. You can do this either by creating
a new controller that extends `EnumController` or you can override `coreCommands` in
your console application and add the controller there.
```php
'enum' => [
    'class' => EnumController::className(),
    // add the path alias of the Yaml file here
    'definitionAlias' => '@app/build/enum.yml',
],
```

Usage
-----
Define your types in a Yaml file:
```yaml
PrevalenceThreshold:
  - PERCENTAGE
  - TIMES
  - STATISTICAL
# you can also use shorter syntax
Behaviour: [COMMERCIAL, CUSTOM, OFFICIAL]
TrpResult:
  - TREND_CONTINUES
  # and you can define an explicit value for the type
  - TREND_REVERSED:
      value: 300
  - TREND_CHANGED_AND_CAN_REVERSE
```

Run the build command to generate PHP classes for the definition.
```bash
php yii enum/build my\\ns @app/src/enum
```
This creates the type classes under namespace `my\ns` (notice the double `\\`)
and places the files into a directory that corresponds to `@app/src/enum` path alias.

After this you can use enumeration types like this:
```php
echo Behaviour::COMMERCIAL; // COMMERCIAL
echo TrpResult::TREND_REVERSED; // 300

// return all values of a type
TrpResult::getList();

// return a human-readable label for one value (by constant name)
echo TrpResult::getLabel('TREND_CONTINUES'); // Trend continues

// returns an array type where type value is the key and human readable
// label is the value. This can be useful when populating drop-downs
TrpResult::getListLabels();

```
