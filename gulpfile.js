var sass = require('gulp-sass');
var gulp = require('gulp');
var bower = require('gulp-bower');
var exec = require('child_process').exec;

var riot = require('gulp-riot');

var inputs = {
    "riot_tags": {
        "input": "./templates/components/*.tag",
        "output": "./public/js/components"
    }
};


var exec = require('gulp-exec');

gulp.task('sass', function () {
    gulp.src('./sass/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(gulp.dest('./public/css'));

});


gulp.task('composer', function (cb)
{
    exec('composer install', function (err, stdout, stderr)
    {
        console.log(stdout);
        console.log(stderr);
        cb(err);
    });
});


gulp.task('bower', function ()
{
    return bower();
});

gulp.task('riot', function (cb)
{
    gulp.src(inputs.riot_tags.input)
        .pipe(riot())
        .pipe(gulp.dest(inputs.riot_tags.output));
});

gulp.task('watch', function ()
{
    gulp.watch(inputs.riot_tags.input, ['riot']);
});


gulp.task('default', ['composer', 'bower' ,'sass', 'riot']);