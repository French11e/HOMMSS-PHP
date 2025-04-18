import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ["resources/js/app.js", "resources/css/app.css"], // Using app.scss for Sass
            refresh: true,
        }),
    ],
    build: {
        manifest: true,
        outDir: "public/build",
    },
});
