#!/usr/bin/env php
<?php
/**
 * Highcharts Updater/Downloader
 * Get the latest development files from GitHub in Branch "master"
 *
 * @author Tonin De Rosso Bolzan <tonin@odig.net>
 * @link http://github.com/tonybolzan/highsoft
 * @license http://www.opensource.org/licenses/mit-license.php "MIT License for this file"
 * @version 3.0.10
 *
 * @link http://code.highcharts.com "Highcharts file service"
 * @link https://github.com/highslide-software/highcharts.com "GitHub Repo"
 */
$options = array(
    'assets' => 'assets',
    'site' => 'http://code.highcharts.com',

    'files' => array(
        '/3.0.10/highcharts.src.js'        => '/highcharts.src.js',
        '/3.0.10/highcharts.js'            => '/highcharts.js',
        '/3.0.10/highcharts-more.src.js'   => '/highcharts-more.src.js',
        '/3.0.10/highcharts-more.js'       => '/highcharts-more.js',

        '/3.0.10/modules/exporting.src.js' => '/modules/exporting.src.js',
        '/3.0.10/modules/exporting.js'     => '/modules/exporting.js',

        '/3.0.10/themes/dark-blue.js'      => '/themes/dark-blue.js',
        '/3.0.10/themes/dark-green.js'     => '/themes/dark-green.js',
        '/3.0.10/themes/gray.js'           => '/themes/gray.js',
        '/3.0.10/themes/grid.js'           => '/themes/grid.js',
        '/3.0.10/themes/skies.js'          => '/themes/skies.js',

        '/stock/1.3.10/highstock.src.js'   => '/highstock.src.js',
        '/stock/1.3.10/highstock.js'       => '/highstock.js',
    ),
);

if (is_dir($options['assets'] . '.old')) {
    system('rm -rf "' . $options["assets"] . '.old"');
}
if (is_dir($options['assets'])) {
    rename($options['assets'], $options['assets'] . '.old') or die("Unable to rename {$options['assets']} to {$options['assets']}.old." . PHP_EOL);
}

mkdir($options['assets']) or die("Unable to create new directory {$options['assets']}" . PHP_EOL);

foreach ($options['files'] as $url => $file) {
    $end_file = $options['assets'] . $file;
    $end_url = $options['site'] . $url;

    echo $end_file . ' <= ' . $end_url . PHP_EOL;

    $dir = dirname($end_file);
    if (!is_dir($dir)) {
        mkdir($dir) or die("Unable to create new directory {$dir}" . PHP_EOL);
    }

    $raw = file_get_contents($end_url);

    if (!$raw) {
        echo '    -> Falhou';
    } else {
        file_put_contents($end_file, $raw, LOCK_EX);
    }
}

echo PHP_EOL . 'Please remove old assets directory' . PHP_EOL . 'Complete...' . PHP_EOL;
