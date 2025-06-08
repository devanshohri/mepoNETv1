document.querySelectorAll('.dropdown-sidebar-button').forEach(div => {
  div.addEventListener('click', () => {
    const isOpen = div.classList.toggle('open');
    div.setAttribute('aria-expanded', isOpen);
    const menu = div.nextElementSibling;
    if (menu) {
      if (isOpen) {
        menu.removeAttribute('hidden');
      } else {
        menu.setAttribute('hidden', '');
      }
    }
  });

  // Optional: toggle on keyboard Enter/Space keys for accessibility
  div.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      div.click();
    }
  });
});

