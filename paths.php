<?
define('DRT', $_SERVER['DOCUMENT_ROOT']);       // document root

// file system paths
define('FRT', realpath(__DIR__ . '/..') . '/'); // website root
define('FSL', __DIR__ . '/');                   // SeiteLite root

function relPath(string $dir): string {
  assert(0 === strpos($dir, DRT));
  return substr($dir, strlen(DRT));
}

// corresponding paths relative to document root
define('RT', relPath(FRT, DRT));                // website root
define('SL', relPath(FSL, DRT));                // SeiteLite root

// URL website root
define('URT', ('on' == ($_SERVER['HTTPS'] ?? '') ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . RT);
