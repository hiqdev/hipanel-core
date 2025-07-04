const mix = require("laravel-mix");
const path = require("path");

mix.webpackConfig({
     resolve: {
       alias: {
         "@": path.resolve(__dirname, "./src"),
       },
     },
     plugins: [],
   })
   .autoload({})
   .ts("src/app/main.tsx", "build")
   .sourceMaps()
   .setPublicPath("build")
   .version()
   .react();
