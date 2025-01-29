doc.whenDone(() => {
  let aside = doc.qSel('body > aside')
  let menu = doc.qSel('body > main #menu')
  let back = doc.qSel('body > aside #menu')

  SLG.nav.aside = aside

  menu.clk(() => aside.tgl('active'))
  back.clk(() => aside.tgl('active'))
})

let fetchPage = (page, replace) => {
  let href = SLG.nav.pagesUrl + page
  if (replace) history.replaceState(page, '', href)
  else history.pushState(page, '', href)

  navPage(page)

  let url = new URL(SLG.nav.fetchPhp)
  url.searchParams.set('page', SLG.nav.basePath + page)

  fetch(url)
    .then((res) => res.text())
    .then((html) => {
      doc.qSel('body > main > article').innerHTML = html
    })
}

let gotoPage = (li) => {
  let page = li.getAttribute('page')
  SLG.nav.aside.tgl('active', false)
  fetchPage(page, false)
}

let navPage = (page) => {
  let lis = doc.qAll('body > aside > nav li')
  lis.forEach((li) => li.tgl('active', li.getAttribute('page') == page))
}

win.addEventListener('popstate', (event) => {
  let page = event.state || ''
  fetchPage(page, true)
})
