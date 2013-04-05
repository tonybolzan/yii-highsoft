<?php

/**
 * HighsoftWidget class file.
 *
 * @author Tonin De Rosso Bolzan <tonin@odig.net>
 * @link http://github.com/tonybolzan/highsoft
 * @license http://www.opensource.org/licenses/mit-license.php "MIT License for this file"
 * @version 3.0.0
 * 
 * @link http://code.highcharts.com "Highcharts file service"
 * @link https://github.com/highslide-software/highcharts.com "GitHub Repo"
 * 
 * Inspired by Milo Schuman <miloschuman@gmail.com> http://yii-highcharts.googlecode.com
 */

/**
 * HighsoftWidget is a wrapper to {@link http://www.highcharts.com/ Highcharts and Highstock}
 * Highcharts is a charting library written in pure JavaScript;
 * Highstock lets you create stock or general timeline charts in pure JavaScript;
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->Widget('ext.highsoft.HighsoftWidget', array(
 *    'type' => 'stock', // or 'chart'
 *    'options' => array(
 *       'title' => array(
 *          'text' => 'Fruit Consumption',
 *       ),
 *       'xAxis' => array( 
 *          'categories' => array('Apples', 'Bananas', 'Oranges'),
 *       ),
 *       'yAxis' => array(
 *          'title' => array('text' => 'Fruit eaten'),
 *       ),
 *       'series' => array(
 *          array('name' => 'Jane', 'data' => array(1, 0, 4)),
 *          array('name' => 'John', 'data' => array(5, 7, 3)),
 *       )
 *    )
 * ));
 * </pre>
 *
 * By configuring the {@link $options} property, you may specify the options
 * that need to be passed to the Highcharts JavaScript object. Please refer to
 * the demo gallery and documentation on the {@link http://www.highcharts.com/
 * Highcharts website} for possible options.
 *
 * Alternatively, you can use a valid JSON string in place of an associative array to specify options:
 *
 * <pre>
 * $this->Widget('ext.highsoft.HighsoftWidget', array(
 *    'type' => 'stock', // or 'chart'
 *    'options'=>'{
 *       "title": { "text": "Fruit Consumption" },
 *       "xAxis": {
 *          "categories": ["Apples", "Bananas", "Oranges"]
 *       },
 *       "yAxis": {
 *          "title": { "text": "Fruit eaten" }
 *       },
 *       "series": [
 *          { "name": "Jane", "data": [1, 0, 4] },
 *          { "name": "John", "data": [5, 7,3] }
 *       ]
 *    }'
 * ));
 * </pre>
 *
 * Note: You must provide a valid JSON string (e.g. double quotes) when using
 * the second option. You can quickly validate your JSON string online using
 * {@link http://jsonlint.com/ JSONLint}.
 *
 * Note: You do not need to specify the <code>chart->renderTo</code> option as
 * is shown in many of the examples on the Highcharts website. This value is
 * automatically populated with the id of the widget's container element. If you
 * wish to use a different container, feel free to specify a custom value.
 */
class HighsoftWidget extends CWidget {

    /**
     * String containing the type of chart to use
     * @var string Chart type 'chart' or 'stock'
     */
    public $type = 'chart';

    /**
     * Sets the options globally for all charts created after this has been called.
     * Takes an options JavaScript object structure as the argument.
     * These options are merged with the default options.
     * Ex.: 'lang' Object
     * @var mixed array to be encoded with {@link CJavaScript::encode JavaScript Encoder} or valid JSON string
     */
    public $global = array();

    /**
     * 
     * @var mixed array to be encoded with {@link CJavaScript::encode JavaScript Encoder} or valid JSON string
     */
    public $options = array();

    /**
     * The values will be HTML-encoded.
     * @var array The element attributes.
     */
    public $htmlOptions = array();

    /**
     * Highcharts-more add-on
     * @var boolean Register highcharts-more add-on
     */
    public $more = true;

    /**
     * Default options to be merges into options
     * @var array Options
     */
    private $defaultOptions = array(
        'exporting' => array(
            'enabled' => true,
            'width' => 1280,
        ),
    );

    /**
     * The name of assets folder
     * @var string name
     */
    private $assets = 'assets';

    /**
     * Renders the widget.
     */
    public function run() {
        // If not set Id get id from the class name
        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->id;
        }

        // check if "options" and "global" parameter is a json string and decode
        if (is_string($this->options)) {
            $this->options = CJSON::decode($this->options);
            if (!is_array($this->options)) {
                throw new CException(yii::t('highsoft', 'The options parameter is not valid JSON.'));
            }
        }
        if (is_string($this->global)) {
            $this->global = CJSON::decode($this->global);
            if (!is_array($this->global)) {
                throw new CException(yii::t('highsoft', 'The global parameter is not valid JSON.'));
            }
        }

        // Define container to render
        $this->defaultOptions = CMap::mergeArray(array('chart' => array('renderTo' => $this->htmlOptions['id'])), $this->defaultOptions);

        // Merge default options in options received
        $this->options = CMap::mergeArray($this->defaultOptions, $this->options);

        // Generate "options" to highsoft
        $jsOptions = CJavaScript::encode($this->options);
        $jsGlobal  = CJavaScript::encode($this->global);
        
        echo CHtml::openTag('div', $this->htmlOptions);
        echo CHtml::closeTag('div');

        $id = __CLASS__ . '#' . $this->htmlOptions['id'];
        $this->registerScripts($id, $jsGlobal, $jsOptions);
    }

    /**
     * Publishe and register script files.
     * 
     * @param string $id Identifier of the script
     * @param string $jsGlobal JavaScript to "setGlobal"
     * @param string $jsOptions JavaScript options
     * @throws CException Invalid JSON string
     */
    protected function registerScripts($id, $jsGlobal, $jsOptions) {
        $register = array();
        $script = array();

        if ($this->global) {
            $script[] = "Highcharts.setOptions($jsGlobal);";
        }

        switch ($this->type) {
            case 'chart':
                $script[] = "var chart_{$this->htmlOptions['id']} = new Highcharts.Chart($jsOptions);";
                $register[] = YII_DEBUG ? 'highcharts.src.js' : 'highcharts.js';

                if ($this->more) {
                    $register[] = YII_DEBUG ? 'highcharts-more.src.js' : 'highcharts-more.js';
                }
                break;
            case 'stock':
                $script[] = "var chart_{$this->htmlOptions['id']} = new Highcharts.StockChart($jsOptions);";
                $register[] = YII_DEBUG ? 'highstock.src.js' : 'highstock.js';
                break;
            default:
                throw new CException(yii::t('highsoft', 'The type parameter is not valid, try "chart" or "stock".'));
                break;
        }

        $cs = Yii::app()->clientScript;

        // Register exporting module if enabled via the 'exporting' option
        if (isset($this->options['exporting']['enabled']) and $this->options['exporting']['enabled']) {
            $register[] = 'modules/' . (YII_DEBUG ? 'exporting.src.js' : 'exporting.js');
        }

        // Register global theme if specified via the 'theme' option
        if (isset($this->options['theme'])) {
            $register[] = 'themes/' . $this->options['theme'] . (YII_DEBUG ? '.js' : '.min.js');
        }
            
        // Publish and register all files specified
        $basePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->assets . DIRECTORY_SEPARATOR;
        $baseUrl = Yii::app()->assetManager->publish($basePath, false, 1, YII_DEBUG);

        // Register the graph and jquery if not ajax request
        if (Yii::app()->request->isAjaxRequest) {
            foreach ($register as &$scriptFile) {
                $scriptFile = $baseUrl . '/' . $scriptFile;
            }

            $jsFiles = '["'. implode('","', $register) .'"]';

            array_unshift($script, <<<JS
                !(function($,a){
                    if(typeof window.Highcharts === 'undefined') {
                        var body = $('body');
                        for(var i=0, l=a.length;i<l;i++) {
                          body.append($('<script>').attr('src',a[i]));
                        }
                    }
                }(jQuery,{$jsFiles}));
JS
            );
            
            $cs->registerScript($id, implode('', $script), CClientScript::POS_END);
        } else {
            foreach ($register as &$scriptFile) {
                $cs->registerScriptFile("$baseUrl/$scriptFile");
            }
            $cs->registerCoreScript('jquery');
            $cs->registerScript($id, implode('', $script), CClientScript::POS_LOAD);
        }
    }
}
