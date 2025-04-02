import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { viteStaticCopy } from "vite-plugin-static-copy";
import lodash from "lodash";

export default defineConfig({
    loader: "sass-loader",
    options: {
        // https://github.com/webpack-contrib/sass-loader#sassoptions
        sassOptions: {
            // If set to true, Sass won’t print warnings that are caused by dependencies (like bootstrap):
            // https://sass-lang.com/documentation/js-api/interfaces/options/#quietDeps
            quietDeps: true,
            silenceDeprecations: [
                "mixed-decls",
                "color-functions",
                "global-builtin",
                "import",
            ],
        },
    },
    includePaths: ["node_modules"],
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true,
                silenceDeprecations: [
                    "mixed-decls",
                    "color-functions",
                    "global-builtin",
                    "import",
                ],
            },
        },
    },
    build: {
        manifest: true,
        rtl: true,
        outDir: "public/build/",
        cssCodeSplit: true,
        // buildDirectory: 'assets',
        rollupOptions: {
            output: {
                assetFileNames: (css) => {
                    if (css.name.split(".").pop() == "css") {
                        return "css/" + `[name]` + ".min." + "css";
                    } else {
                        return "icons/" + css.name;
                    }
                },
                entryFileNames: "js/" + `[name]` + `.js`,
            },
        },
    },
    plugins: [
        laravel({
            input: [
                "resources/scss/bootstrap.scss",
                "resources/scss/icons.scss",
                "resources/scss/app.scss",
                "resources/scss/custom.scss",
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: "resources/fonts",
                    dest: "",
                },
                {
                    src: "resources/images",
                    dest: "",
                },
                {
                    src: "resources/js",
                    dest: "",
                },
                {
                    src: "resources/json",
                    dest: "",
                },
                {
                    src: "resources/libs",
                    dest: "",
                },
            ],
        }),
    ],
});
