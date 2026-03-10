import './bootstrap';

const loadingOverlay = document.getElementById('page-loader');

function showPageLoader() {
    if (!loadingOverlay) {
        return;
    }

    loadingOverlay.classList.remove('opacity-0', 'pointer-events-none');
}

window.addEventListener('load', () => {
    if (!loadingOverlay) {
        return;
    }

    loadingOverlay.classList.add('opacity-0', 'pointer-events-none');
});

document.addEventListener('click', (event) => {
    const link = event.target.closest('a');

    if (!link) {
        return;
    }

    const href = link.getAttribute('href');
    const target = link.getAttribute('target');

    if (!href || href.startsWith('#') || target === '_blank') {
        return;
    }

    if (link.hasAttribute('data-no-loader')) {
        return;
    }

    const currentUrl = new URL(window.location.href);
    const nextUrl = new URL(link.href, window.location.origin);

    if (currentUrl.href === nextUrl.href) {
        return;
    }

    showPageLoader();
});

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) {
        return;
    }

    if (form.hasAttribute('data-no-loader')) {
        return;
    }

    showPageLoader();
});

window.pageLoader = {
    show: showPageLoader,
};
