// Tiny helper library (MIT)
export const qs = (sel, scope=document) => scope.querySelector(sel);
export const qsa = (sel, scope=document) => [...scope.querySelectorAll(sel)];
export const on = (el, evt, fn) => el.addEventListener(evt, fn);
export const formatDate = (d=new Date()) => new Intl.DateTimeFormat(document.documentElement.lang||'ar', {dateStyle:'medium'}).format(d);
export const domReady = (fn) => document.readyState !== 'loading' ? fn() : document.addEventListener('DOMContentLoaded', fn);