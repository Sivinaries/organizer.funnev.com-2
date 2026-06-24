<!-- Page Loading Overlay -->
<div id="page-loading"
    class="hidden fixed inset-0 z-[100] bg-white/70 backdrop-blur-sm flex items-center justify-center">
    <svg class="animate-spin h-10 w-10 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none"
        viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
    </svg>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pageLoading = document.getElementById('page-loading');
        const forms = document.querySelectorAll('form');

        /* ============ Helper validasi sisi-klien (pesan ramah) ============ */
        function fieldLabel(el) {
            if (el.dataset.label) return el.dataset.label;
            const wrap = el.closest('div');
            const lbl = wrap ? wrap.querySelector('label') : null;
            return lbl ? lbl.textContent.replace('*', '').trim() : 'Kolom ini';
        }
        function clearErr(el) {
            el.classList.remove('ring-2', 'ring-red-400', 'border-red-400');
            const wrap = el.closest('div');
            if (wrap) {
                const m = wrap.querySelector('.js-error-msg');
                if (m) m.remove();
            }
        }
        function showErr(el, msg) {
            el.classList.add('ring-2', 'ring-red-400', 'border-red-400');
            const wrap = el.closest('div');
            if (wrap && !wrap.querySelector('.js-error-msg')) {
                const p = document.createElement('p');
                p.className = 'js-error-msg text-xs text-red-500 mt-1';
                p.textContent = msg;
                wrap.appendChild(p);
            }
        }
        function htmlEmpty(v) {
            return !v || !v.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, '').trim();
        }
        function validateForm(form) {
            let firstInvalid = null;
            form.querySelectorAll('[required], [data-required]').forEach(function (el) {
                clearErr(el);
                let empty;
                if (el.type === 'checkbox' || el.type === 'radio') {
                    empty = !el.checked;
                } else if (el.tagName === 'TEXTAREA') {
                    let val = el.value;
                    // Jika textarea ini editor Summernote, ambil konten via API agar akurat
                    if (window.jQuery) {
                        const $el = window.jQuery(el);
                        if ($el.next('.note-editor').length) {
                            try { val = $el.summernote('code'); } catch (e) {}
                        }
                    }
                    empty = htmlEmpty(val);
                } else {
                    empty = !String(el.value || '').trim();
                }
                if (empty) {
                    showErr(el, fieldLabel(el) + ' wajib diisi.');
                    if (!firstInvalid) firstInvalid = el;
                }
            });
            return firstInvalid;
        }

        // Bersihkan tanda error saat user mulai mengisi
        document.addEventListener('input', function (e) {
            if (e.target.matches && e.target.matches('[required], [data-required]')) clearErr(e.target);
        });
        document.addEventListener('change', function (e) {
            if (e.target.matches && e.target.matches('[required], [data-required]')) clearErr(e.target);
        });

        forms.forEach(form => {
            form.addEventListener('submit', function (ev) {

                /* CASE 1: SEARCH / PAGE LOADING (overlay) */
                if (form.hasAttribute('data-page-loading')) {
                    if (pageLoading) pageLoading.classList.remove('hidden');
                    return;
                }

                /* CASE 2: Validasi sisi-klien (form modal CRUD) */
                if (form.hasAttribute('data-validate')) {
                    const invalid = validateForm(form);
                    if (invalid) {
                        ev.preventDefault();
                        invalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        if (typeof invalid.focus === 'function') invalid.focus({ preventScroll: true });
                        return; // jangan tampilkan spinner; modal tetap terbuka
                    }
                }

                /* Bersihkan pemisah ribuan pada field currency sebelum dikirim ke server */
                form.querySelectorAll('.currency').forEach(function (el) {
                    el.value = String(el.value).replace(/\./g, '');
                });

                /* CASE 3: Spinner tombol submit */
                const btn = form.querySelector('button[type="submit"]');
                if (!btn || btn.disabled) return;

                const originalWidth = btn.offsetWidth;
                btn.style.width = `${originalWidth}px`;
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');

                btn.innerHTML = `
                    <div class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span>Processing...</span>
                    </div>
                `;
            });
        });
    });
</script>
