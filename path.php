<?
function arrPath(string $path): array {
  $path = explode('/', $path);
  return array_filter($path, fn($s) => trim($s));
}

function stripSuffix(string $name): string {
  // could be .md
  if (substr($name, -3) == '.md')
    $name = substr($name, 0, -3);
  return $name;
}

function stripPrefix(string $name): string {
  // may begin with digits* and a dash / strip it
  if (preg_match('/^\d*-/', $name))
    $name = preg_replace('/^\d+-/', '', $name);
  return $name;
}

function findFile(string $dir, string $name): array {
  foreach (scandir($dir) as $file) {
    if (in_array($file, ['.', '..', 'index.md']))
      continue;
    if (!($nm = stripPrefix($file)))
      continue;

    if (is_dir($dir . $file) && $nm == $name)
      return [$nm, $file, true];

    if (!($nm = stripSuffix($nm)))
      continue;

    if ($nm == $name)
      return [$nm, $file, false];
  }

  return [null, false];
}
