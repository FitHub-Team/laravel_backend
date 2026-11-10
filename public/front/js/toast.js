(function () {
    if (window.SuperFitToast) return;

    const active = new Map();
    const types = {
        success: { icon: '✓', label: 'نجاح' },
        error: { icon: '×', label: 'خطأ' },
        warning: { icon: '!', label: 'تنبيه' },
        info: { icon: 'i', label: 'معلومة' },
    };
    const flashKey = 'superfit.toast.flash';
    let sequence = 0;
    let host;

    function container() {
        if (!host) {
            host = document.createElement('div');
            host.className = 'sf-toast-container';
            host.setAttribute('role', 'region');
            host.setAttribute('aria-label', 'الإشعارات');
            document.body.append(host);
        }
        return host;
    }

    function dismiss(id) {
        const item = active.get(id);
        if (!item) return;
        clearTimeout(item.timer);
        item.element.remove();
        active.delete(id);
    }

    function show(message, type = 'info', options = {}) {
        if (typeof message !== 'string' || !message.trim()) return null;
        if (!document.body) {
            document.addEventListener('DOMContentLoaded', () => show(message, type, options), { once: true });
            return null;
        }
        if (!Object.hasOwn(types, type)) type = 'info';
        message = message.trim();
        for (const [id, item] of active) {
            if (item.message === message && item.type === type) return id;
        }
        if (active.size >= 4) dismiss(active.keys().next().value);

        const id = ++sequence;
        const element = document.createElement('div');
        element.className = `sf-toast sf-toast--${type}`;
        element.setAttribute('role', type === 'error' ? 'alert' : 'status');
        element.setAttribute('aria-atomic', 'true');

        const icon = document.createElement('span');
        icon.className = 'sf-toast-icon';
        icon.textContent = types[type].icon;
        icon.setAttribute('aria-hidden', 'true');
        const content = document.createElement('div');
        content.className = 'sf-toast-content';
        const label = document.createElement('strong');
        label.textContent = types[type].label;
        const text = document.createElement('p');
        text.setAttribute('dir', 'auto');
        text.textContent = message;
        content.append(label, text);
        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'sf-toast-close';
        close.setAttribute('aria-label', 'إغلاق الإشعار');
        close.textContent = '×';
        close.addEventListener('click', () => dismiss(id));
        element.append(icon, content, close);

        const duration = Number.isFinite(options.duration) ? options.duration : (type === 'error' ? 7000 : 5000);
        const item = { element, message, type, remaining: duration === 0 ? 0 : Math.max(1000, duration), timer: null, paused: new Set() };
        const resume = () => {
            if (!item.remaining || item.paused.size) return;
            item.startedAt = Date.now();
            item.timer = setTimeout(() => dismiss(id), item.remaining);
        };
        const pause = (reason) => {
            if (!item.paused.size && item.timer) {
                clearTimeout(item.timer);
                item.remaining = Math.max(1, item.remaining - (Date.now() - item.startedAt));
            }
            item.paused.add(reason);
        };
        const unpause = (reason) => {
            item.paused.delete(reason);
            resume();
        };
        element.addEventListener('pointerenter', () => pause('pointer'));
        element.addEventListener('pointerleave', () => unpause('pointer'));
        element.addEventListener('focusin', () => pause('focus'));
        element.addEventListener('focusout', (event) => {
            if (!element.contains(event.relatedTarget)) unpause('focus');
        });
        active.set(id, item);
        container().append(element);
        resume();
        return id;
    }

    const toast = { show, dismiss, clear: () => [...active.keys()].forEach(dismiss) };
    for (const type of Object.keys(types)) {
        toast[type] = (message, options) => show(message, type, options);
    }
    toast.flash = (message, type = 'success') => {
        try {
            sessionStorage.setItem(flashKey, JSON.stringify({ message, type, expiresAt: Date.now() + 30000 }));
        } catch {
            show(message, type);
        }
    };
    window.SuperFitToast = toast;

    function restoreFlash() {
        try {
            const saved = sessionStorage.getItem(flashKey);
            sessionStorage.removeItem(flashKey);
            if (saved) {
                const flash = JSON.parse(saved);
                if (flash.expiresAt > Date.now()) show(flash.message, flash.type);
            }
        } catch { /* Notifications also work when browser storage is unavailable. */ }
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', restoreFlash, { once: true });
    } else {
        restoreFlash();
    }
})();
