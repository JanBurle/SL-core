<?
class Page extends Site {
  private $body = '';
  private $elsTodo = [];
  private $elsDone = [];

  function __construct() {
    ob_start();
  }

  function __destruct() {
    $this->body = ob_get_clean();
    $this->elsTodo = customTags($this->body);

    // search template dirs
    foreach ([FRT,FSL] as $dir) {
      $tplPath = 'tpl/' . $this->tpl;
      $tplBase = $dir . $tplPath;
      $tplFile = $tplBase. '.php';

      if (file_exists($tplFile))
        break;
      else
        $tplFile='';
    }

    check($tplFile, 'missing template');

    ob_start();
    require $tplFile;
    echo ob_get_clean();
  }

  function els(string|array $tags) {
    $this->elsTodo = array_merge($this->elsTodo, (array) $tags);
  }

  function readEls(): array {
    $elsDone = $this->elsDone;
    $elsTodo = array_diff($this->elsTodo, $elsDone);
    $this->elsTodo = [];
    $this->elsDone = array_merge($elsDone, $elsTodo);

    return readEls(FSL . 'els', FRT . 'dist/els', $elsTodo);
  }
}
