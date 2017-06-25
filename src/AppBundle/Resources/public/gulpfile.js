var gulp = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    bower = require('gulp-bower'),
    concat = require('gulp-concat'),
    cleanCSS = require('gulp-clean-css'),
    dedupe = require('gulp-dedupe'),
    flatten = require('gulp-flatten'),
    plumber = require('gulp-plumber'),
    rename = require('gulp-rename'),
    sass = require('gulp-ruby-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    mainBowerFiles = require('gulp-main-bower-files'),
    merge = require('merge-stream'),
    uglify = require('gulp-uglify');

// Config
var config = {
    autoprefixerBrowsers: ['last 3 versions', 'ie >= 9', 'iOS >= 7', 'ff >= 38', 'Chrome >= 40', 'Android >= 4.1'],
    scssInput: 'scss/main.scss',
    scssOutput: '../../../../web/dist/css',
    jsOutput: '../../../../web/dist/js',
    projectScripts: [
        'bower_components/jquery/dist/jquery.min.js'
    ],
    bowerDir: 'bower_components'
};

/**
 * Default task
 */
gulp.task('default', ['scripts', 'sass', 'watch'], function () {

});

/**
 * Watch task
 */
gulp.task('watch', function () {
    gulp.watch('js/**/*.js', ['scripts']);
    gulp.watch('scss/**/*.scss', ['sass']);
});

/**
 * Compass / Sass tasks
 */
gulp.task('sass', function () {
    return sass(config.scssInput, {
        stopOnError: true,
        sourcemap: true
    })
        .on('error', sass.logError)
        .pipe(plumber())
        .pipe(autoprefixer(config.autoprefixerBrowsers))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(sourcemaps.write('maps', {
            includeContent: false,
            sourceRoot: config.scssOutput
        }))
        .pipe(concat('all.min.css'))
        .pipe(gulp.dest(config.scssOutput));
});

/**
 * Scrips task
 */
gulp.task('scripts', ['prepare-js'], function () {
    return gulp.src(config.jsOutput + '/all.js')
        .pipe(uglify())
        .pipe(concat('all.min.js'))
        .pipe(gulp.dest(config.jsOutput))
});

// Bower install
gulp.task('bower', function () {
    return bower().pipe(gulp.dest(config.bowerDir))
});

// Prepare js for init
gulp.task('prepare-js', ['bower'], function () {
    var jqueryStream = gulp.src(config.projectScripts);

    var bowerStream = gulp.src('bower.json')
        .pipe(mainBowerFiles([
            '**/*.js'
        ]))
        .pipe(flatten());

    var appStream = gulp.src(['js/**/*.js'])
        .pipe(flatten());

    return merge(jqueryStream, bowerStream, appStream)
        .pipe(flatten())
        .pipe(dedupe())
        .pipe(sourcemaps.init())
        .pipe(concat('all.js'))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(config.jsOutput));
});
