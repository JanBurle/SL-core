// @ts-ignore Duplicate identifier '_'
class _ extends ShadowElem {
  constructor() {
    super()
    this.set('span', this.textContent)
    this.qSel('svg').fetchIcon(this.attr('icon'))
  }
}
