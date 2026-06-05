// Interaksi sisi klien — vanilla JS, tanpa library.

// Toggle menu navigasi di mobile.
function toggleMenu() {
  var nav = document.getElementById('mainNav');
  if (nav) nav.classList.toggle('open');
}

document.addEventListener('DOMContentLoaded', function () {
  // Dropdown (kategori & user) — toggle saat diklik (selain hover desktop).
  document.querySelectorAll('.nav-dropdown-toggle').forEach(function (toggle) {
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      var parent = toggle.closest('.nav-dropdown');
      parent.classList.toggle('open');
    });
  });

  // Konfirmasi sebelum aksi hapus.
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('submit', function (e) {
      if (!confirm(el.getAttribute('data-confirm'))) e.preventDefault();
    });
  });
  document.querySelectorAll('a[data-confirm]').forEach(function (el) {
    el.addEventListener('click', function (e) {
      if (!confirm(el.getAttribute('data-confirm'))) e.preventDefault();
    });
  });

  // Validasi sederhana form bertanda [data-validate].
  document.querySelectorAll('form[data-validate]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      var invalid = false;
      form.querySelectorAll('[required]').forEach(function (input) {
        if (!input.value.trim()) {
          invalid = true;
          input.style.borderColor = '#dc2626';
        } else {
          input.style.borderColor = '';
        }
      });
      if (invalid) {
        e.preventDefault();
        alert('Mohon lengkapi semua kolom yang wajib diisi.');
      }
    });
  });
});
