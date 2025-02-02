<?
function pathTail(string $dir, string $head): string {
  assert(0 === strpos($dir, $head));
  return substr($dir, strlen($head));
}

function defPaths($trimLevels = 0): void {
  // file system paths
  define('FRT', realpath(__DIR__ . '/..') . '/'); // website root
  define('FSL', __DIR__ . '/');                   // SeiteLite root

  // corresponding paths, relative to URL root
  $rt = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/';
  if ($trimLevels < 0) {
    $rtParts = explode('/', $rt);
    array_splice($rtParts, $trimLevels - 1);
    $rt = implode('/', $rtParts) . '/';
  }

  define('RT', $rt);                      // website root
  define('SL', RT . pathTail(FSL, FRT));  // SeiteLite root

  // URL website root
  define('URT', ('on' == ($_SERVER['HTTPS'] ?? '') ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . RT);
}
