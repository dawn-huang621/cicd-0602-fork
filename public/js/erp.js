'use strict';

// 共用導覽；不接管既有 DataTables、AJAX 或業務操作。
const menuToggle = document.querySelector('.erp-menu-toggle');
const navigation = document.querySelector('#erp-navigation');
if (menuToggle && navigation) {
  const closeMenu = () => {
    menuToggle.setAttribute('aria-expanded', 'false');
    navigation.classList.remove('is-open');
  };
  menuToggle.addEventListener('click', () => {
    const open = menuToggle.getAttribute('aria-expanded') !== 'true';
    menuToggle.setAttribute('aria-expanded', String(open));
    navigation.classList.toggle('is-open', open);
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeMenu();
      document.querySelectorAll('.erp-user[open]').forEach((item) => { item.open = false; });
    }
  });
  document.addEventListener('click', (event) => {
    if (!event.target.closest('.erp-nav-inner')) closeMenu();
    document.querySelectorAll('.erp-user[open]').forEach((item) => {
      if (!item.contains(event.target)) item.open = false;
    });
  });
}

// 僅用於 data-erp-table 靜態表格。伺服器／DataTables 表格由原頁面管理。
document.querySelectorAll('[data-erp-table]').forEach((table) => {
  const card = table.closest('.erp-card');
  const search = card?.querySelector('[data-table-search]');
  const count = card?.querySelector('[data-table-count]');
  const rows = Array.from(table.tBodies[0]?.rows || []);
  const filter = () => {
    const query = (search?.value || '').trim().toLocaleLowerCase();
    let visible = 0;
    rows.forEach((row) => {
      row.hidden = !row.textContent.toLocaleLowerCase().includes(query);
      if (!row.hidden) visible++;
    });
    if (count) count.textContent = `顯示 ${visible} 筆，共 ${rows.length} 筆資料`;
    const empty = card?.querySelector('[data-table-empty]');
    if (empty) empty.hidden = visible !== 0;
  };
  search?.addEventListener('input', filter);
  filter();
});
