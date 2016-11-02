var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();
var exec = require('child_process').exec;

paths = {
    php:       "src/**/*.åphp",
    php_tests: "tests/**/*.php",
    sami:      "app/sami.php"
}

gulp.task('coverage', function () {
    return gulp.src(paths.php_tests)
        .pipe(plugins.phpunit(
            './vendor/bin/phpunit',
            {
                debug: false,
                coverageText: './var/build/coverage'
            }
        )).on('error', errorHandler);
});

gulp.task('default', ['test', 'validate', 'watch']);

gulp.task('docs', function (cb) {
    exec('php sami.phar update ' + paths.sami, function (err, stdout, stderr) {
        console.log(stdout);
        console.log(stderr);
        cb(err);
    }).on('error', errorHandler);
});

gulp.task('test', function () {
    return gulp.src(paths.php_tests)
        .pipe(plugins.phpunit(
            'vendor/bin/phpunit',
            {
                debug: false,
            }
        )).on('error', errorHandler);
})

gulp.task('validate', function () {
    return gulp.src(paths.php)
        .pipe(plugins.phpcs(
            {
            bin: './vendor/bin/phpcs',
            standard: 'Symfony2',
            warningSeverity: 0
            }
        )).on('error', errorHandler)
        .pipe(plugins.phpcs.reporter('log'));
});

gulp.task('watch', function () {
    var onChange = function (event) {
        console.log('File '+event.path+' has been '+event.type);
    };

    gulp.watch(paths.php, ['test', 'validate'])
        .on('change', onChange);
});

function errorHandler (error) {
    console.log(error.toString());
    this.emit('end');
}