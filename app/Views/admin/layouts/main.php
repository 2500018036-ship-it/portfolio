<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Admin Panel') ?> | Portfolio Admin</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        .admin-form-modal .modal-body {
            background: #f4f6f9;
        }

        .admin-form-modal .modal-body > form > .card {
            margin-bottom: 0;
            box-shadow: none;
        }

        .admin-form-modal .modal-body > form > .card > .card-header {
            display: none;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?= $this->include('admin/partials/navbar') ?>
        <?= $this->include('admin/partials/sidebar') ?>

        <div class="content-wrapper">
            <?= $this->include('admin/partials/breadcrumb') ?>

            <section class="content">
                <div class="container-fluid">
                    <?= $this->include('admin/partials/flash') ?>
                    <?= $this->renderSection('content') ?>
                </div>
            </section>
        </div>

        <?= $this->include('admin/partials/footer') ?>
    </div>

    <div class="modal fade admin-form-modal" id="adminFormModal" tabindex="-1" role="dialog" aria-labelledby="adminFormModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminFormModalTitle">Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-circle-notch fa-spin mr-1"></i> Loading form...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script>
        (() => {
            const sidebar = document.querySelector('.main-sidebar .sidebar');
            const activeLink = document.querySelector('.main-sidebar .nav-link.active');
            const storageKey = 'portfolio_admin_sidebar_scroll';

            if (!sidebar) {
                return;
            }

            const restoreScroll = () => {
                const savedScroll = Number(sessionStorage.getItem(storageKey));

                if (Number.isFinite(savedScroll) && savedScroll > 0) {
                    sidebar.scrollTop = savedScroll;
                    return;
                }

                if (activeLink) {
                    activeLink.scrollIntoView({ block: 'nearest' });
                }
            };

            requestAnimationFrame(() => {
                restoreScroll();
                requestAnimationFrame(restoreScroll);
            });

            sidebar.addEventListener('scroll', () => {
                sessionStorage.setItem(storageKey, String(sidebar.scrollTop));
            }, { passive: true });

            document.addEventListener('click', (event) => {
                const link = event.target.closest('.main-sidebar a[href]');

                if (!link || link.classList.contains('disabled')) {
                    return;
                }

                sessionStorage.setItem(storageKey, String(sidebar.scrollTop));
            });
        })();

        (() => {
            const modalElement = document.getElementById('adminFormModal');
            const modalBody = modalElement?.querySelector('.modal-body');
            const modalTitle = modalElement?.querySelector('.modal-title');
            const parser = new DOMParser();

            if (!modalElement || !modalBody || !modalTitle || !window.jQuery) {
                return;
            }

            const loading = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-circle-notch fa-spin mr-1"></i> Loading form...
                </div>
            `;

            const errorView = (url) => `
                <div class="alert alert-danger mb-0">
                    Form tidak bisa dimuat di modal.
                    <a href="${url}" class="alert-link">Buka halaman form</a>.
                </div>
            `;

            const isModalFormLink = (link) => {
                if (!link || link.target === '_blank' || link.dataset.noModal === '1') {
                    return false;
                }

                const url = new URL(link.href, window.location.href);

                if (url.origin !== window.location.origin) {
                    return false;
                }

                const path = url.pathname.replace(/\/+/g, '/');

                return /\/(?:index\.php\/)?admin\/.+(?:\/new|\/\d+\/edit)$/.test(path);
            };

            const cleanTitle = (value, fallback) => {
                const title = (value || fallback || 'Form').trim();

                return title.replace(/\s+\|\s+Portfolio Admin$/i, '');
            };

            const prepareForm = (form) => {
                const cloned = form.cloneNode(true);
                const cancelLink = cloned.querySelector('.card-footer a.btn-secondary');

                if (cancelLink) {
                    const closeButton = document.createElement('button');
                    closeButton.type = 'button';
                    closeButton.className = cancelLink.className;
                    closeButton.setAttribute('data-dismiss', 'modal');
                    closeButton.textContent = cancelLink.textContent.trim() || 'Cancel';
                    cancelLink.replaceWith(closeButton);
                }

                cloned.querySelectorAll('input[id], select[id], textarea[id]').forEach((field) => {
                    field.id = `${field.id}_modal`;
                });

                cloned.querySelectorAll('label[for]').forEach((label) => {
                    label.setAttribute('for', `${label.getAttribute('for')}_modal`);
                });

                return cloned;
            };

            const openFormModal = async (url, fallbackTitle) => {
                modalTitle.textContent = cleanTitle(fallbackTitle, 'Form');
                modalBody.innerHTML = loading;
                window.jQuery(modalElement).modal('show');

                try {
                    const response = await fetch(url, {
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }

                    const html = await response.text();
                    const documentFragment = parser.parseFromString(html, 'text/html');
                    const form = documentFragment.querySelector('form');

                    if (!form) {
                        window.location.href = url;
                        return;
                    }

                    const title = documentFragment.querySelector('.card-title')?.textContent
                        || documentFragment.querySelector('title')?.textContent
                        || fallbackTitle;

                    modalTitle.textContent = cleanTitle(title, fallbackTitle);
                    modalBody.innerHTML = '';
                    modalBody.appendChild(prepareForm(form));

                    const firstField = modalBody.querySelector('input:not([type="hidden"]), select, textarea');
                    if (firstField) {
                        setTimeout(() => firstField.focus(), 200);
                    }
                } catch (error) {
                    modalBody.innerHTML = errorView(url);
                }
            };

            document.addEventListener('click', (event) => {
                const link = event.target.closest('a[href]');

                if (!isModalFormLink(link)) {
                    return;
                }

                event.preventDefault();
                openFormModal(link.href, link.textContent);
            });

            modalElement.addEventListener('hidden.bs.modal', () => {
                modalBody.innerHTML = loading;
            });
        })();
    </script>
</body>

</html>
