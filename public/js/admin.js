(function () {
    // ---- Sidebar (mobile) ----
    var sidebar = document.getElementById('cxSidebar');
    var burger = document.getElementById('cxBurger');
    var scrim = document.getElementById('cxScrim');

    function setOpen(open) {
        sidebar.classList.toggle('is-open', open);
        scrim.classList.toggle('is-open', open);
        burger.setAttribute('aria-expanded', open ? 'true' : 'false');
    }
    if (sidebar && burger && scrim) {
        burger.addEventListener('click', function () { setOpen(!sidebar.classList.contains('is-open')); });
        scrim.addEventListener('click', function () { setOpen(false); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setOpen(false); });
    }

    // ---- "Pilih kolom": sembunyikan/tampilkan kolom tabel, diingat per tabel ----
    var table = document.querySelector('[data-cx-table]');
    var menu = document.querySelector('[data-cx-colmenu]');
    if (table && menu) {
        var key = 'cx-kolom:' + table.getAttribute('data-cx-table');
        var hidden = [];
        try { hidden = JSON.parse(localStorage.getItem(key) || '[]'); } catch (e) { hidden = []; }

        var apply = function () {
            table.querySelectorAll('[data-col]').forEach(function (cell) {
                cell.hidden = hidden.indexOf(cell.getAttribute('data-col')) !== -1;
            });
            menu.querySelectorAll('input[data-col-toggle]').forEach(function (box) {
                box.checked = hidden.indexOf(box.getAttribute('data-col-toggle')) === -1;
            });
        };

        menu.addEventListener('change', function (e) {
            var box = e.target.closest('input[data-col-toggle]');
            if (!box) return;
            var name = box.getAttribute('data-col-toggle');
            hidden = hidden.filter(function (x) { return x !== name; });
            if (!box.checked) hidden.push(name);
            try { localStorage.setItem(key, JSON.stringify(hidden)); } catch (e2) { /* abaikan */ }
            apply();
        });
        apply();
    }

    // ---- Select yang langsung mengirim form (filter, baris per halaman) ----
    document.querySelectorAll('[data-autosubmit]').forEach(function (el) {
        el.addEventListener('change', function () { el.form.submit(); });
    });
})();
