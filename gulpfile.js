var gulp = require('gulp');
var bower = require('gulp-bower');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var watch = require('gulp-watch');

gulp.task('init', function() {
    bower()
        .pipe(gulp.dest('app/Resources/lib/'))
});

gulp.task('default', function() {
    gulp.src('app/Resources/styles/style.scss')
        .pipe(sass())
        .pipe(gulp.dest('web/css'));

    gulp.src(['app/Resources/lib/angular/angular.js',
            'app/Resources/scripts/script.js',
            'app/Resources/lib/jquery/jquery.js',
            'app/Resources/lib/bootstrap-sass-official/vendor/assets/javascripts/bootstrap/collapse.js',
            'app/Resources/lib/bootstrap-sass-official/vendor/assets/javascripts/bootstrap.js'])
        .pipe(concat('all.js'))
        .pipe(gulp.dest('web/js'));

    gulp.src('app/Resources/lib/bootstrap-sass-official/vendor/assets/fonts/bootstrap/*')
        .pipe(gulp.dest('web/fonts'));
});

//gulp.task('watch', function () {
//    gulp.src('app/Resources/styles/*')
//        .pipe(watch(function() {
//            return gulp.src('app/Resources/styles/main.scss')
//                .pipe(sass())
//                .pipe(gulp.dest('web/assets/css/'));
//        }));
//});

//gulp.task('watchJS', function () {
//    gulp.src('app/Resources/styles/*')
//        .pipe(watch(function() {
//            return gulp.src(['app/Resources/lib/angular/angular.js',
//                    'app/Resources/scripts/script.js',
//                    'app/Resources/lib/jquery/jquery.js'])
//                .pipe(concat('all.js'))
//                .pipe(gulp.dest('web/js'));
//        }));
//});