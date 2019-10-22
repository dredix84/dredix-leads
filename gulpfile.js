const {series} = require('gulp');
var gulp = require('gulp');

function clean(cb) {
    cb();
}

function build(cb) {
    cb();
}

function copyJs(cb) {
    gulp.src(['./node_modules/vue*/dist/*.js'], {base: './node_modules'})
        .pipe(gulp.dest('./webroot/js'));
    gulp.src(['./node_modules/*/dist/*.js'], {base: './node_modules'})
        .pipe(gulp.dest('./webroot/js'));
    gulp.src(['./node_modules/moment/min/*.js'], {base: './node_modules'})
        .pipe(gulp.dest('./webroot/js'));
    cb();
}

function copyCss(cb) {
    gulp.src(['./node_modules/vue*/dist/*.css'], {base: './node_modules'})
        .pipe(gulp.dest('./webroot/css'));
    gulp.src(['./node_modules/*/dist/*.css'], {base: './node_modules'})
        .pipe(gulp.dest('./webroot/css'));
    gulp.src(['./node_modules/moment/min/*.css'], {base: './node_modules'})
        .pipe(gulp.dest('./webroot/css'));
    cb();
}

exports.default = series(build, copyJs, copyCss, clean);
