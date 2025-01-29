<svg></svg>
<span></span>
<s-ico meh name='meh'></s-ico>
<style>
  :host {
    width: 13rem;
    height: 3rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 2rem;
    cursor: pointer;
    color: white;
    background: blue;
    border: thick solid red;
  }

  :host(:hover) {
    filter: brightness(1.1);
  }

  svg {
    width: 2em;
    height: 2em;
  }

  s-ico {
    width: 2em;
    height: 2em;
  }
</style>
