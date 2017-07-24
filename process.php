<?php
$lang = [];
function process_file($filename) {
    global $lang;
    if (pathinfo($filename)['extension'] !== 'php') return;
    $lines = explode("\n", trim(file_get_contents($filename)));
    echo $filename.PHP_EOL;
    foreach ($lines as $line) {
        if (strpos($line, '$_[') === 0) {
            $label = substr($line, strpos($line,'\'')+1);
            $label = substr($label, 0, strpos($label,'\''));
            $lang[substr($filename, 2)][] = $label;
            echo $label . PHP_EOL;
        }
    }
}
function process_dir($path) {
    foreach(scandir($path) as $sub) {
        if ($sub == ".." || $sub == ".") continue;
        if (is_dir($path.DIRECTORY_SEPARATOR.$sub)) {
            process_dir($path.DIRECTORY_SEPARATOR.$sub);
        } else {
            process_file($path.DIRECTORY_SEPARATOR.$sub);
        }
    }
}
foreach (['admin', 'catalog'] as $path) {
    chdir('upload\\'.$path.'\language\en_gb');
    process_dir('.');
    chdir('..\..\..\..');
    $lang['root'] = $lang['en_gb.php'];
    unset($lang['en_gb.php']);
    file_put_contents($path.'.json', json_encode($lang, JSON_PRETTY_PRINT));
    $lang = [];
}
