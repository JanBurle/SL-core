<?
require FSL . 'md.php';

class MdBook extends Page {
  var $tpl = 'htmlnav';
  var string $basePath;
  var string $fetchPhp;
  var string $nav;

  function __construct(string $name, string $basePath, array $path) {
    parent::__construct();
    $this->basePath = $basePath;
    $this->fetchPhp = 'sl/php/fetchMD.php';
    $this->nav = tag('ol', $this->navol($this->scan($name, '', '')));
    echo mdPage($this->basePath, $path);
    $path = implode('/', $path);
    echo script("onLis();navPage('$path')");
  }

  function scan(string $name, string $filePath, string $nmPath): array {
    $res = [];

    $dir = FRT . $this->basePath . $filePath;
    foreach (scandir($dir) as $file) {
      if (in_array($file, ['.', '..', 'index.md']))
        continue;
      if (!($nm = stripPrefix($file)))
        continue;

      if (is_dir($dir . $file)) {
        $res[] = $this->scan($nm, $filePath . "$file/", $nmPath . "$nm/");
        continue;
      }

      if (!($nm = stripSuffix($nm)))
        continue;

      $res[] = [$nm, $nmPath . $nm, []];
    }

    return [$name, $nmPath, $res];
  }

  function navol(array $val): string {
    [$name, $relPath, $arr] = $val;
    $path = j($relPath);
    $res = tag('li', $name, "class='ptr' path=$path");

    if ($arr) {
      $sub = '';
      foreach ($arr as $val)
        $sub .= $this->navol($val);
      $res .= tag('ol', $sub);
    }

    return $res;
  }
}
