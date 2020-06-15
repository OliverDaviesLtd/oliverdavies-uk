import '../css/tailwind.pcss'
import 'alpinejs'
import hljs from 'highlightjs'

document.addEventListener('DOMContentLoaded', event => {
  document.querySelectorAll('pre code').forEach(block => {
    hljs.highlightBlock(block)
  })
})
