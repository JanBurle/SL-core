<?
// error handling
class SlErr extends Exception {
}

function err(string $msg, int $code = 500) {
  throw new SlErr($msg, $code);
}

function check($expr, $msg = 'runtime check') {
  if (!$expr) err($msg);
}

// cache busting
function bust(): string {
  return doBust() ? '?bust=' . time() : '';
}

function h(string $s): string {
  return htmlspecialchars($s);
}

function j(string $s): string {
  return json_encode($s, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}
