'use strict';

const password = document.querySelector('#password');
const toggle = document.querySelector('.password-toggle');
const capsWarning = document.querySelector('#caps-warning');
toggle.hidden = false;
toggle.addEventListener('click', () => {
  const visible = password.type === 'password';
  password.type = visible ? 'text' : 'password';
  toggle.setAttribute('aria-pressed', String(visible));
  toggle.setAttribute('aria-label', visible ? '隱藏密碼' : '顯示密碼');
});
for (const event of ['keydown', 'keyup']) {
    password.addEventListener(event, (e) => {
        const isCapsLock = typeof e.getModifierState === 'function'
            ? e.getModifierState('CapsLock')
            : false;

        capsWarning.hidden = !isCapsLock;
    });
}
password.addEventListener('blur', () => { capsWarning.hidden = true; });
document.querySelectorAll('[data-help]').forEach((button) => {
  button.setAttribute('aria-controls', 'help-message');
  button.setAttribute('aria-expanded', 'false');
  button.addEventListener('click', () => {
    const message = document.querySelector('#help-message');
    message.hidden = !message.hidden;
    document.querySelectorAll('[data-help]').forEach((item) => {
      item.setAttribute('aria-expanded', String(!message.hidden));
    });
  });
});
document.querySelector('#login-form').addEventListener('submit', () => {
  const button = document.querySelector('.submit-button');
  button.disabled = true;
  button.querySelector('span').textContent = '正在登入…';
});
window.addEventListener('pageshow', () => {
  const button = document.querySelector('.submit-button');
  button.disabled = false;
  button.querySelector('span').textContent = '登入工作空間';
});
