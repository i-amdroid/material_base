var gulp = require('gulp');
var watch = require('gulp-watch');
var batch = require('gulp-batch');
var sass = require('gulp-sass');
var sasslint = require('gulp-sass-lint');
var eslint = require('gulp-eslint');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var imagemin = require('gulp-imagemin');

var SASS = 'sass';
var CSS = 'css';
var IMG = 'img';
var JS = 'js';

var sassOptions = {
  includePaths: ['./node_modules/breakpoint-sass/stylesheets']
};

var autoprefixerOptions = {
  browsers: ['> 1%']
};

// tasks

gulp.task('sass', function () {
  return gulp.src(SASS + '/**/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass.sync(sassOptions).on('error', sass.logError))
    .pipe(autoprefixer(autoprefixerOptions))
    .pipe(sourcemaps.write('../css'))
    .pipe(gulp.dest(CSS));
});

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

gulp.task('imagemin', function() {
  gulp.src(IMG + '/src/*')
    .pipe(imagemin())
    .pipe(gulp.dest(IMG));
});

// build without lint

gulp.task('build', ['sass', 'imagemin']);

gulp.task('watch', function () {
  watch(SASS + '/**/*.scss', batch(function (events, done) {
    gulp.start('build', done);
  }));
});

// build with lint

gulp.task('build-lint', ['sass-lint', 'sass', 'imagemin', 'js-lint']);

gulp.task('watch-lint', function () {
  watch(SASS + '/**/*.scss', batch(function (events, done) {
    gulp.start('build-int', done);
  }));
});

// default task

gulp.task('default', ['build', 'watch']);
//gulp.task('default', ['build-lint', 'watch-lint']);
