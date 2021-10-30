import 'virtual:windi.css';
import App from './App.svelte'
import Button from './button.svelte'

const app = new App({
  target: document.getElementById('app')
})

export function testingExport() {
  new Button({target: document.body});
}