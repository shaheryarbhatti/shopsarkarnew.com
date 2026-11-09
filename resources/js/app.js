import './bootstrap';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

Alpine.plugin(collapse);

/**
 * Rich-text editor: turns every <textarea data-rich> into a Quill editor
 * and keeps the underlying textarea in sync for form submission.
 */
function initRichEditors() {
    document.querySelectorAll('textarea[data-rich]').forEach((ta) => {
        if (ta.dataset.richInit) return;
        ta.dataset.richInit = '1';

        const wrapper = document.createElement('div');
        wrapper.className = 'rich-editor rounded-lg border border-gray-300 bg-white';
        const editor = document.createElement('div');
        wrapper.appendChild(editor);
        ta.style.display = 'none';
        ta.parentNode.insertBefore(wrapper, ta);

        const quill = new Quill(editor, {
            theme: 'snow',
            placeholder: ta.getAttribute('placeholder') || 'Write here...',
            modules: {
                toolbar: [
                    [{ header: [2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['blockquote', 'link'],
                    [{ align: [] }],
                    ['clean'],
                ],
            },
        });

        quill.clipboard.dangerouslyPasteHTML(ta.value || '');
        quill.on('text-change', () => {
            const html = quill.root.innerHTML;
            ta.value = (html === '<p><br></p>') ? '' : html;
        });

        // Ensure value is synced right before submit.
        const form = ta.closest('form');
        if (form) {
            form.addEventListener('submit', () => {
                const html = quill.root.innerHTML;
                ta.value = (html === '<p><br></p>') ? '' : html;
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', initRichEditors);
if (document.readyState !== 'loading') initRichEditors();

/**
 * Product gallery: slider with images + videos (file/youtube),
 * thumbnail navigation and hover-zoom on images.
 */
Alpine.data('productGallery', (media = []) => ({
    media,
    active: 0,
    zoom: { on: false, x: '50%', y: '50%' },

    get current() {
        return this.media[this.active] ?? null;
    },
    isImage(m) { return m && m.type === 'image'; },
    select(i) {
        this.active = i;
        this.zoom.on = false;
    },
    next() { this.select((this.active + 1) % this.media.length); },
    prev() { this.select((this.active - 1 + this.media.length) % this.media.length); },

    onMove(e) {
        const m = this.current;
        if (!m || m.type !== 'image') return;
        const r = e.currentTarget.getBoundingClientRect();
        this.zoom.x = ((e.clientX - r.left) / r.width) * 100 + '%';
        this.zoom.y = ((e.clientY - r.top) / r.height) * 100 + '%';
        this.zoom.on = true;
    },
    onLeave() { this.zoom.on = false; },
}));

/**
 * Hero heading: make the Urdu line exactly as wide as the widest
 * English heading line, then justify it (RTL) to fill that width.
 */
Alpine.data('heroHeading', () => ({
    fit() {
        this.$nextTick(() => {
            const en = this.$refs.en, ur = this.$refs.ur;
            if (!en || !ur) return;
            const apply = () => { ur.style.width = en.offsetWidth + 'px'; };
            apply();
            // Re-fit on resize and after fonts load.
            window.addEventListener('resize', apply);
            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(apply);
            }
        });
    },
}));

window.Alpine = Alpine;
Alpine.start();
