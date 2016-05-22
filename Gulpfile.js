var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();

paths = {
    php:       "src/**/*.php",
    php_tests: "tests/**/*.php"
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

    gulp.watch(paths.php, ['test', 'checkstyle'])
        .on('change', onChange);
});

function errorHandler (error) {
    console.log(error.toString());
    this.emit('end');
}