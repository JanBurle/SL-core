<?
require __DIR__ . '/../paths.php';

$page = FRT . $_REQUEST['page'] ?? '';

function incFile(string $file): bool {
  return false !== @include $file;
}

spl_autoload_register(function (string $cls) {
  // namespace to path
  $file = str_replace('\\', '/', $cls) . '.php';
  // class dirs
  $clsDirs = [FSL . 'cls/'];
  // search
  foreach ($clsDirs as $dir)
    if (incFile($dir . $file))
      return true;
  return false;
});

function pageHtml($page) {
  if (is_dir($page))
    $page .= '/index.md';
  else
    $page .= '.md';
  if (file_exists($page)) {
    $md = new md\ParsedownExtra();
    echo $md->text(file_get_contents($page));
  }
}

pageHtml($page);
