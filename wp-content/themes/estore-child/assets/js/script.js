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
                    t.classList.toggle('border-white/25', on);
                });
            });
        });
    }

    function initAOS() {
        if (typeof AOS === 'undefined') return;
        var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        AOS.init({ duration: reduce ? 0 : 700, once: true, offset: 80, disable: reduce });
    }
})();
