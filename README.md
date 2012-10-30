#Yii Highsoft - Hightchart and Highstok chart wrapper

HighsoftWidget is a wrapper to {@link http://www.highcharts.com/ Highcharts and Highstock}
Highcharts is a charting library written in pure JavaScript;
Highstock lets you create stock or general timeline charts in pure JavaScript;
https://github.com/highslide-software/highcharts.com

## Features:
- Script to update assets from Highsoft github with auto minifier (Google Clousure)
- Unique widget for Hightchart and Highstok
- Accepts valid array or JSON string to options
- Global atributes for Highcharts object
- "More" option to register highcharts-more.js
- Unminified javascripts in YII_DEBUG mode
- Not regiter jquery dependency on ajax requests
- MIT License (Except assets)

## Example:

To use this widget, you may insert the following code in a view:
```php
$this->Widget('ext.highsoft.HighsoftWidget', array(
   'type' => 'stock', // or 'chart'
   'options' => array(
      'title' => array(
         'text' => 'Fruit Consumption',
      ),
      'xAxis' => array( 
         'categories' => array('Apples', 'Bananas', 'Oranges'),
      ),
      'yAxis' => array(
         'title' => array('text' => 'Fruit eaten'),
      ),
      'series' => array(
         array('name' => 'Jane', 'data' => array(1, 0, 4)),
         array('name' => 'John', 'data' => array(5, 7, 3)),
      )
   )
));
```
By configuring the {@link $options} property, you may specify the options
that need to be passed to the Highcharts JavaScript object. Please refer to
the demo gallery and documentation on the {@link http://www.highcharts.com/
Highcharts website} for possible options.

Alternatively, you can use a valid JSON string in place of an associative array to specify options:

```php
$this->Widget('ext.highsoft.HighsoftWidget', array(
   'type' => 'stock', // or 'chart'
   'options'=>'{
      "title": { "text": "Fruit Consumption" },
      "xAxis": {
         "categories": ["Apples", "Bananas", "Oranges"]
      },
      "yAxis": {
         "title": { "text": "Fruit eaten" }
      },
      "series": [
         { "name": "Jane", "data": [1, 0, 4] },
         { "name": "John", "data": [5, 7,3] }
      ]
   }'
));
```
Note: You must provide a valid JSON string (e.g. double quotes) when using
the second option. You can quickly validate your JSON string online using
{@link http://jsonlint.com/ JSONLint}.

Note: You do not need to specify the <code>chart->renderTo</code> option as
is shown in many of the examples on the Highcharts website. This value is
automatically populated with the id of the widget's container element. If you
wish to use a different container, feel free to specify a custom value.