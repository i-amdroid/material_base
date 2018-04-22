var gulp = require('gulp');
var sass = require('gulp-sass');
var changed = require('gulp-changed');
var autoprefixer = require('gulp-autoprefixer');
var imagemin = require('gulp-imagemin');

var SASS = 'sass';
var CSS = 'css';
var IMG = 'img';

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

gulp.task('default', ['build', 'watch']);
