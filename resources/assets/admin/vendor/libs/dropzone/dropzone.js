import Dropzone from 'dropzone/dist/dropzone';

try {
  window.Dropzone = Dropzone;
} catch (e) {}

export { Dropzone };
