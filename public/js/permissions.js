'use strict';
(() => {
  const page = document.querySelector('[data-permission-page]');
  if (!page) return;
  const form = page.querySelector('[data-permission-form]');
  const all = page.querySelector('[data-select-all]');
  const search = page.querySelector('[data-module-search]');
  const rows = Array.from(page.querySelectorAll('[data-module-row]'));
  const boxes = form ? Array.from(form.querySelectorAll('input[name="permissions[]"]')) : [];
  const initial = boxes.map((box) => box.checked);
  const save = form?.querySelector('[data-permission-save]');
  const readOnly = save?.disabled;
  let dirty = false;
  let submitting = false;
  let timer;
  const toast = (message) => {
    const element = page.querySelector('[data-permission-toast]');
    element.textContent = message;
    element.hidden = false;
    clearTimeout(timer);
    timer = setTimeout(() => { element.hidden = true; }, 3000);
  };
  const update = () => {
    dirty = boxes.some((box, i) => box.checked !== initial[i]);
    if (all) {
      all.checked = boxes.length > 0 && boxes.every((box) => box.checked);
      all.indeterminate = boxes.some((box) => box.checked) && !all.checked;
    }
    if (boxes.some((box) => !box.disabled)) {
      page.querySelector('[data-save-status]').textContent = dirty ? '有尚未儲存的權限變更' : '目前沒有尚未儲存的變更';
    }
  };
  boxes.forEach((box) => box.addEventListener('change', () => {
    const row = box.closest('tr');
    if (box.dataset.action === 'view' && !box.checked) {
      row.querySelectorAll('input[type="checkbox"]').forEach((item) => { item.checked = false; });
    } else if (box.checked) {
      row.querySelectorAll('[data-action="view"]').forEach((item) => { item.checked = true; });
    }
    update();
  }));
  all?.addEventListener('change', () => {
    boxes.filter((box) => !box.disabled).forEach((box) => { box.checked = all.checked; });
    update();
  });
  search?.addEventListener('input', () => {
    const query = search.value.trim().toLocaleLowerCase();
    rows.forEach((row) => { row.hidden = !row.dataset.moduleName.toLocaleLowerCase().includes(query); });
    page.querySelector('[data-empty]').hidden = rows.some((row) => !row.hidden);
  });
  form?.addEventListener('reset', () => {
    // Restore explicit initial values before updating the aggregate checkbox.
    boxes.forEach((box, i) => { box.checked = initial[i]; });
    update();
    requestAnimationFrame(update);
  });
  form?.addEventListener('submit', () => {
    submitting = true;
    save.disabled = true;
    save.textContent = '正在儲存…';
  });
  window.addEventListener('pageshow', () => {
    submitting = false;
    if (save) { save.disabled = readOnly; save.textContent = '儲存變更 →'; }
  });
  const chooseTab = (members) => {
    page.querySelector('#permissions-panel').hidden = members;
    page.querySelector('#members-panel').hidden = !members;
    ['permissions-tab', 'members-tab'].forEach((id, index) => {
      const tab = page.querySelector('#' + id);
      tab.setAttribute('aria-selected', String(Boolean(index) === members));
      tab.tabIndex = Boolean(index) === members ? 0 : -1;
    });
  };
  page.querySelector('#permissions-tab')?.addEventListener('click', () => chooseTab(false));
  page.querySelector('#members-tab')?.addEventListener('click', () => chooseTab(true));
  page.querySelector('[data-view-members]')?.addEventListener('click', () => {
    chooseTab(true);
    page.querySelector('#members-tab').focus();
  });
  page.querySelector('.tabs')?.addEventListener('keydown', (event) => {
    if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
    event.preventDefault();
    const members = event.key === 'End' || (event.key !== 'Home' && page.querySelector('#permissions-tab').getAttribute('aria-selected') === 'true');
    chooseTab(members);
    page.querySelector(members ? '#members-tab' : '#permissions-tab').focus();
  });
  page.querySelectorAll('[data-role-link]').forEach((link) => link.addEventListener('click', (event) => {
    if (dirty) { event.preventDefault(); toast('請先儲存或取消目前的變更，再切換角色。'); }
  }));
  const dialog = page.querySelector('[data-role-dialog]');
  const roleForm = page.querySelector('[data-role-form]');
  const openDialog = (editing) => {
    if (dirty) { toast('請先儲存或取消目前的權限變更。'); return; }
    roleForm.action = editing ? roleForm.dataset.editUrl : roleForm.dataset.createUrl;
    roleForm.querySelector('[data-rename-method]').disabled = !editing;
    roleForm.querySelector('[data-dialog-title]').textContent = editing ? '編輯角色名稱' : '新增角色';
    roleForm.querySelector('[name="name"]').value = editing ? roleForm.dataset.roleName : '';
    dialog.showModal();
    roleForm.querySelector('[name="name"]').focus();
  };
  page.querySelector('[data-add-role]').addEventListener('click', () => openDialog(false));
  page.querySelector('[data-edit-role]')?.addEventListener('click', () => openDialog(true));
  page.querySelector('[data-close-dialog]').addEventListener('click', () => dialog.close());
  window.addEventListener('beforeunload', (event) => {
    if (dirty && !submitting) { event.preventDefault(); event.returnValue = ''; }
  });
  update();
})();
