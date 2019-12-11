var urlLocal 	= 'http://localhost:12346/', // Local Development URL for BrowserSync. Change as-needed.
gulp          = require('gulp'),
autoprefixer  = require('gulp-autoprefixer'), // Autoprefixing magic
sass          = require('gulp-sass'),
filter        = require('gulp-filter'),
newer         = require('gulp-newer'),
rename        = require('gulp-rename'),
concat        = require('gulp-concat'),
plumber       = require('gulp-plumber'), // Helps prevent stream crashing on errors
sourcemaps    = require('gulp-sourcemaps'),
order         = require("gulp-order"),
watch         = require('gulp-watch'),
cleanCSS      = require('gulp-clean-css'),
c             = require('ansi-colors');
sass.compiler = require('node-sass');

var browserSync = require('browser-sync').create();

var onError = function (err) {
  console.log('An error occurred:', c.red(err.message));
  this.emit('end');
};

gulp.task('css', function () {
  return gulp.src('../public/sass/*.scss')
  .pipe(plumber({ errorHandler: onError }))
  .pipe(sourcemaps.init({loadMaps: true}))
  .pipe(sass())
  .pipe(cleanCSS({compatibility: 'ie8'}))
  .pipe(gulp.dest('../public/css'))
  .pipe(plumber.stop())
  .pipe(browserSync.stream());
});



gulp.task('watch', function () {

  browserSync.init({
    proxy: urlLocal,
    files: "*.css,*.php,css/*css,sass/**/*.scss"
  });
  gulp.watch('../public/sass/**/*.scss', gulp.series('css'));
  gulp.watch("../public/**/*.js").on('change', browserSync.reload);
});
