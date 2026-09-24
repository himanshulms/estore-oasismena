/**
 * estore.oasismena - global front-end behaviour.
 *
 * Sliders are configured from markup, not here: put data-slides-per-view,
 * data-slides-tablet, data-slides-mobile, data-space-between and data-loop
 * on any .swiper element and initSwipers() picks them up.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        initHeader();
        initDrawer();
        initBackToTop();
        initSwipers();
        initGallery();
        initQuoteModal();
        initCategoryDropdown();
        initAOS();
    });

    /* Sticky header: solid background once scrolled past the hero edge. */
    function initHeader() {
        var header = document.getElementById('site-header');
        if (!header) return;

        var onScroll = function () {
            header.classList.toggle('is-stuck', window.scrollY > 8);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    /* Mobile drawer. Focus returns to the toggle on close. */
    function initDrawer() {
        var drawer = document.getElementById('mobile-drawer');
        var backdrop = document.getElementById('drawer-backdrop');
        var openBtn = document.getElementById('drawer-open');
        var closeBtn = document.getElementById('drawer-close');
        if (!drawer || !openBtn) return;

        function setOpen(open) {
            drawer.classList.toggle('is-open', open);
            drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
            openBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
            document.body.style.overflow = open ? 'hidden' : '';

            if (backdrop) {
                backdrop.classList.toggle('opacity-100', open);
                backdrop.classList.toggle('visible', open);
                backdrop.classList.toggle('opacity-0', !open);
                backdrop.classList.toggle('invisible', !open);
            }
            if (!open) openBtn.focus();
        }

        openBtn.addEventListener('click', function () { setOpen(true); });
        if (closeBtn) closeBtn.addEventListener('click', function () { setOpen(false); });
        if (backdrop) backdrop.addEventListener('click', function () { setOpen(false); });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && drawer.classList.contains('is-open')) setOpen(false);
        });

        // Tapping a link inside the drawer should close it.
        drawer.addEventListener('click', function (e) {
            if (e.target.closest('a')) setOpen(false);
        });
    }

    function initBackToTop() {
        var btn = document.getElementById('backtotop');
        if (!btn) return;

        var onScroll = function () {
            btn.classList.toggle('is-visible', window.scrollY > 600);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });

        btn.addEventListener('click', function () {
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
        });
    }

    /* Generic Swiper initialiser - every .swiper is configured from its own
       data attributes, so adding a slider needs no JS change. */
    function initSwipers() {
        if (typeof Swiper === 'undefined') return;

        document.querySelectorAll('.swiper').forEach(function (el) {
            var d = el.dataset;
            var num = function (v, fallback) {
                var n = parseFloat(v);
                return isNaN(n) ? fallback : n;
            };

            new Swiper(el, {
                slidesPerView: num(d.slidesMobile, 1),
                spaceBetween: num(d.spaceBetween, 24),
                loop: d.loop === 'true',
                autoplay: d.autoplay === 'true' ? { delay: num(d.autoplayDelay, 5000), disableOnInteraction: false } : false,
                pagination: el.querySelector('.swiper-pagination') ? { el: el.querySelector('.swiper-pagination'), clickable: true } : false,
                navigation: el.querySelector('.swiper-button-next') ? {
                    nextEl: el.querySelector('.swiper-button-next'),
                    prevEl: el.querySelector('.swiper-button-prev')
                } : false,
                breakpoints: {
                    768: { slidesPerView: num(d.slidesTablet, num(d.slidesPerView, 2)) },
                    1024: { slidesPerView: num(d.slidesPerView, 3) }
                }
            });
        });
    }

    /* Product gallery: thumbnails swap the main image in place. */
    function initGallery() {
        var main = document.getElementById('gallery-main');
        var thumbs = document.querySelectorAll('.gallery-thumb');
        if (!main || !thumbs.length) return;

        thumbs.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var full = btn.dataset.full;
                if (!full) return;
                main.src = full;
                thumbs.forEach(function (t) {
                    var on = t === btn;
                    t.setAttribute('aria-selected', on ? 'true' : 'false');
                    t.classList.toggle('ring-2', on);
                    t.classList.toggle('ring-white/40', on);
                });
            });
        });
    }

    /* Request a Quote dialog. Intercepts the quote buttons, prefills the
       product and category, and restores focus on close. The buttons keep a
       real href to the contact page, so this is an enhancement, not a
       dependency. */
    function initQuoteModal() {
        var modal = document.getElementById('quote-modal');
        if (!modal) return;

        var panel = modal.querySelector('.quote-modal__panel');
        var opener = null;

        function setField(name, value) {
            var el = modal.querySelector('[name="' + name + '"]');
            if (!el || !value) return;
            // Only select an option that actually exists, so a missing product
            // leaves the dropdown on its placeholder rather than blank.
            if (el.tagName === 'SELECT') {
                var match = Array.prototype.find.call(el.options, function (o) {
                    return o.value === value;
                });
                if (match) el.value = value;
            } else {
                el.value = value;
            }
        }

        function open(trigger) {
            opener = trigger || null;
            if (trigger) {
                setField('quote-product', trigger.getAttribute('data-quote-product') || '');
                setField('quote-category', trigger.getAttribute('data-quote-category') || '');
            }
            modal.hidden = false;
            document.body.style.overflow = 'hidden';
            var first = modal.querySelector('input, select, textarea, button');
            if (first) first.focus();
        }

        function close() {
            modal.hidden = true;
            document.body.style.overflow = '';
            if (opener) { opener.focus(); opener = null; }
        }

        document.addEventListener('click', function (e) {
            var trigger = e.target.closest('.btn-quote, [data-quote-open]');
            if (trigger) { e.preventDefault(); open(trigger); return; }
            if (e.target.closest('[data-quote-close]')) { e.preventDefault(); close(); }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.hidden) close();
            if (e.key !== 'Tab' || modal.hidden || !panel) return;
            var f = panel.querySelectorAll('a[href], button, input, select, textarea');
            f = Array.prototype.filter.call(f, function (el) { return !el.disabled && el.offsetParent !== null; });
            if (!f.length) return;
            var first = f[0], last = f[f.length - 1];
            if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
            else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
        });

        // Close once CF7 reports the message was sent.
        document.addEventListener('wpcf7mailsent', function () { setTimeout(close, 1600); });
    }

    /* Replace the native category <select> with a styled listbox. The select
       stays in the DOM and remains the source of truth, so the existing AJAX
       filter keeps listening to its change event and the no-JS form still
       submits. */
    function initCategoryDropdown() {
        var select = document.querySelector('.cat-select');
        if (!select || select.dataset.enhanced) return;
        select.dataset.enhanced = '1';

        var wrap = document.createElement('div');
        wrap.className = 'cat-dropdown';

        var toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.className = 'cat-dropdown__toggle';
        toggle.setAttribute('aria-haspopup', 'listbox');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.innerHTML = '<span class="cat-dropdown__label"></span>' +
            '<svg width="12" height="8" viewBox="0 0 12 8" fill="none" stroke="currentColor" ' +
            'stroke-width="1.875" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
            '<path d="M1 1L6 7L11 1"/></svg>';

        var panel = document.createElement('ul');
        panel.className = 'cat-dropdown__panel';
        panel.setAttribute('role', 'listbox');
        panel.hidden = true;

        var options = [];
        Array.prototype.forEach.call(select.options, function (opt, i) {
            var li = document.createElement('li');
            var btn = document.createElement('button');
            btn.type = 'button';
            // The server indents brands with a dash; detect and restyle rather
            // than re-deriving the hierarchy here.
            var raw = opt.textContent.replace(/\u00a0/g, ' ').trim();
            var child = /^[\u2014-]\s/.test(raw);
            btn.className = 'cat-dropdown__option' + (child ? ' cat-dropdown__option--child' : '');
            btn.textContent = child ? raw.replace(/^[\u2014-]\s*/, '') : raw;
            btn.setAttribute('role', 'option');
            btn.dataset.index = i;
            btn.setAttribute('aria-selected', opt.selected ? 'true' : 'false');
            li.appendChild(btn);
            panel.appendChild(li);
            options.push(btn);
        });

        function label() {
            var o = select.options[select.selectedIndex];
            wrap.querySelector('.cat-dropdown__label').textContent =
                o ? o.textContent.replace(/\u00a0/g, ' ').replace(/^[\u2014-]\s*/, '').trim() : '';
            options.forEach(function (b, i) {
                b.setAttribute('aria-selected', i === select.selectedIndex ? 'true' : 'false');
            });
        }

        function open() {
            panel.hidden = false;
            toggle.setAttribute('aria-expanded', 'true');
            var cur = options[select.selectedIndex];
            if (cur) cur.classList.add('is-active');
        }
        function close() {
            panel.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
            options.forEach(function (b) { b.classList.remove('is-active'); });
        }

        toggle.addEventListener('click', function () {
            panel.hidden ? open() : close();
        });

        panel.addEventListener('click', function (e) {
            var btn = e.target.closest('.cat-dropdown__option');
            if (!btn) return;
            select.selectedIndex = parseInt(btn.dataset.index, 10);
            select.dispatchEvent(new Event('change', { bubbles: true }));
            label();
            close();
            toggle.focus();
        });

        document.addEventListener('click', function (e) {
            if (!wrap.contains(e.target)) close();
        });

        wrap.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !panel.hidden) { close(); toggle.focus(); return; }
            if (panel.hidden) {
                if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); options[select.selectedIndex].focus(); }
                return;
            }
            var idx = options.indexOf(document.activeElement);
            if (e.key === 'ArrowDown') { e.preventDefault(); (options[idx + 1] || options[0]).focus(); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); (options[idx - 1] || options[options.length - 1]).focus(); }
            else if (e.key === 'Home') { e.preventDefault(); options[0].focus(); }
            else if (e.key === 'End') { e.preventDefault(); options[options.length - 1].focus(); }
        });

        select.parentNode.insertBefore(wrap, select);
        wrap.appendChild(toggle);
        wrap.appendChild(panel);
        wrap.appendChild(select);
        select.classList.add('sr-only');
        select.setAttribute('tabindex', '-1');
        select.setAttribute('aria-hidden', 'true');
        label();
    }

    function initAOS() {
        if (typeof AOS === 'undefined') return;
        var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        AOS.init({ duration: reduce ? 0 : 700, once: true, offset: 80, disable: reduce });
    }
})();
