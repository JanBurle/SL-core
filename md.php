<?
function mdPage(string $base, array $path) {
  $df = FRT . $base; // directory or file
  while ($path) {
    [$name, $file, $isDir] = findFile($df, array_shift($path));
    if (!$name)
      break;

    if ($isDir) {
      $df .= "$file/";
      if (!$path) {
        $df .= 'index.md';
        break;
      }
    } else {
      $df .= $file;
      break;
    }
  }

  if (is_file($df) && substr($df, -3) == '.md') {
    $md = new md\ParsedownExtra();
    echo $md->text(file_get_contents($df));
  }
}
