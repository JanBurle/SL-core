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
    foreach ([[FRT, RT], [FSL, SL]] as [$dir, $url]) {
      $tpl = 'tpl/' . $this->tpl;
      $tplBase = $dir . $tpl;
      $tplFile = $tplBase . '.php';
      $tplPath = $url . $tpl;

      if (is_file($tplFile))
        break;
      else
        $tplFile = '';
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

    return readEls(FRT . 'els', FSL . 'els', FRT . 'dist/els', $elsTodo);
  }
}
