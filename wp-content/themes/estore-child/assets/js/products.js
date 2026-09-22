// Products page: swap the grid when the category dropdown changes.
// Loaded only by templates/products.php; ajax URL and nonce come from the form.
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('product-filter');
    var grid = document.getElementById('products-grid');
    var select = document.getElementById('category');
    if (!form || !grid || !select) return;

    var active = null;

    select.addEventListener('change', function () {
        var termId = select.value;

        // Drop any in-flight response so fast switching can't land out of order.
        if (active) active.abort();
        active = new AbortController();
        grid.setAttribute('aria-busy', 'true');

        fetch(form.dataset.ajaxUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: 'estore_filter_products',
                category: termId,
                nonce: form.dataset.nonce
            }).toString(),
            signal: active.signal
        })
            .then(function (r) { return r.json(); })
            .then(function (payload) {
                if (!payload || !payload.success) return;
                grid.innerHTML = payload.data.html;

                // Keep the URL shareable without reloading.
                var url = new URL(window.location.href);
                if (termId && termId !== '0') url.searchParams.set('category', termId);
                else url.searchParams.delete('category');
                window.history.replaceState({}, '', url);
            })
            .catch(function (err) {
                // Leave the previous grid in place rather than blanking it.
                if (err.name !== 'AbortError') console.error('Product filter failed:', err);
            })
            .finally(function () { grid.setAttribute('aria-busy', 'false'); });
    });
});
