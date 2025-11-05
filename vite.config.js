import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import html from '@rollup/plugin-html';
import { glob } from 'glob';
import path from 'path';
import iconsPlugin from './vite.icons.plugin.js';

/**
 * Get Files from a directory
 * @param {string} query
 * @returns array
 */
function GetFilesArray(query) {
  return glob.sync(query);
}

// Standard asset groups
const pageJsFiles        = GetFilesArray('resources/assets/js/*.js');
const vendorJsFiles      = GetFilesArray('resources/assets/vendor/js/*.js');
const LibsJsFiles        = GetFilesArray('resources/assets/vendor/libs/**/*.js');
const LibsScssFiles      = GetFilesArray('resources/assets/vendor/libs/**/!(_)*.scss');
const LibsCssFiles       = GetFilesArray('resources/assets/vendor/libs/**/*.css');
const CoreScssFiles      = GetFilesArray('resources/assets/vendor/scss/**/!(_)*.scss');
const FontsScssFiles     = GetFilesArray('resources/assets/vendor/fonts/!(_)*.scss');
const FontsJsFiles       = GetFilesArray('resources/assets/vendor/fonts/**/!(_)*.js');
const FontsCssFiles      = GetFilesArray('resources/assets/vendor/fonts/**/!(_)*.css');

// KFA assets, NOTE: Do NOT wildcard include directories in "input" -- only valid entry files!
const KfaCssFiles         = GetFilesArray('resources/assets/kfa/css/**/*.css');
const KfaFontsFiles       = GetFilesArray('resources/assets/kfa/fonts/**/*');
const KfaIconsFiles       = GetFilesArray('resources/assets/kfa/icons/**/*');
const KfaImagesFiles      = GetFilesArray('resources/assets/kfa/images/**/*');
const KfaJsFiles          = GetFilesArray('resources/assets/kfa/js/**/*.js');

// Only include .js/.css files for news-bar and sliders (NOT the whole dir)
const KfaLibsNewsBarJsFiles  = GetFilesArray('resources/assets/kfa/libs/news-bar/**/*.js');
const KfaLibsNewsBarCssFiles = GetFilesArray('resources/assets/kfa/libs/news-bar/**/*.css');
const KfaLibsSlidersJsFiles  = GetFilesArray('resources/assets/kfa/libs/sliders/**/*.js');
const KfaLibsSlidersCssFiles = GetFilesArray('resources/assets/kfa/libs/sliders/**/*.css');

// REMOVE: The problematic swiper-bundle.js entry which isn't present and causes Vite build errors.
// Instead, make sure publicKfaEntries and the laravel input array do NOT reference the missing file!
// Optionally, refer to files present in your project structure.

// Optionally: publicKfaEntries with correct, existing files only
const publicKfaEntries = [
  'resources/assets/kfa/js/main.js',
  'resources/assets/kfa/libs/news-bar/newsbar.js',
  'resources/assets/kfa/libs/sliders/sliders.js',
  'resources/assets/kfa/libs/sliders/sliders.css',
  'resources/assets/kfa/libs/news-bar/newsbar.css',
  'resources/assets/kfa/css/styles.css',
];

// Processing Window Assignment for Libs like jKanban, pdfMake
function libsWindowAssignment() {
  return {
    name: 'libsWindowAssignment',

    transform(src, id) {
      if (id.includes('jkanban.js')) {
        return src.replace('this.jKanban', 'window.jKanban');
      } else if (id.includes('vfs_fonts')) {
        return src.replaceAll('this.pdfMake', 'window.pdfMake');
      }
    }
  };
}

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/assets/css/demo.css',
        'resources/js/app.js',
        ...pageJsFiles,
        ...vendorJsFiles,
        ...LibsJsFiles,
        'resources/js/laravel-user-management.js',
        ...CoreScssFiles,
        ...LibsScssFiles,
        ...LibsCssFiles,
        ...FontsScssFiles,
        ...FontsJsFiles,
        ...FontsCssFiles,
        ...KfaCssFiles,
        ...KfaFontsFiles,
        ...KfaIconsFiles,
        ...KfaImagesFiles,
        ...KfaJsFiles,
        // Only include valid entry files that actually exist (no swiper-bundle.js here):
        ...KfaLibsNewsBarJsFiles,
        ...KfaLibsNewsBarCssFiles,
        ...KfaLibsSlidersJsFiles,
        ...KfaLibsSlidersCssFiles,
      ],
      refresh: true
    }),
    html(),
    libsWindowAssignment(),
    iconsPlugin()
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources')
    }
  },
  json: {
    stringify: true
  },
  build: {
    commonjsOptions: {
      include: [/node_modules/]
    }
  }
});
