import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';

const source = readFileSync(new URL('../../public/front/js/toast.js', import.meta.url), 'utf8');

function browser(storage = new Map()) {
    let time = 0;
    let nextTimer = 0;
    const timers = new Map();
    class Element {
        children = [];
        attributes = {};
        listeners = {};
        append(...children) {
            children.forEach(child => { child.parent = this; this.children.push(child); });
        }
        remove() { this.parent.children = this.parent.children.filter(child => child !== this); }
        setAttribute(key, value) { this.attributes[key] = value; }
        addEventListener(name, callback) { this.listeners[name] = callback; }
        contains(target) { return this === target || this.children.some(child => child.contains(target)); }
        dispatch(name, event = {}) { this.listeners[name]?.(event); }
    }
    const body = new Element();
    const context = vm.createContext({
        window: {},
        document: { body, readyState: 'complete', createElement: () => new Element() },
        sessionStorage: {
            setItem: (key, value) => storage.set(key, value),
            getItem: key => storage.get(key) ?? null,
            removeItem: key => storage.delete(key),
        },
        Date: { now: () => time },
        setTimeout: (callback, delay) => { const id = ++nextTimer; timers.set(id, { callback, at: time + delay }); return id; },
        clearTimeout: id => timers.delete(id),
    });
    vm.runInContext(source, context);
    return {
        toast: context.window.SuperFitToast,
        cards: () => body.children[0]?.children ?? [],
        reload: () => browser(storage),
        loadAgain: () => vm.runInContext(source, context),
        tick(ms) {
            time += ms;
            for (const [id, timer] of [...timers]) {
                if (timer.at <= time) { timers.delete(id); timer.callback(); }
            }
        },
    };
}

test('all notification types render accessible text without interpreting HTML', () => {
    const page = browser();
    for (const type of ['success', 'error', 'warning', 'info']) page.toast[type]('<img src=x onerror=alert(1)>');
    assert.equal(page.cards().length, 4);
    for (const card of page.cards()) {
        assert.equal(card.children[1].children[1].textContent, '<img src=x onerror=alert(1)>');
        assert.equal(card.children[2].attributes['aria-label'], 'إغلاق الإشعار');
    }
    assert.equal(page.cards()[1].attributes.role, 'alert');
    assert.equal(page.cards()[0].attributes.role, 'status');
});

test('deduplicates active messages, bounds the stack, and dismisses by button', () => {
    const page = browser();
    const id = page.toast.success('Saved');
    assert.equal(page.toast.success('Saved'), id);
    for (let i = 0; i < 5; i++) page.toast.info(String(i));
    assert.equal(page.cards().length, 4);
    assert.equal(page.cards()[0].children[1].children[1].textContent, '1');
    page.cards()[0].children[2].dispatch('click');
    assert.equal(page.cards().length, 3);
    page.toast.clear();
    assert.equal(page.cards().length, 0);
});

test('auto-dismisses while preserving persistent notifications', () => {
    const page = browser();
    page.toast.success('Saved');
    page.toast.error('Failed');
    page.toast.info('Persistent', { duration: 0 });
    page.tick(5000);
    assert.equal(page.cards().length, 2);
    page.tick(2000);
    assert.equal(page.cards().length, 1);
    assert.equal(page.cards()[0].children[1].children[1].textContent, 'Persistent');
});

test('pauses the remaining lifetime until both pointer and keyboard focus leave', () => {
    const page = browser();
    page.toast.info('Reading');
    const card = page.cards()[0];
    page.tick(2000);
    card.dispatch('pointerenter');
    card.dispatch('focusin');
    page.tick(10000);
    card.dispatch('pointerleave');
    page.tick(10000);
    assert.equal(page.cards().length, 1);
    card.dispatch('focusout');
    page.tick(2999);
    assert.equal(page.cards().length, 1);
    page.tick(1);
    assert.equal(page.cards().length, 0);
});

test('redirect messages are consumed once and stale or malformed messages are ignored', () => {
    const storage = new Map();
    const page = browser(storage);
    page.toast.flash('Saved across redirect');
    const next = page.reload();
    assert.equal(next.cards().length, 1);
    assert.equal(next.reload().cards().length, 0);
    storage.set('superfit.toast.flash', '{broken');
    assert.equal(browser(storage).cards().length, 0);
    storage.set('superfit.toast.flash', JSON.stringify({ message: 'Expired', expiresAt: -1 }));
    assert.equal(browser(storage).cards().length, 0);
});

test('ignores empty messages, falls back for unknown types, and initializes only once', () => {
    const page = browser();
    page.toast.show(null);
    page.toast.show('  ');
    assert.equal(page.cards().length, 0);
    page.toast.show('Hello', 'unknown');
    assert.equal(page.cards()[0].className, 'sf-toast sf-toast--info');
    page.loadAgain();
    page.toast.success('Still shared');
    assert.equal(page.cards().length, 2);
});
