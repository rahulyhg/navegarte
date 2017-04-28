var mix = require("laravel-mix");

mix
  .setPublicPath('public')
  .js('resources/assets/js/app.js', 'public/js')
  .sass('resources/assets/sass/app.scss', 'public/css', {
    outputStyle: 'nested'
  })
  .version();
