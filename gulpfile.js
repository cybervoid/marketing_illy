var sass = require('gulp-sass');
var gulp = require('gulp');


gulp.task('sass', function () {
    gulp.src('./sass/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./public/css'));

});

gulp.task('default', ['sass']);