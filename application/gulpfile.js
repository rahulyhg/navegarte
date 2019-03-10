'use strict';

/**
 * Configurações
 */
const file = require('fs');
const config = JSON.parse(file.readFileSync('./resources/assets/config.json', 'UTF8')) || {};

/**
 * Plugins do GULP
 */
const gulp = require('gulp');
const sass = require('gulp-sass');
const postcss = require('gulp-postcss');
const minifycss = require('gulp-minify-css');
const notify = require('gulp-notify');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
// const babel = require('gulp-babel');
const browserSync = require('browser-sync').create();

/**
 * Compila os scss
 *
 * @param src
 * @param fileName
 * @param dest
 * @returns {*}
 */
const compileSass = (src, fileName, dest) => {
  return gulp
    .src(src)
    .pipe(sass({includePaths: `${config.path.public}/assets`}).on('error', sass.logError))
    .pipe(concat(`${fileName}.css`))
    .pipe(postcss([
      require('autoprefixer')({browsers: ['last 2 versions'], cascade: false}),
      require('css-mqpacker')()
    ]))
    .pipe(minifycss({
      keepBreaks: false,
      target: config.path.public,
      root: config.path.public,
      processImport: true,
      keepSpecialComments: 0
    }))
    .pipe(concat(`${fileName}.css`))
    .pipe(gulp.dest(dest))
    .pipe(notify(`${dest.replace(config.path.public, '')}/${fileName}.css successfully.`))
    .pipe(browserSync.stream());
};

const sassError = () => compileSass('./resources/assets/sass/error.scss', 'error', config.path.css);
gulp.task('sass-error', () => sassError());

const sassMail = () => compileSass('./resources/assets/sass/mail.scss', 'mail', config.path.css);
gulp.task('sass-mail', () => sassMail());

const sassWeb = () => compileSass(config.web.css, 'app', `${config.path.css}/web`);
gulp.task('sass-web', sassWeb);

/**
 * Compila os javascripts
 *
 * @param src
 * @param fileName
 * @param dest
 * @returns {*}
 */
const compileJs = (src, fileName, dest) => {
  return gulp
    .src(src)
    .pipe(concat(`${fileName}.js`))
    /*.pipe(babel({presets: ['env']}))*/
    .pipe(uglify())
    .pipe(concat(`${fileName}.js`))
    .pipe(gulp.dest(dest))
    .pipe(notify(`${dest.replace(config.path.public, '')}/${fileName}.js successfully.`))
    .pipe(browserSync.stream());
};

const jsWeb = () => compileJs(config.web.js, 'app', `${config.path.js}/web`);
gulp.task('js-web', jsWeb);

/**
 * Sincronização do browser
 */
const browser = () => {
  browserSync.init({
    proxy: 'localhost',
    port: 3001,
    files: [
      '**/*.php',
      '**/*.twig',
      '**/*.css',
      '**/*.js',
      '**/*.html'
    ],
    injectChanges: false
  });
};

gulp.task('browser-sync', browser);

/**
 * Inicia a observaão das tarefas
 */
gulp.task('watch', () => {
  const onChange = (event) => {
    console.log(`File ${event.path} was ${event.type}, running tasks...`);
  };
  
  /* Error */
  gulp.watch('./resources/assets/sass/error.scss', sassError);
  
  /* Email */
  gulp.watch('./resources/assets/sass/mail.scss', sassMail);
  
  /* Web */
  gulp.watch('./resources/assets/sass/web/**/**/**/*.{scss,css}', sassWeb);
  gulp.watch('./resources/assets/js/web/**/**/**/*.js', jsWeb);
  
  /* Plugins JS */
  gulp.watch('./resources/assets/js/plugins/**/**/**/*.js', gulp.parallel(jsWeb));
  
  /* BrowserSync */
  gulp.watch([
    '../!**!/!*.{php,twig,js,css}',
    '!../!**!/docker',
    '!../!**!/node_modules',
    '!../!**!/bower_componets',
    '!../!**!/vendor'
  ]).on('change', browserSync.reload);
});

/**
 * Roda todas tarefas
 */
gulp.task('default', gulp.parallel('watch', 'sass-error', 'sass-mail', 'sass-web', 'js-web'));
