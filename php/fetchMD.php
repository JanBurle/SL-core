<?
require __DIR__ . '/../paths.php';
require FSL . 'autoload.php';
require FSL . 'path.php';
require FSL . 'md.php';

$path = arrPath($_REQUEST['path'] ?? '');
echo mdPage('', $path);
