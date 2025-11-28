<!-- Select2 CSS + Initialization loader
     We dynamically inject Select2 CSS/JS only after jQuery is available so
     the plugin won't execute before jQuery is registered by Vite. -->
<style>
    .select2-container { width: 100% !important; }
    .select2-container--bootstrap-5 .select2-selection--multiple { border: 1px solid #dee2e6; border-radius: 0.375rem; background-color: #fff; min-height: 38px; padding: 5px; }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice { background-color: #0d6efd; border-color: #0d6efd; color: white; padding: 4px 8px; border-radius: 3px; margin: 2px; }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove { color: white; margin-right: 3px; }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover { color: #fff; }
    .select2-container--bootstrap-5.select2-container--open .select2-dropdown { border-color: #dee2e6; border-radius: 0.375rem; }
    .select2-dropdown { direction: rtl; text-align: right; }
    .select2-search__field { direction: rtl; text-align: right; }
    .select2-results__option { text-align: right; direction: rtl; }
</style>

<script>
    (function(){
        const cssUrls = [
            'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css'
        ];
        const jsUrl = 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js';

        function loadCss(href) {
            if (document.querySelector('link[href="' + href + '"]')) return;
            const l = document.createElement('link');
            l.rel = 'stylesheet'; l.href = href; l.crossOrigin = 'anonymous';
            document.head.appendChild(l);
        }

        function loadScript(src, cb) {
            if (document.querySelector('script[src="' + src + '"]')) {
                return cb && cb();
            }
            const s = document.createElement('script');
            s.src = src; s.async = false; s.crossOrigin = 'anonymous';
            s.onload = function(){ cb && cb(); };
            s.onerror = function(){ cb && cb(new Error('Failed to load ' + src)); };
            document.body.appendChild(s);
        }

        function initPlugin() {
            try {
                if (typeof $ === 'undefined' || typeof $.fn === 'undefined') {
                    // jQuery still not available, retry shortly
                    setTimeout(initPlugin, 80);
                    return;
                }

                $(function(){
                    if (!$.fn.select2) {
                        console.warn('select2 is not registered on jQuery');
                        return;
                    }
                    $('#categories').select2({
                        placeholder: 'اختر الفئات',
                        allowClear: true,
                        width: '100%',
                        theme: 'bootstrap-5',
                        language: {
                            searching: function() { return 'جاري البحث...'; },
                            noResults: function() { return 'لا توجد نتائج'; }
                        }
                    });
                });
            } catch (e) {
                console.error('Select2 init error', e);
            }
        }

        // Load CSS immediately (safe)
        cssUrls.forEach(loadCss);

        // Wait for jQuery then load the select2 script and initialize
        (function waitForjQuery() {
            if (typeof $ === 'undefined') {
                setTimeout(waitForjQuery, 80);
                return;
            }
            // jQuery exists, load select2 JS
            loadScript(jsUrl, function(err){
                if (err) return console.error(err);
                initPlugin();
            });
        })();
    })();
</script>



