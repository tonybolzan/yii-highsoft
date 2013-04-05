#Yii Highsoft - Hightchart and Highstock chart wrapper

HighsoftWidget is a wrapper to [Highcharts](http://www.highcharts.com/ "Highcharts Official Site") and [Highstock](http://www.highcharts.com/products/highstock "Highstock Official Site")
Highcharts is a charting library written in pure JavaScript;
Highstock lets you create stock or general timeline charts in pure JavaScript;
https://github.com/highslide-software/highcharts.com

## Features:
- Script to update assets from Highsoft github with auto minifier (Google Clousure API)
- Unique widget for Hightchart and Highstock
- Accepts valid PHP array or JSON string to options
- Global attributes for Highcharts object
- "More" option to register highcharts-more.js
- Unminified javascripts in YII_DEBUG mode
- No register jQuery dependency on ajax requests
- Only one script is loaded in multiple highcharts charts on ajax requests
- MIT License (Except assets)

## Example:

To use this widget, you need to insert the following code in a view:
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
By configuring the **$options** property, you may specify the options
that need to be passed to the Highcharts Javascript object. Please refer to
the demo gallery and documentation on the [HighCharts Documentation Site](http://www.highcharts.com/) for possible options.

Alternatively, you can use a valid JSON string instead of an associative array to specify options:

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
[JSONLint](http://jsonlint.com/).

Note: You do not need to specify the <code>chart->renderTo</code> option as
is shown in many of the examples on the Highcharts website. This value is
automatically populated with the id of the widget's container element. If you
wish to use a different container, feel free to specify a custom value.

## ChangeLog
* **3.0.0** :
    - Updated Highcharts to Version 3.0.0 (2013-04-05)
    - Improved generation graphics for ajax requests
    - Chart container `div` tag not closing (fixed)