import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import html from '@rollup/plugin-html';
import { glob } from 'glob';

/**
 * Get Files from a directory
 * @param {string} query
 * @returns array
 */
function GetFilesArray(query) {
  return glob.sync(query);
}
/**
 * Js Files
 */
// Page JS Files
const pageJsFiles = GetFilesArray('resources/assets/admin/js/*.js');

// Processing Vendor JS Files
const vendorJsFiles = GetFilesArray('resources/assets/admin/vendor/js/*.js');

// Processing Libs JS Files
const LibsJsFiles = GetFilesArray('resources/assets/admin/vendor/libs/**/*.js');
const frontendJsFiles = GetFilesArray('resources/assets/frontend/js/*.js');
/**
 * Scss Files
 */
// Processing Core, Themes & Pages Scss Files
const CoreScssFiles = GetFilesArray('resources/assets/admin/vendor/scss/**/!(_)*.scss');


// Processing Libs Scss & Css Files
const LibsScssFiles = GetFilesArray('resources/assets/admin/vendor/libs/**/!(_)*.scss');
const LibsCssFiles = GetFilesArray('resources/assets/admin/vendor/libs/**/*.css');
const frontendCssFiles = GetFilesArray('resources/assets/frontend/css/**/!(_)*.css');
// Processing Fonts Scss Files
const FontsScssFiles = GetFilesArray('resources/assets/admin/vendor/fonts/!(_)*.scss');

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
        'resources/css/admin/app.css',
        'resources/assets/admin/css/demo.css',
        'resources/js/admin/app.js',
        ...frontendJsFiles,
        ...frontendCssFiles,
        ...pageJsFiles,
        ...vendorJsFiles,
        ...LibsJsFiles,
        ...CoreScssFiles,
        ...LibsScssFiles,
        ...LibsCssFiles,
        ...FontsScssFiles
      ],
      refresh: true
    }),
    html(),
    libsWindowAssignment()
  ],
  build: {
    chunkSizeWarningLimit: 1000 
  }
});
