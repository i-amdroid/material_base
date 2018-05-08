var gulp = require('gulp');
var sass = require('gulp-sass');
var sasslint = require('gulp-sass-lint');
var eslint = require('gulp-eslint');
var changed = require('gulp-changed');
var autoprefixer = require('gulp-autoprefixer');
var imagemin = require('gulp-imagemin');

var SASS = 'sass';
var CSS = 'css';
var IMG = 'img';
var JS = 'js';

gulp.task('sass-lint', function () {
  return gulp.src(SASS + '/**/*.scss')
    .pipe(sasslint())
    .pipe(sasslint.format())
    .pipe(sasslint.failOnError());
});

gulp.task('js-lint', function () {
  return gulp.src(JS + '/**/*.js')
    .pipe(eslint())
    .pipe(eslint.format())
    .pipe(eslint.failAfterError());
});

gulp.task('sass', function () {
  return gulp.src(SASS + '/**/*.scss')
    .pipe(sass({
      includePaths: ['./node_modules/breakpoint-sass/stylesheets']
    }).on('error', sass.logError))
    .pipe(gulp.dest(CSS));
});

gulp.task('autoprefixer', ['sass'], function() {
  gulp.src(CSS + '/*.css')
    //.pipe(changed(CSS))
    .pipe(autoprefixer({
        browsers: ['> 1%']
    }))
    .pipe(gulp.dest(CSS));
});

gulp.task('imagemin', function() {
  gulp.src(IMG + '/src/*')
    .pipe(imagemin())
    .pipe(gulp.dest(IMG));
});

gulp.task('build', ['sass', 'autoprefixer', 'imagemin']);

gulp.task('watch', function() {
  gulp.watch(SASS + '/**/*.scss', ['sass', 'autoprefixer', 'imagemin']);
});

gulp.task('build-lint', ['sass-lint', 'sass', 'autoprefixer', 'imagemin', 'js-lint']);

gulp.task('watch-lint', function() {
  gulp.watch(SASS + '/**/*.scss', ['sass-lint', 'sass', 'autoprefixer', 'imagemin']);
  gulp.watch(JS + '/**/*.js', ['js-lint']);
});

gulp.task('default', ['build', 'watch']);

//gulp.task('default', ['build-lint', 'watch-lint']);
