import 'alpinejs'
import 'focus-visible'
import 'highlight.js/styles/github.css'
import 'styles/tailwind.pcss'
import bash from 'highlight.js/lib/languages/bash'
import hljs from 'highlight.js/lib/core'
import ini from 'highlight.js/lib/languages/ini'
import javascript from 'highlight.js/lib/languages/javascript'
import php from 'highlight.js/lib/languages/php'
import yaml from 'highlight.js/lib/languages/yaml'

hljs.registerLanguage('bash', bash);
hljs.registerLanguage('ini', ini);
hljs.registerLanguage('javascript', javascript);
hljs.registerLanguage('php', php);
hljs.registerLanguage('yaml', yaml);

document.addEventListener('DOMContentLoaded', event => {
  document.querySelectorAll('pre code').forEach(block => {
    hljs.highlightBlock(block)
  })
})
