'use strict';

let file   = require('fs');
let config = JSON.parse(file.readFileSync('./resources/assets/config.json', 'UTF8')) || {};

const gulp     = require('gulp');
const concat   = require('gulp-concat-util');
const sequence = require('run-sequence');
const notify   = require('gulp-notify');

/**
 * Gulps STYLES
 */
const cssComb      = require('gulp-csscomb');
const sass         = require('gulp-sass');
const postCss      = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const minifyCss    = require('gulp-minify-css');

/**
 * Gulp JS
 */
const uglify = require('gulp-uglify');
const babel  = require('gulp-babel');
const es2015 = require('babel-preset-es2015');

/**
 * Task CSS
 */
const taskCss = (taskName, array, name, path) => {
  gulp.task(taskName, () => {
    return gulp
      .src(array)
      .pipe(sass({
        includePaths: [config.path.public + '/assets/bower_components']
      })).on('error', sass.logError)
      .pipe(concat('all.css'))
      .pipe(postCss([
        autoprefixer({
          browsers: ['last 2 versions'],
          cascade: false
        }),
        require('css-mqpacker')
      ]))
      //.pipe(cssComb('./resources/assets/.csscomb.json'))
      .pipe(minifyCss({
        keepBreaks: false,
        target: config.path.public,
        root: config.path.public,
        processImport: true,
        keepSpecialComments: 0
      }))
      .pipe(concat(name + '.css'))
      .pipe(gulp.dest(path))
      .pipe(notify({
        message: 'Build style <%= file.relative %> successfully!',
        templateOptions: {
          date: new Date()
        }
      }));
  });
};

taskCss('styles', config.web.css, 'app', config.path.css + '/web');
taskCss('stylesMail', './resources/assets/sass/mail.scss', 'mail', config.path.css);
//taskCss('stylesAdm', config.adm.css, 'app', config.path.css + '/adm');

/**
 * Task JS
 */
const taskJs = (taskName, array, name, path) => {
  gulp.task(taskName, () => {
    return gulp
      .src(array)
      .pipe(concat('all.js'))
      /*.pipe(babel({
       presets: [es2015]
       }))*/
      .pipe(uglify())
      .pipe(concat(name + '.js'))
      .pipe(gulp.dest(path))
      .pipe(notify({
        message: 'Build script <%= file.relative %> successfully!',
        templateOptions: {
          date: new Date()
        }
      }));
  });
};

taskJs('scripts', config.web.js, 'app', config.path.js + '/web');
//taskJs('scriptsAdm', config.adm.js, 'app', config.path.js + '/adm');

/**
 * Task WATCH
 */
gulp.task('watch', () => {
  const onChange = (event) => {
    console.log(`File ${event.path} was ${event.type}, running tasks...`);
  };
  
  return [
    gulp.watch('./resources/assets/sass/mail.scss', ['stylesMail']).on('change', onChange),
    gulp.watch('./resources/assets/sass/web/**/**/**/*.{scss,css}', ['styles']).on('change', onChange),
    //gulp.watch('./resources/assets/sass/adm/**/**/**/*.{scss,css}', ['stylesAdm']).on('change', onChange),
    
    gulp.watch('./resources/assets/js/web/**/**/**/*.js', ['scripts']).on('change', onChange),
    //gulp.watch('./resources/assets/js/adm/**/**/**/*.js', ['scriptsAdm']).on('change', onChange),
    
    gulp.watch('./resources/assets/libraries/web/**/**/**/*.{scss,css,js}', ['styles', 'scripts']).on('change', onChange)
    //gulp.watch('./resources/assets/libraries/adm/**/**/**/*.{scss,css,js}', ['stylesAdm', 'scriptsAdm']).on('change', onChange)
  ];
});

/**
 * Task DEFAULT
 */
gulp.task('default', (callback) => {
  //return sequence('styles', 'stylesAdm', 'stylesMail', 'scripts', 'scriptsAdm', ['watch'], callback);
  return sequence('styles', 'stylesMail', 'scripts', ['watch'], callback);
});
