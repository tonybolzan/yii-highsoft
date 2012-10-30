<?php
/**
 * Highcharts Updater/Downloader
 * Get the latest development files from GitHub in Branch "master"
 * 
 * @author Tonin De Rosso Bolzan <tonin@odig.net>
 * @link http://github.com/tonybolzan/highsoft
 * @license http://www.opensource.org/licenses/mit-license.php "MIT License for this file"
 * @version 0.1
 * 
 * @link http://code.highcharts.com "Highcharts file service"
 * @link https://github.com/highslide-software/highcharts.com "GitHub Repo"
 */
$options = array(
    'assets'  => 'assets',
    'closure' => 'http://closure-compiler.appspot.com/compile',
    'site'    => 'https://raw.github.com/highslide-software/highcharts.com/v2.3.3/js', // Version 2.3.3
    //'site'    => 'http://code.highcharts.com/master', // 
    //'site'    => 'https://raw.github.com/highslide-software/highcharts.com/master/js', // Development version
    
    'files'   => array(
        array(
            'folder' => '/',
            'name'   => 'highcharts.src.js',
            'min'    => 'highcharts.js',
        ),
        array(
            'folder' => '/',
            'name'   => 'highcharts-more.src.js',
            'min'    => 'highcharts-more.js',
        ),
        array(
            'folder' => '/',
            'name'   => 'highstock.src.js',
            'min'    => 'highstock.js',
        ),
        array(
            'folder' => '/modules/',
            'name'   => 'exporting.src.js',
            'min'    => 'exporting.js',
        ),
        array(
            'folder' => '/themes/',
            'name'   => 'dark-blue.js',
            'min'    => 'dark-blue.min.js',
        ),
        array(
            'folder' => '/themes/',
            'name'   => 'dark-green.js',
            'min'    => 'dark-green.min.js',
        ),
        array(
            'folder' => '/themes/',
            'name'   => 'gray.js',
            'min'    => 'gray.min.js',
        ),
        array(
            'folder' => '/themes/',
            'name'   => 'grid.js',
            'min'    => 'grid.min.js',
        ),
        array(
            'folder' => '/themes/',
            'name'   => 'skies.js',
            'min'    => 'skies.min.js',
        ),
        //array(
        //    'folder' => '/adapters/',
        //    'name'   => 'mootools-adapter.src.js',
        //    'min'    => 'mootools-adapter.js',
        //),
        //array(
        //    'folder' => '/adapters/',
        //    'name'   => 'prototype-adapter.src.js',
        //    'min'    => 'prototype-adapter.js',
        //),
    ),
);

foreach ($options['files'] as $file) {
    $url      = $options['site']   . $file['folder'] . $file['name'];
    $path_raw = $options['assets'] . $file['folder'] . $file['name'];
    $path_min = $options['assets'] . $file['folder'] . $file['min'];
    
    echo $path_raw .' <= '. $url . PHP_EOL;
    
    $raw = file_get_contents($url);
    
    $ch = curl_init($options['closure']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'output_info=compiled_code&output_format=text&compilation_level=SIMPLE_OPTIMIZATIONS&js_code=' . urlencode($raw));
    $minified = curl_exec($ch);
    curl_close($ch);
    
    file_put_contents($path_min, $minified, LOCK_EX);
    file_put_contents($path_raw, $raw, LOCK_EX);
}

echo 'Complete...'. PHP_EOL;