<?
function conf($key, $def = null): mixed {
  global $conf;
  return $conf[$key] ?? $def;
}

// Is this a development environment?
function isDev(): int {
  return conf('dev') && ini_get('display_errors');
}

function doBust(): bool {
  return isDev();
}

require __DIR__ . '/paths.php';
require FSL . 'util.php';
require FSL . 'autoload.php';
require FSL . 'path.php';

// routing
$path = arrPath($_REQUEST['path'] ?? '');

// error handling
require FSL . 'err.php';
// DOM
require FSL . 'dom.php';

function route(array $route, array $path): false|array {
  for (;;) {
    if (!($head = ($path[0] ?? '')))      // no more path
      break;
    if (!($leg = ($route[$head] ?? '')))  // no route
      break;
    array_shift($path);
    if (!is_array($leg))                  // route end
      return [$leg, $path];               // target file + remaining path
    return route($leg, $path);            // dive in
  }

  return ['index', $path];                // default
}

// let's go
try {
  checkVersion();
  $pages = conf('pages', '');
  [$file, $path] = route($route, $path);
  incFile(FRT . "$pages$file.php", $path) or err('Page not found', 404);
} catch (Exception $e) {
  errPage($e);
}
