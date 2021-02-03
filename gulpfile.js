var gulp = require('gulp');
var concat = require('gulp-concat');
var minify = require('gulp-minify');
var cleanCss = require('gulp-clean-css');
 
gulp.task('pack-js', function () {	
	return gulp.src(['layouts/lib/jquery/jquery.min.js', 'layouts/lib/jquery/jquery-migrate-1.0.0.js', 'layouts/lib/jquery/purl.js', 'layouts/lib/jquery/select2/select2.min.js', 'layouts/lib/jquery/jquery.class.min.js', 'layouts/lib/jquery/jquery-ui-1.11.3.custom/jquery-ui.js', 'libraries/jquery/jstorage.min.js', 'layouts/lib/jquery/jquery-validation/jquery.validate.min.js', 'layouts/lib/jquery/jquery.slimscroll.min.js', 'libraries/jquery/jquery.ba-outside-events.min.js', 'libraries/jquery/defunkt-jquery-pjax/jquery.pjax.js', 'libraries/jquery/multiplefileupload/jquery_MultiFile.js', 'libraries/jquery/jquery.additions.js', 'layouts/lib/bootstrap-notify/bootstrap-notify.min.js', 'layouts/lib/jquery/websockets/reconnecting-websocket.js', 'layouts/lib/jquery/jquery-play-sound/jquery.playSound.js', 'layouts/lib/jquery/malihu-custom-scrollbar/jquery.mousewheel.min.js', 'layouts/lib/jquery/malihu-custom-scrollbar/jquery.mCustomScrollbar.js', 'layouts/lib/jquery/autoComplete/jquery.textcomplete.js', 'layouts/lib/jquery/jquery.qtip.custom/jquery.qtip.js', 'libraries/jquery/jquery-visibility.min.js', 'layouts/lib/jquery/daterangepicker/moment.min.js', 'layouts/lib/jquery/daterangepicker/jquery.daterangepicker.js', 'layouts/lib/jquery/jquery.timeago.js'])
		.pipe(concat('app.js'))
        .pipe(minify())
		.pipe(gulp.dest('layouts/lib'));
});

gulp.task('pack-css', function () {
	return gulp.src(['layouts/lib/todc/css/bootstrap.min.css', 'layouts/lib/todc/css/docs.min.css', 'layouts/lib/todc/css/todc-bootstrap.min.css', 'layouts/lib/font-awesome/css/font-awesome.min.css', 'layouts/lib/jquery/select2/select2.css', 'layouts/lib/select2-bootstrap/select2-bootstrap.css', 'libraries/bootstrap/js/eternicode-bootstrap-datepicker/css/datepicker3.css', 'layouts/lib/jquery/jquery-ui-1.11.3.custom/jquery-ui.css', 'layouts/lib/animate/animate.min.css', 'layouts/lib/jquery/malihu-custom-scrollbar/jquery.mCustomScrollbar.css', 'layouts/lib/jquery/jquery.qtip.custom/jquery.qtip.css', 'layouts/lib/jquery/daterangepicker/daterangepicker.css', 'layouts/lib/jo-icons/style.css'])
		.pipe(concat('app.css'))
        .pipe(cleanCss())
		.pipe(gulp.dest('layouts/lib'));
});
 
gulp.task('default', ['pack-js', 'pack-css']);
