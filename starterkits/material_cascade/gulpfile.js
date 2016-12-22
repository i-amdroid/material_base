var gulp = require('gulp');
var changed = require('gulp-changed');
var sass = require('gulp-ruby-sass');
var autoprefixer = require('gulp-autoprefixer');
var imagemin = require('gulp-imagemin');

var SASS = 'sass';
var CSS = 'css';
var IMG = 'img';

gulp.task('sass', function () {
  return sass(SASS + '/**/*.scss', {
      compass: true
    })
    .on('error', sass.logError)
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

gulp.task('build', function(){
  gulp.run('sass');
  gulp.run('autoprefixer');
  gulp.run('imagemin');
});

gulp.task('watch', function() {
  gulp.watch(SASS + '/**/*.scss', function () {
    gulp.run('sass');
    gulp.run('autoprefixer');
    gulp.run('imagemin');
  });
});

gulp.task('default', function(){
  gulp.run('build');
  gulp.run('watch');
});
