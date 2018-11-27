var gulp = require('gulp');
var $    = require('gulp-load-plugins')();
var exec = require('child_process').exec;
var cleanCSS = require('gulp-clean-css');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var jshint = require('gulp-jshint');
var postcss     = require('gulp-postcss');
var reporter    = require('postcss-reporter');
var syntax_scss = require('postcss-scss');
var stylelint   = require('stylelint');
var autoprefixer = require('autoprefixer');
var runSequence = require('run-sequence');
var svgSprite = require('gulp-svg-sprite');
//var clean = require('gulp-clean');

// if you need a new jquery plugin, add it here
var vendorjs = [
  'source/js/jquery.js',
  'source/js/jqfix.js',
  'source/js/jquery.fitvids.js',
  'source/js/galleryview/js/jquery.easing.js',
  'source/js/galleryview/js/jquery.galleryview-ioby.js',
  'source/js/galleryview/js/jquery.galleryview.min.js',
  'source/js/galleryview/js/jquery.ioby-rotator.js',
  'source/js/galleryview/js/jquery.timers.js',
  'source/js/curvycorners.js',
  'source/js/html5.js',
  'source/js/countUp.js',
  'source/js/countUp-jquery.js',
  'source/js/jquery.waypoints.min.js',
  'source/js/inview.min.js',
  'source/js/typed.min.js'
];

// if you need some vendor css, add it here:
var vendorcss = [
  'source/js/galleryview/css/jquery.galleryview.css'
];

var cssvendorassets = [
  'source/js/galleryview/css/loader.gif',
  'source/js/galleryview/css/themes/dark/next.png',
  'source/js/galleryview/css/themes/dark/prev.png',
  'source/js/galleryview/css/themes/dark/panel-nav-next.png',
  'source/js/galleryview/css/themes/dark/panel-nav-prev.png',
  'source/js/galleryview/css/themes/light/next.png',
  'source/js/galleryview/css/themes/light/prev.png',
  'source/js/galleryview/css/themes/light/panel-nav-next.png',
  'source/js/galleryview/css/themes/light/panel-nav-prev.png',
  'source/css/*.png',
  'source/css/*.gif',
];


// this lints our Sass - sometimes this is just plain annoying, so feel free
// to adjust as needed if it's giving you issues.
gulp.task('scss-lint', function(){
  var stylelintConfig = {
    "rules": {
      "block-no-empty": true,
      "color-no-invalid-hex": true,
      "declaration-colon-space-before": "never",
      "media-feature-colon-space-after": "always",
      "media-feature-colon-space-before": "never",
      "max-empty-lines": 5,
      "number-no-trailing-zeros": true,
      "declaration-block-no-duplicate-properties": true,
      "declaration-block-trailing-semicolon": "always",
    }
  }

  var processors = [
    stylelint(stylelintConfig),
    reporter({
      clearMessages: true,
      throwError: true
    })
  ];

  return gulp.src(
      ['source/scss/**/*.scss', '!source/scss/vendors/**/*.scss']
    )
    .pipe(postcss(processors, {syntax: syntax_scss}));
});

// compile CSS
gulp.task('css-compile', function(){

  return gulp.src(['source/scss/app.scss', 'source/_patterns/**/*.scss'])
    .pipe($.sass().on('error', $.sass.logError))
    .pipe(concat('app.min.css'))
    .pipe(postcss([ autoprefixer({
      browsers: [
      '> 1%',
      'last 2 versions',
      'Firefox ESR',
      'Opera 12.1',
      'ie >= 9',
      'Firefox >= 23',
      'Chrome >= 22',
      'Safari >= 4',
      'iOS >= 5.1',
      'Android >= 4.1'
      ]
    }) ]))
    .pipe(cleanCSS({compatibility: 'ie9'}))
    .pipe(gulp.dest('public/css'));
});


// compile the copies of drupal css
gulp.task('cms-css-compile', function(){

  return gulp.src(['source/cms/*'])
    .pipe($.sass().on('error', $.sass.logError))
    .pipe(concat('cms.min.css'))
    .pipe(postcss([ autoprefixer({
      browsers: [
      '> 1%',
      'last 2 versions',
      'Firefox ESR',
      'Opera 12.1',
      'ie >= 9',
      'Firefox >= 23',
      'Chrome >= 22',
      'Safari >= 4',
      'iOS >= 5.1',
      'Android >= 4.1'
      ]
    }) ]))
    .pipe(cleanCSS({compatibility: 'ie9'}))
    .pipe(gulp.dest('public/css'));
});

// compile vendor CSS
gulp.task('css-vendor', function(){
  return gulp.src(vendorcss)
    .pipe(concat('plugins.min.css'))
    .pipe(cleanCSS({compatibility: 'ie9'}))
    .pipe(gulp.dest('css'));
});

gulp.task('css-vendor-assets', function(){
  return gulp.src(cssvendorassets)
    .pipe(gulp.dest('public/css'));
});

// compile javascript
// gulp.task('js', function () {
//   gulp.src('source/js/app.js')
//     .pipe(jshint())
//     .pipe(jshint.reporter('default'))
//     .pipe(uglify())
//     .pipe(rename({
//       suffix: '.min'
//     }))
//     .pipe(gulp.dest('public/js'));
// });

// compile our javascript
gulp.task('js', function(){
  return gulp.src(['source/js/app.js','source/js/refresh.js'])
    .pipe(concat('scripts.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('public/js'));
});

// compile vendor javascript
gulp.task('js-vendor', function(){
  return gulp.src(vendorjs)
    .pipe(concat('plugins.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('public/js'));
});

// Create an SVG sprite for all icons that don't need animation
// gulp.task('svgsprites', function () {
//   return gulp.src('**/*.svg', {cwd: 'img/svg'})
//     .pipe(svgSprite(
//       config = {
//         mode : {
//           css : {
//             sprite: "../img/svg-compiled/sprite.svg",
//             bust: true,
//             dimensions: true,
//             prefix: "svg-%s",
//             render: {
//                 scss: {
//                     template: 'scss/xfiles/sprite.scss.handlebars',
//                     dest: "../scss/xfiles/_sprite.scss"
//                 }
//             },
//             dest: "css",
//           }
//         }
//       }
//     ))
//     .pipe(gulp.dest('.'));
// }); 

// Pipe all SVG icons that need animation into the pattern library as twig files

// gulp.task('svginline', function () {
//   return gulp.src('**/*.svg', {cwd: 'img/svg-inline'})
//     .pipe(rename(
//       {
//         extname: ".twig"
//       }
//     ))
//     .pipe(gulp.dest('source/_patterns/00-base/20-inline-svg'));
// });


// gulp.task('cleansvg', function () {
//   return gulp.src('img/svg-compiled/*', {read: false})
//     .pipe(clean());
// });


// compile the pattern library
gulp.task('plab', function (cb) {
  exec('php ./core/console --generate', function (err, stdout, stderr) {
    console.log(stdout);
    console.log(stderr);
    cb(err);
  });
});

// compile CSS
gulp.task('plab-css', function(){

  return gulp.src(['source/scss/xfiles/plab.scss'])
    .pipe($.sass().on('error', $.sass.logError))
    .pipe(concat('plab.min.css'))
    .pipe(postcss([ autoprefixer({
      browsers: [
      '> 1%',
      'last 2 versions',
      'Firefox ESR',
      'Opera 12.1',
      'ie >= 9',
      'Firefox >= 23',
      'Chrome >= 22',
      'Safari >= 4',
      'iOS >= 5.1',
      'Android >= 4.1'
      ]
    }) ]))
    .pipe(cleanCSS({compatibility: 'ie9'}))
    .pipe(gulp.dest('public/css'));
});


// // the "watch" task - triggers events on save
// gulp.task('default', function() {
//   gulp.watch(['source/scss/**/*.scss','source/_patterns/**/*.scss'], ['scss-lint','css-compile']);
//   gulp.watch(['source/**/**'], ['plab', 'css-compile', 'cms-css-compile']); //@TODO make this more efficient
//   gulp.watch(['source/js/app.js'], ['js']);
// });

// the "watch" task - triggers events on save
gulp.task('default', function() {
  //gulp.watch(['source/scss/**/*.scss','source/_patterns/**/*.scss'], ['build']);
  gulp.watch(['source/**/**'], ['build']); //@TODO make this more efficient
  //gulp.watch(['source/js/app.js'], ['build']);
});

// compile css once
gulp.task('css', function(cb) {
  runSequence(
    'scss-lint',
    'css-compile', 
    cb);
});

// compile all svgs once (also recompiles css and plab)
/*
gulp.task('svg', function(cb) {
  runSequence(
    //'cleansvg',
    ['svginline', 'svgsprites'],
    'css',
    'plab',
    cb);
});
*/

// compile everything once
gulp.task('build', function(cb) {
  runSequence(
  //['svginline', 'svgsprites'],
  'scss-lint',
  'plab',
  ['css-compile', 'cms-css-compile', 'css-vendor', 'css-vendor-assets', 'js', 'js-vendor'],
  //'plab',
  cb)
});

// just incase someone tries to run 'gulp watch' instead of just 'gulp'
gulp.task('watch', ['default']);

// @TODO: stop plab from duplicating cms source css files into public
