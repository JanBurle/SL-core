<? $this->els(['s-ico']) ?>
<? include __DIR__ . '/inc/head.php' ?>
<?
$basePath = $this->basePath . '/';
$pagesUrl = URT . $this->basePath . '/';
$fetchPhp = URT . $this->fetchPhp . '/';
?>
<?= script("SLG.nav={basePath:'$basePath',pagesUrl:'$pagesUrl',fetchPhp:'$fetchPhp'}") ?>
<aside>
  <header>
    <span id="menu"><s-ico arrow-left></s-ico></span>
  </header>
  <nav>
    <?= $this->nav ?>
  </nav>
  <footer>
  </footer>
</aside>

<main>
  <header>
    <span id="menu"><s-ico menu></s-ico></span>
  </header>
  <a href=""></a>
  <article>
    <?= $this->body ?>
  </article>
  <footer>
  </footer>
</main>

<? include __DIR__ . '/inc/foot.php' ?>
