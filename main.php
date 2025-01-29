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
// utilities
require FSL . 'util.php';

// include file with pars
function incFile(string $file, $path = []): bool {
  // vars available in included file: $file, $path
  return false !== @include $file;
}

// autoload classes
spl_autoload_register(function (string $cls) {
  // namespace to path
  $file = str_replace('\\', '/', $cls) . '.php';
  // class dirs
  $clsDirs = [FSL . 'cls/', FRT . 'cls/'];
  // search
  foreach ($clsDirs as $dir)
    if (incFile($dir . $file))
      return true;
  return false;
});

// routing
parse_str($_SERVER['QUERY_STRING'], $qry);
$path = explode('/', $qry['path'] ?? '');
$path = array_filter($path, fn($s) => trim($s));

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
