<?

class MdBook extends Page {
  var $tpl = 'htmlnav';
  var string $basePath;
  var string $fetchPhp;
  var string $nav;
  var string $page;

  function __construct($name, $basePath, $path) {
    parent::__construct();
    $this->basePath = $basePath;
    $this->fetchPhp = 'sl/php/fetch.php';
    $this->page = implode('/', $path);
    $this->nav = tag('ol', $this->navol($this->scan($name, '')));
    $this->showPage();
    echo script("navPage('{$this->page}')");
  }

  function scan($name, $relPath) {
    $res = [];

    $dir = FRT . $this->basePath . '/' . $relPath;
    foreach (scandir($dir) as $file) {
      if (in_array($file, ['.', '..', 'index.md']))
        continue;
      // strip prefix
      $nm = $file;
      // TODO // strip prefix 99- from $nm
      // if (false !== ($pos = strpos($nm, '.')))
      //   $nm = substr($nm, $pos + 1);

      if (is_dir("$dir/$file"))
        $res[] = $this->scan($nm, $relPath ? "$relPath/$file" : $file);
      elseif (pathinfo($file, PATHINFO_EXTENSION) == 'md') {
        $file = pathinfo($file, PATHINFO_FILENAME);
        $nm = pathinfo($nm, PATHINFO_FILENAME);
        $res[] = [pathinfo($nm, PATHINFO_FILENAME), "$relPath/$file", []];
      }
    }

    return [$name, $relPath, $res];
  }

  function navol($val) {
    [$name, $relPath, $arr] = $val;
    $page = json_encode($relPath, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    $res = tag('li', $name, "class='ptr' page=$page onclick='gotoPage(this)'");

    if ($arr) {
      $sub = '';
      foreach ($arr as $val)
        $sub .= $this->navol($val);
      $res .= tag('ol', $sub);
    }

    return $res;
  }

  function showPage() {
    $page = $this->page;
    $file = FRT . $page;
    if (is_dir($file))
      $file .= '/index.md';
    else
      $file .= '.md';
    if (file_exists($file)) {
      $md = new md\ParsedownExtra();
      echo $md->text(file_get_contents($file));
    }
  }
}
