const mix = require("laravel-mix");

mix.js("resources/js/app.js", "public/js");

mix.styles(
    ["public/css/grid.min.css", "public/css/main.css"],
    "public/css/app.css"
);
