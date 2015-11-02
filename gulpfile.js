var sass = require('gulp-sass');
var gulp = require('gulp');

var exec = require('gulp-exec');

gulp.task('sass', function () {
    gulp.src('./sass/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./public/css'));

});


gulp.task('composer', function () {
    gulp.src('.').pipe(exec('composer install'));

});

gulp.task('bower', function (cb) {
    exec('bower install --allow-root', function (err, stdout, stderr) {
        console.log(stdout);
        console.log(stderr);
        cb(err);
    });
});


gulp.task('default', ['composer', 'bower' ,'sass']);