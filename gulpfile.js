'use strict';

let file = require('fs');
let config = JSON.parse(file.readFileSync('./resources/assets/config.json', 'UTF8')) || {};

const gulp = require('gulp');
const concat = require('gulp-concat-util');
const sequence = require('run-sequence');
const notify = require('gulp-notify');

/**
 * Gulps STYLES
 */
const cssComb = require('gulp-csscomb');
const sass = require('gulp-sass');
const postCss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const minifyCss = require('gulp-minify-css');

/**
 * Gulp JS
 */
const uglify = require('gulp-uglify');
const babel = require('gulp-babel');
const es2015 = require('babel-preset-es2015');

/**
 * Task CSS
 */
gulp.task('styles', () => {
  return gulp
    .src(config.css)
    .pipe(sass()).on('error', sass.logError)
    .pipe(concat('all.css'))
    .pipe(postCss([
      autoprefixer({
        browsers: ['last 2 versions'],
        cascade: false
      }),
      require('css-mqpacker')
    ]))
    .pipe(cssComb('./resources/assets/.csscomb.json'))
    .pipe(minifyCss({
      keepBreaks: true,
      target: config.path.public,
      root: config.path.public,
      processImport: true,
      keepSpecialComments: 0
    }))
    .pipe(concat('app.css'))
    .pipe(gulp.dest(config.path.css))
    .pipe(notify({
      //message: 'OK! file: <%= file.path %>',
      message: 'Build styles successfully!',
      templateOptions: {
        date: new Date()
      }
    }));
});

/**
 * Task JS
 */
gulp.task('scripts', () => {
  return gulp
    .src(config.js)
    .pipe(concat('all.js'))
    /*.pipe(babel({
     presets: [es2015]
     }))*/
    .pipe(uglify())
    .pipe(concat('app.js'))
    .pipe(gulp.dest(config.path.js))
    .pipe(notify({
      message: 'Build scripts successfully!',
      templateOptions: {
        date: new Date()
      }
    }));
});

/**
 * Task WATCH
 */
gulp.task('watch', () => {
  const onChange = (event) => {
    console.log(`File ${event.path} was ${event.type}, running tasks...`);
  };

  return [
    gulp.watch('./resources/assets/sass/**/**/*.{scss,css}', ['styles']).on('change', onChange),
    gulp.watch('./resources/assets/libraries/**/**/*.{scss,css}', ['styles']).on('change', onChange),
    gulp.watch('./resources/assets/js/**/**/*.js', ['scripts']).on('change', onChange),
    gulp.watch('./resources/assets/libraries/**/**/*.js}', ['scripts']).on('change', onChange)
  ];
});

/**
 * Task DEFAULT
 */
gulp.task('default', (callback) => {
  return sequence('styles', 'scripts', ['watch'], callback);
});
