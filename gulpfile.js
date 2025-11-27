/**
 * Gulpfile configurado para compilar SCSS, minificar JS e imágenes,
 * y usar BrowserSync para recargar el navegador automáticamente.
 * (Actualizado por Sergio — versión optimizada y no disruptiva)
 */

import { src, dest, watch, series, parallel } from "gulp";
import * as dartSass from "sass";
import gulpSass from "gulp-sass";
import terser from "gulp-terser";
import { glob } from "glob";
import path from "path";
import fs from "fs";
import sharp from "sharp";
import browserSync from "browser-sync";

const sass = gulpSass(dartSass);
const bs = browserSync.create(); // 🆕 Instancia de BrowserSync

const paths = {
  scss: "src/scss/**/*.scss",
  js: "src/js/**/*.js",
  images: "src/img/{apps,branding}/**/*.{png,jpg,jpeg,svg}",
};

// ===============================
// 🎨 Compilar SCSS
// ===============================
export function css(done) {
  src("src/scss/app.scss", { sourcemaps: true })
    .pipe(
      sass({
        outputStyle: "compressed",
      }).on("error", sass.logError)
    )
    .pipe(dest("public/build/css", { sourcemaps: "." }))
    .pipe(bs.stream()) // 🆕 Inyecta el CSS automáticamente
    .on("end", () => {
      console.log("✅ CSS compilado correctamente → public/build/css/app.css");
    });
  done();
}

// ===============================
// ⚙️ Compilar JS
// ===============================
export function js(done) {
  src(paths.js)
    .pipe(terser())
    .pipe(dest("./public/build/js"))
    .pipe(bs.stream()); // 🆕 Recarga el navegador al compilar JS
  done();
}

// ===============================
// 🖼️ Optimizar imágenes
// ===============================
export async function images(done) {
  const srcDir = "./src/img";
  const buildDir = "./public/build/img";
  const images = await glob("./src/img/**/*");

  images.forEach((file) => {
    const relativePath = path.relative(srcDir, path.dirname(file));
    const outputSubDir = path.join(buildDir, relativePath);
    processImages(file, outputSubDir);
  });
  done();
}

function processImages(file, outputSubDir) {
  if (!fs.existsSync(outputSubDir)) {
    fs.mkdirSync(outputSubDir, { recursive: true });
  }
  const baseName = path.basename(file, path.extname(file));
  const extName = path.extname(file).toLowerCase();

  if (extName === ".svg") {
    const outputFile = path.join(outputSubDir, `${baseName}${extName}`);
    fs.copyFileSync(file, outputFile);
  } else if ([".png", ".jpg", ".jpeg"].includes(extName)) {
    const outputFile = path.join(outputSubDir, `${baseName}${extName}`);
    const outputFileWebp = path.join(outputSubDir, `${baseName}.webp`);
    const outputFileAvif = path.join(outputSubDir, `${baseName}.avif`);
    const options = { quality: 80 };

    sharp(file).toFile(outputFile);
    sharp(file).webp(options).toFile(outputFileWebp);
    sharp(file).avif(options).toFile(outputFileAvif);
  }
}

// ===============================
// 🧠 Servidor local con recarga automática 🆕
// ===============================
function serve(done) {
  bs.init({
    server: {
      baseDir: "./public", // Carpeta que sirve el navegador
    },
    notify: false,
  });

  watch(paths.scss, css);
  watch(paths.js, js);
  watch(paths.images, images);
  watch("public/**/*.html").on("change", bs.reload); // 🆕 Recarga al editar HTML

  done();
}

// ===============================
// 🚀 Tareas por defecto
// ===============================
export function dev() {
  // 🆕 Incluye BrowserSync junto al watcher
  series(js, css, images, serve)();
}

export default series(js, css, images, serve);

export const build = series(parallel(js, css, images));
