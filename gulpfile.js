const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

elixir(mix => {
  mix.copy('resources/assets/img', 'public/img');
  mix.copy('resources/assets/css', 'public/css');
  mix.copy('resources/assets/js', 'public/js');

    mix.sass('app.scss')
       .webpack('app.js');
});