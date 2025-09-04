import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();


// Fallback: toggler do sidebar se Alpine não estiver ativo por algum motivo
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[aria-label="Alternar menu lateral"]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      // Se Alpine não capturar, ainda assim alterna
      setTimeout(() => {
        if (!document.body.classList.contains('sidebar-collapse')) {
          document.body.classList.toggle('sidebar-collapse');
        }
      }, 0);
    }, { once: false });
  });
});
