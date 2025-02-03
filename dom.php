<?

function domFrom(string $html): DOMDocument {
  $dom = new DOMDocument();
  libxml_use_internal_errors(true);
  $html && $dom->loadHTML($html);
  if (isDev()) {
    foreach (libxml_get_errors() as $error)
      if (LIBXML_ERR_FATAL == $error->level)
        printError("domFrom: $error->message");
  }
  libxml_clear_errors();
  return $dom;
}

function customTags(string $html): array {
  $dom = domFrom($html);

  $tags = [];
  foreach ($dom->getElementsByTagName('*') as $el) {
    $tag = $el->nodeName;
    if (false !== strpos($tag, '-'))
      $tags[] = $tag;
  }

  return array_unique($tags);
}

function tag($tag, $content, $extra = '') {
  $extra && $extra = " $extra";
  return "<$tag$extra>$content</$tag>";
}

function script($content) {
  return tag('script', $content);
}
