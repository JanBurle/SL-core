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

    $tplBase = FSL . 'tpl/' . $this->tpl;
    $tplPath = relPath($tplBase);
    ob_start();
    require $tplBase . '.php';
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
