<?
require __DIR__ . '/../paths.php';
defPaths(-2);
require FSL . 'autoload.php';
require FSL . 'path.php';
require FSL . 'md.php';

$path = arrPath($_REQUEST['path'] ?? '');
echo mdPage('', $path);
