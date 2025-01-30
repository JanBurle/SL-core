<?
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
