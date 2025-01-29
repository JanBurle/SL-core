<?
class Site // the base class for all pages
{
  // default values
  var $title  = 'SeiteLite';        // page title
  var $tpl    = 'html';             // template name
  var $lang   = 'en';               // language

  var $logo   = SL . 'assets/logo.svg'; // logo/icon

  // meta tags
  var $description  = '';
  var $robots       = 'index, follow';
}
