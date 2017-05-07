// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var sass = require('gulp-ruby-sass');
var path = require('path');
var changed = require('gulp-changed');
var notify = require("gulp-notify");
var ftp = require('gulp-ftp');
var spritesmith = require('gulp.spritesmith');
var merge = require('merge-stream');
var buffer = require('vinyl-buffer');
var imagemin = require('gulp-imagemin');
var pngquant = require('imagemin-pngquant');

//General
var themeName = 'default-bootstrap';
var projectDir = path.resolve(__dirname);

var paths = {
    prestashopImgDir: './themes/default-bootstrap/img/',
    prestashopSpriteFiles: './themes/default-bootstrap/img/sprite/*.*',
    prestashopSassDir: './themes/default-bootstrap/sass/',
    prestashopSassFiles: './themes/default-bootstrap/sass/**/*.scss',
    prestashopCssDir: './themes/default-bootstrap/css'
};
var sassConfig = {
    style: 'production',
    compass: true,
    loadPath: [projectDir + '/themes/'+ themeName +'/sass']
};


var ftpConnect = {
    // host: '',
    // user: '',
    // pass: ''
};

/* Task
* Compile our prestashop SASS files
*/
gulp.task('sass', function() {
    ftpConnect.remotePath = '/themes/default-bootstrap/css';
    return gulp.src(paths.prestashopSassFiles)
        .pipe(changed(paths.prestashopCssDir,{ extension: '.css' }))
        .pipe(sass(sassConfig))
        // .pipe(ftp(ftpConnect))
        .pipe(gulp.dest(paths.prestashopCssDir))
        .pipe(notify("SASS Done!"));
});


gulp.task('sass:all', function() {
    ftpConnect.remotePath = '/themes/default-bootstrap/css';
    return gulp.src(paths.prestashopSassFiles)
        .pipe(sass(sassConfig))
        // .pipe(ftp(ftpConnect))
        .pipe(gulp.dest(paths.prestashopCssDir));
});

gulp.task('sprite', function() {
    ftpConnect.remotePath = '/themes/default-bootstrap/img';
    var spriteData =
        gulp.src(paths.prestashopSpriteFiles) // путь, откуда берем картинки для спрайта
            .pipe(spritesmith({
                imgName: 'sprite.png',
                cssName: '_sprite.scss',
                algorithm: 'binary-tree',
                imgPath : '../img/sprite.png',
                padding: 5,
            }));

    var imgStream = spriteData.img
        .pipe(buffer())
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()],
            interlaced: true
        }))
        // .pipe(ftp(ftpConnect))
        .pipe(gulp.dest(paths.prestashopImgDir)); // путь, куда сохраняем картинку
    var cssStream = spriteData.css.pipe(gulp.dest(paths.prestashopSassDir)); // путь, куда сохраняем стили

    return merge(imgStream, cssStream).pipe(notify("Sprite Done!"));
});

/* Task
* Watch Files For Changes
*/
gulp.task('watch', function() {
    gulp.watch(paths.prestashopSassFiles, ['sass']);
    gulp.watch(paths.prestashopSpriteFiles, ['sprite']);
});

// Default Task
gulp.task('default', ['sass', 'sprite', 'watch']);