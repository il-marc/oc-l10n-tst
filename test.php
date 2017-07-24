<?php
$DIR = 'upload';
$LANG = 'ru-ru';

function test_file($path, $labels) {
    $undefined = [];
    include_once $path;
    foreach ($labels as $label) {
        if (!isset($_[$label])) {
            $undefined[] = $label;
        }
    }
    return $undefined;
}
$passed = 0;
$failed = 0;
foreach (['admin', 'catalog'] as $path) {
    $json = json_decode(file_get_contents($path.'.json'), true);
    $json[$LANG.'.php'] = $json['root'];
    unset($json['root']);
    foreach ($json as $file => $labels) {
        $file = implode(DIRECTORY_SEPARATOR, [$DIR, $path, 'language', $LANG, $file]);
        $undefined = test_file($file, $labels);
        if (!empty($undefined)) {
            $failed++;
            echo 'Failed ' . $file . PHP_EOL;
            echo "\tUndefined: " . implode(', ', $undefined).PHP_EOL;
        } else {
            $passed++;
        }
    }
}
echo "Passed: $passed. Failed: $failed";