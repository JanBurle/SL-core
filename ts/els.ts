SLG.reVars = {}

// base classes for custom html elements
class _Elem extends HTMLElement {
  root: ShadowRoot | HTMLElement
  rv: ReVar<any>

  constructor() {
    super()
    // react
    this.rv = SLG.reVars[this.attr('$')] || new ReVar(0)
  }

  connectedCallback() {
    // react
    this.rv.sub(this)
    this.init()
    this.revar()
  }

  disconnectedCallback() {
    this.rv.unsub(this)
    this.done()
  }

  init() {}
  done() {}

  revar() {}

  // --- attributes ---

  // test attribute
  hasAttr(attr: str): bol {
    return this.hasAttribute(attr)
  }

  // get attribute
  attr(attr: str, def = ''): str {
    return this.getAttribute(attr) ?? def
  }

  // first attribute w/o value
  attr0(): str {
    for (let attr of this.attributes as any as Attr[]) if (!attr.value) return attr.name
    return ''
  }

  // get as num
  numAttr(attr: str, def = 0): num {
    return this.attr(attr).toNum(def)
  }

  // get as int
  intAttr(attr: str, def = 0 as int): int {
    return this.attr(attr).toInt(def)
  }

  // get as object
  objAttr(attr: str, def = null): any {
    return this.attr(attr).toObj(def)
  }

  // set attribute
  setAttr(attr: str, val = '') {
    this.setAttribute(attr, val)
  }

  // remove attribute
  remAttr(attr: str) {
    this.removeAttribute(attr)
  }

  // toggle no-value attribute
  tglAttr(attr: str, on?: bol) {
    if (undefined === on) on = !this.hasAttr(attr)
    on ? this.setAttr(attr) : this.remAttr(attr)
  }

  // --- inner nodes ---

  // selector
  qSel(sel: str) {
    return this.root.querySelector(sel) as HTMLElement
  }

  // selector
  qAll(sel: str) {
    return this.root.querySelectorAll(sel)
  }

  // selector
  qId(id: str) {
    return this.qSel('#' + id)
  }

  // --- slot nodes ---
  slotNodes() {
    return (this.qSel('slot') as HTMLSlotElement).assignedNodes()
  }

  slotTags(tag: str) {
    let nodes = this.slotNodes()
    tag = tag.toUpperCase()
    return nodes.filter((node) => tag == node.nodeName)
  }

  slotText(tag = '', def = '') {
    return (tag ? this.slotTags(tag) : this.slotNodes())[0]?.textContent || def
  }

  slotNum(tag = '', def = 0) {
    return this.slotText(tag).toNum(def)
  }

  slotInt(tag = '', def = 0 as int) {
    return this.slotText(tag).toInt(def)
  }

  slotObj(tag = '', def = null) {
    return this.slotText(tag).toObj(def)
  }

  // slot selector
  qSlot(sel: str) {
    return this.querySelector(sel)
  }

  // --- handle nodes ---

  // tag or element
  _el(elTag: elTag) {
    return elTag.isStr() ? this.qSel(elTag as str) : (elTag as el)
  }

  _slotEl(elTag: elTag) {
    return elTag.isStr() ? this.qSlot(elTag as str) : (elTag as el)
  }

  // append child to root
  apdRoot(elTag: elTag): el {
    return this.root.apd(elTag)
  }

  // move from slot inside
  move(toElTag: elTag, el: el) {
    this._el(toElTag).appendChild(this._slotEl(el))
  }

  // set inner HTML
  set(elTag: elTag, html: str) {
    this._el(elTag).innerHTML = html
  }

  // --- view ---

  setPos(left: int, top: int) {
    this.style.left = left + 'px'
    this.style.top = top + 'px'
  }

  // call back when host width changes
  onWidth(cb: cbNum) {
    new ResizeObserver((els) => {
      let {clientWidth} = els[0].target
      cb(clientWidth)
    }).observe(this)
  }

  // call back when host comes into view, only once
  onView(cb: cb) {
    new IntersectionObserver((els, observer) => {
      if (0 < els[0].intersectionRatio) {
        observer.disconnect()
        cb()
      }
    }).observe(this)
  }

  // --- reactive attributes ---
  static reattrs: str[] = []

  static get observedAttributes() {
    return this.reattrs
  }

  attributeChangedCallback(name: str, oldVal: any, newVal: any) {
    if (oldVal != newVal) this.reatr(name, newVal)
  }

  reatr(name: str, val: any) {}
}

// --- define custom elements ---
interface CustomElementConstructor {
  tag: str
}

// common shadow DOM style
let shadowStyle = new CSSStyleSheet()

for (let sheet of doc.styleSheets as any as CSSStyleSheet[]) {
  // based on title: transfer rules
  if ('shadow' == sheet.title)
    for (let rule of sheet.cssRules as any as CSSRule[])
      shadowStyle.insertRule(rule.cssText)
}

// shadow DOM element
class ShadowElem extends _Elem {
  constructor() {
    super()
    this.root = this.attachShadow({mode: 'open'})
    // clone template
    let tplId = (this.constructor as CustomElementConstructor).tag
    let tpl = (doc.qId(tplId) as HTMLTemplateElement).content
    this.root.appendChild(tpl.cloneNode(true))
    // style
    this.root.adoptedStyleSheets = [shadowStyle]
  }
}

// light DOM element
class LightElem extends _Elem {
  constructor() {
    super()
    this.root = this
    // use template
    let tplId = (this.constructor as CustomElementConstructor).tag
    // a template is optional for light DOM elements
    let tpl = (doc.qId(tplId) as HTMLTemplateElement)?.content
    tpl && doc.body.appendChild(tpl)
    // construction
    this.init()
  }
}

let defElem = (tag: str, cls: CustomElementConstructor) => {
  cls.tag = tag
  customElements.define(tag, cls)
}
