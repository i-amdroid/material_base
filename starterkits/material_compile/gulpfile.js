var gulp = require('gulp');
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

// tasks

gulp.task('sass', done => {
  gulp.src(SASS + '/**/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass.sync(sassOptions).on('error', sass.logError))
    .pipe(autoprefixer({}))
    .pipe(sourcemaps.write('../css'))
    .pipe(gulp.dest(CSS));
  done();
});

gulp.task('sass-lint', done => {
  gulp.src(SASS + '/**/*.scss')
    .pipe(sasslint())
    .pipe(sasslint.format())
    .pipe(sasslint.failOnError())
  done();
});

gulp.task('js-lint', done => {
  gulp.src(JS + '/**/*.js')
    .pipe(eslint())
    .pipe(eslint.format())
    .pipe(eslint.failAfterError())
  done();
});

gulp.task('imagemin', done => {
  gulp.src(IMG + '/src/*')
    .pipe(imagemin())
    .pipe(gulp.dest(IMG));
  done();
});

// build without lint

gulp.task('build', gulp.series('sass', 'imagemin'));

gulp.task('watch', function () {
  return gulp.watch(SASS + '/**/*.scss', gulp.series(gulp.series('sass', 'imagemin')));
});

// build with lint

gulp.task('build-lint', gulp.series('sass-lint', 'sass', 'imagemin', 'js-lint'));

gulp.task('watch-lint', function () {
  return gulp.watch(SASS + '/**/*.scss', gulp.series(gulp.series('sass-lint', 'sass', 'imagemin', 'js-lint')));
});

gulp.task('default', gulp.series('build', 'watch'));
// gulp.task('default', gulp.series('build-lint', 'watch-lint'));
