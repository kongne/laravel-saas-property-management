import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

document.addEventListener('alpine:init', () => {
    Alpine.data('scrollReveal', () => ({
        visible: false,
        init() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.visible = true;
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            observer.observe(this.$el);
        }
    }));

    Alpine.data('globalSearch', () => ({
        open: false,
        query: '',
        results: [],
        loading: false,
        selectedIndex: 0,
        searching: false,
        
        init() {
            this.$watch('query', () => {
                if (this.query.length < 2) {
                    this.results = [];
                    this.searching = false;
                    return;
                }
                this.searching = true;
                this.debouncedSearch();
            });
        },

        debouncedSearch: null,

        async search() {
            this.loading = true;
            this.selectedIndex = 0;
            try {
                const res = await fetch(`/search?q=${encodeURIComponent(this.query)}`);
                if (!res.ok) throw new Error('Search failed');
                const data = await res.json();
                this.results = data.results || [];
            } catch (e) {
                this.results = [];
            } finally {
                this.loading = false;
            }
        },

        openSearch() {
            this.open = true;
            this.query = '';
            this.results = [];
            this.searching = false;
            this.$nextTick(() => {
                this.$refs.searchInput?.focus();
            });
        },

        closeSearch() {
            this.open = false;
            this.query = '';
            this.results = [];
            this.searching = false;
        },

        select(index) {
            const item = this.results[index];
            if (item) {
                window.location.href = item.url;
            }
        },

        onKeydown(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                this.selectedIndex = Math.min(this.selectedIndex + 1, this.results.length - 1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                this.selectedIndex = Math.max(this.selectedIndex - 1, 0);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                this.select(this.selectedIndex);
            } else if (e.key === 'Escape') {
                this.closeSearch();
            }
        },

        getResultIcon(type) {
            const icons = {
                property: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                unit: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>',
                tenant: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
                lease: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
                payment: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>',
                maintenance: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            };
            return icons[type] || '';
        },

        getResultBadge(type) {
            const badges = {
                property: 'bg-indigo-100 text-indigo-700',
                unit: 'bg-cyan-100 text-cyan-700',
                tenant: 'bg-amber-100 text-amber-700',
                lease: 'bg-emerald-100 text-emerald-700',
                payment: 'bg-rose-100 text-rose-700',
                maintenance: 'bg-violet-100 text-violet-700',
            };
            return badges[type] || 'bg-slate-100 text-slate-700';
        }
    }));

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('[type="submit"]');
            if (btn && !btn.dataset.noLoading && !btn.closest('[data-no-loading]')) {
                btn.disabled = true;
                btn.classList.add('is-loading');
            }
        });
    });
});

document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchBtn = document.querySelector('[data-global-search]');
        if (searchBtn) searchBtn.click();
    }
});

Alpine.start();
