/**
 * jQuery Framer Plugin
 *
 * @author		Hirohisa Nagai
 * @version		0.80
 * @copyright	eternity design ( http://eternitydesign.net/ )
 * @license		MIT License
 *
 * include spin.js
 * fgnass.github.com/spin.js#v2.1.0
 *
 */

(function($) {
	var FRM = $.Framer = {};


	$.fn.Framer = function(settings) {
		settings = $.extend({
			animation: "fade",
			loadingColor: '#fff',
			opacity: 0.8,
			overlayTime: 500,
			isOverlayClose: true,
			isAutoResize: true,
			isScroll: true,
			resizeRatio: 0.9,
			speed: 500,
			title: '<div id="frmTitle"></div>',
			description: '<div id="frm_description">{description}</div>',
			closeBtn: '<div class="close_btn"></div>',
			inner: {},
			width: 640,
			height: 360,
			iframe: '<iframe name="framer-iframe" frameborder="0" id="framer-iframe"></iframe>',
			ajaxDataType: 'html',
			blur: '',
			isPushState: false,
			isCSSAnim: false
		}, settings);


		FRM.target;
		FRM.body;
		FRM.contents;
		FRM.indicator;
		FRM.box;
		FRM.type;
		FRM.title;
		FRM.description;
		FRM.closeBtn;
		FRM.container;
		FRM.blurTarget;
		FRM.animation;

		var loading;
		var overlay;
		var scrollTimer;
		var isMove = false;
		var baseURL = location.href;


		FRM.open = function(e) {
			FRM.body = $('body');

			//console.log('$.Framer.open');
			overlay = $('<div id="frm_overlay"></div>');
			overlay.css('opacity', 0);
			overlay.height($(document).height()).width($(window).width());


			loading = $('<div id="loading"></div>').css({
				width: $(window).width(),
				height: $(window).height(),
				top: $(window).scrollTop(),
				left: 0
			});

			var loading_options = {
				lines: 12,
				width: 4,
				color: settings.loadingColor
			};

			FRM.indicator = new Spinner(loading_options).spin(loading[0]);
			FRM.body.append(loading);

			FRM.body.append(overlay);
			overlay.fadeTo(settings.overlayTime, settings.opacity);

			// Blur
			if(settings.blur !== '') {
				FRM.blurTarget = $(settings.blur).Vague({
					intensity: 8,
					animationOptions: {
						duration: settings.speed,
						easing: 'linear'
					}
				});
				FRM.blurTarget.blur();
			}
			
			FRM.box = $('<div id="framer"></div>');
			FRM.container = $('<div id="framerContainer" />');
			FRM.box.append(FRM.container);

			if(arguments.length > 1) {
				// API call
				// console.log("API!!!", arguments[0]);
				FRM.target = $("<a />");
				FRM.target.attr("href", arguments[0]);
				FRM.target.attr("data-framer-type", arguments[1]);

				if($.isPlainObject(arguments[2])) {
					settings = $.extend(settings, arguments[2]);
				}
			}
			else {
				FRM.target = $(this);
			}

			FRM.animation = FRM.target.attr('data-framer-animation') || settings.animation;

			if(FRM.animation !== "fade") {
				FRM.container.css({
					"transition-duration": String(settings.speed * 0.001) + "s"
				});
				FRM.box.addClass(FRM.animation);
			}

			if(!$.isEmptyObject(settings.inner)) {
				FRM.box.append(settings.inner);
			}
			
			if(FRM.target.attr('title')) {
				if(settings.title != '') {
					if(settings.title.search(/{title}/)) {
						FRM.title = $(settings.title.replace(/{title}/, FRM.target.attr('title')));
					}
					else {
						FRM.title = $(settings.title).text(FRM.target.attr('title'));
					}

					FRM.container.append(FRM.title);
				}
			}

			if(FRM.target.attr('data-framer-description')) {
				if(settings.description != '') {
					if(settings.description.search(/{description}/)) {
						FRM.description = $(settings.description.replace(/{description}/, FRM.target.attr('data-framer-description')));
					}
					else {
						FRM.description = $(settings.description).text(FRM.target.attr('data-framer-description'));
					}

					FRM.container.append(FRM.description);
				}
			}

			FRM.type = getType(FRM.target.attr('href'), FRM.target.attr('data-framer-type'));

			if(FRM.type == 'image') {
				FRM.contents = $("<img />").on("load", function() {
					loadImageComplete();
					// FRM.contents = setBoxSize(FRM.contents);
					// showContents();
				}).on("error", function() {
					//console.log("error", settings.resources[key][0]);
				});
				FRM.contents.attr("src", FRM.target.attr('href'));
			}
			else if(FRM.type == 'inline') {
				FRM.contents = getInlineContents();
			}
			else if(FRM.type == 'video') {
				FRM.contents = getVideoJSContents();
			}
			else if(FRM.type == 'youtube') {
				FRM.contents = getYoutubeContents();
			}
			else if(FRM.type == 'vimeo') {
				FRM.contents = getVimeoContents();
			}
			else if(FRM.type == 'soundcloud') {
				FRM.contents = getSCContents();
			}
			else if(FRM.type == 'twitch') {
				FRM.contents = getTwitchContents();
			}
			else if(FRM.type == 'iframe') {
				FRM.contents = getiFrameContents();
			}
			else if(FRM.type == 'ajax') {
				getAjaxContents.apply(this);
			}

			if(FRM.type != 'image' && FRM.type != 'ajax') {
				showContents();
			}
			
			FRM.box.addClass(FRM.type);
			
			$(window).on('resize.Framer', resizeFramer);
			
			if(settings.isScroll) {
				$(window).on('scroll.Framer', scrollEvent);
				$(window).on('scrollComplete.Framer', scrollCompleteEvent);
			}

			return false;
		};


		FRM.close = function() {
			$(window).off('resize.Framer', resizeFramer);
			FRM.closeBtn.off("click", $.Framer.close);

			if(settings.isScroll) {
				$(window).off('scroll.Framer', scrollEvent);
				$(window).off('scrollComplete.Framer', scrollCompleteEvent);
			}
			
			if(settings.isOverlayClose) {
				overlay.off("click", $.Framer.close);
			}

			if(settings.closeBtn != '') {
				FRM.closeBtn.fadeOut(settings.speed);
			}
			overlay.fadeOut(settings.overlayTime);

			if(settings.blur !== '') {
				FRM.blurTarget.unblur();
			}

			if(FRM.animation !== "fade") {
				FRM.container.on("transitionend webkitTransitionEnd", destroyBox);
				FRM.box.removeClass('show');
				// setTimeout(destroyBox, settings.speed);
			}
			else {
				FRM.box.fadeOut(settings.speed, destroyBox);
			}
		};


		var destroyBox = function() {
			if(FRM.animation !== "fade") {
				FRM.container.off("transitionend webkitTransitionEnd", destroyBox);
				FRM.box.css({"display": "none"});
			}

			if(FRM.type == 'inline') {
				FRM.contents.hide();
				FRM.body.append(FRM.contents);
			}
			else if(FRM.type == 'video') {
				FramerVideo.destroy();
			}
			
			if(!$.isEmptyObject(settings.inner)) {
				$(settings.inner).remove();
			}
			
			if(FRM.title) {
				FRM.title.remove();
			}
			if(FRM.description) {
				FRM.description.remove();
			}

			FRM.closeBtn.remove();
			if(FRM.type != 'inline') {
				FRM.contents.remove();
			}

			FRM.container.remove();
			FRM.box.remove();
			overlay.remove();

			if(settings.isPushState && isMove) {
				window.history.back();
			}
			
			FRM.body.trigger('close.Framer');
		};


		var loadImageComplete = function() {
			FRM.contents = setBoxSize(FRM.contents);
			showContents();
		}


		var showContents = function() {
			FRM.container.append(FRM.contents);
			FRM.body.append(FRM.box);

			setPosition();
			
			FRM.indicator.stop();
			loading.remove();
			delete FRM.indicator;
			
			if(settings.closeBtn != '') {
				FRM.closeBtn = $(settings.closeBtn);
			}
			
			if(FRM.animation !== "fade") {
				// FRM.box.css({
				// 	"display": "block"
				// }).addClass('show').delay(settings.speed, showContentsComplete);
				FRM.box.css({
					"display": "block"
				}).addClass('show');
				FRM.container.on("transitionend webkitTransitionEnd", showContentsComplete);
			}
			else {
				FRM.box.fadeIn(settings.speed, function() {
					showContentsComplete();
				});
			}
		};


		var showContentsComplete = function() {
			if(FRM.animation !== "fade") {
				FRM.container.off("transitionend webkitTransitionEnd", showContentsComplete);
			}

			if(FRM.type == 'video') {
				FramerVideo = _V_("framer_video", $.parseJSON(FRM.target.attr('data-framer-video-setup')));

				if(isIE()) {
					//console.log('noCloneEvent');
					var source = FRM.target.attr('href');
					if(!source.match(/\.webm$/i) && !source.match(/\.webm$/i) && !source.match(/\.mp4$/i)) {
						FramerVideo.src(source + '.mp4');
					}
					else if(source.match(/\.mp4$/i)) {
						FramerVideo.src(source);
					}
					//console.log('video: ', FRM.contents.width(), FRM.contents.height());
					FramerVideo.width(FRM.contents.width() || FRM.target.attr('data-framer-width') || settings.width);
					FramerVideo.height(FRM.contents.height() || FRM.target.attr('data-framer-height') || settings.height);
				}
			}
			
			if(settings.isOverlayClose) {
				overlay.on("click", $.Framer.close);
			}
			
			if(settings.closeBtn != '') {
				FRM.container.append(FRM.closeBtn);
				FRM.closeBtn.fadeIn(settings.speed);
				FRM.closeBtn.on("click", $.Framer.close);
			}

			// pushState
			if(settings.isPushState) {
				if(FRM.target.attr('data-framer-ps') != null) {
					// window.history.pushState(FRM.target.attr('data-framer-ps'), "", "#" + FRM.target.attr('data-framer-ps'));
					window.history.pushState(FRM.target.attr('data-framer-ps'), "", FRM.target.attr('data-framer-ps'));
					isMove = true;
				}
			}

			FRM.body.trigger('open.Framer');
		};
		
		
		var setPosition = function() {
			FRM.box.css({
				top: Math.floor(($(window).height() - FRM.box.outerHeight()) * 0.5) + $(window).scrollTop(),
				left: Math.floor(($(window).width() - FRM.box.outerWidth()) * 0.5)
			});
		};

		// windowサイズから、#framerに利用可能なサイズを計算。設定
		var setBoxSize = function(contents) {
			var cw, ch, ratio;

			if(FRM.type === "image") {
				var is = getImageSize(FRM.contents[0]);

				cw = parseInt(FRM.target.attr('data-framer-width') || is.width);
				ch = parseInt(FRM.target.attr('data-framer-height') || is.height);
			}
			else if(FRM.type === "soundcloud") {
				cw = parseInt(FRM.target.attr('data-framer-width') || settings.width);
				ch = 166;
			}
			else if(FRM.type === "twitch") {
				cw = parseInt(FRM.target.attr('data-framer-width') || 620);
				ch = parseInt(FRM.target.attr('data-framer-height') || 378);
			}
			else if(FRM.type === "inline" || FRM.type === "ajax") {
				cw = parseInt(FRM.target.attr('data-framer-width') || contents.outerWidth() || settings.width);
				ch = parseInt(FRM.target.attr('data-framer-height') || contents.outerHeight() || settings.height);
			}
			else {
				cw = parseInt(FRM.target.attr('data-framer-width') || settings.width);
				ch = parseInt(FRM.target.attr('data-framer-height') || settings.height);
			}

			// #framerContainer のborderとpaddingのサイズを取得する。この時点ではwidth、heightともに0なはずのなので・・・
			var containerOuterWidth = FRM.container.outerWidth() === 0 ? FRM.container.outerWidth() : FRM.container.outerWidth() - FRM.container.width();
			var containerOuterHeight = FRM.container.outerHeight() === 0 ? FRM.container.outerHeight() : FRM.container.outerHeight() - FRM.container.height();

			// #framerContainerのpadding、border-sizeとコンテンツのサイズを足した数値。これが#framerのサイズとなるため、縮小する場合のベースの値となる
			var targetWidth = containerOuterWidth === 0 ? cw + containerOuterWidth : containerOuterWidth - cw;
			var targetHeight = containerOuterHeight === 0 ? ch + containerOuterHeight : containerOuterHeight - ch;

			var ww = $(window).width();
			var wh = $(window).height();

			// var emw = containerOuterWidth - FRM.box.width();
			// var emh = containerOuterHeight - FRM.box.height();

			var mw = ww - cw;
			var mh = wh - ch;

			var innerHeight = FRM.box.height() - ch;
			
			// console.log("setMovieSize", cw, ch, containerOuterWidth, containerOuterHeight, ww, wh, mw, mh);

			if(mw > mh) {	// 縦スペースが横スペースより小さい
				if(wh * settings.resizeRatio < targetHeight) {
					// リサイズ処理
					if(settings.isAutoResize) {
						// console.log("hhhh", targetHeight);
						// まずは一番親のコンテナとなる#framerのサイズを設定。この時、#framerContainerのpadding.border-sizeを含んだサイズ
						FRM.box.height(wh * settings.resizeRatio);	// Framerへのpaddingを考慮に入れた数値
						ratio = FRM.box.height() / targetHeight;
						FRM.box.width(targetWidth * ratio);

						// #framerに設定されたサイズから、#framerContainerのpadding、border-sizeを引いたサイズをコンテンツに設定
						// contents.width(parseInt(FRM.box.width() - containerOuterWidth));
						// contents.height(parseInt(FRM.box.height() - containerOuterHeight));
						contents = setContentsSize(contents, parseInt(FRM.box.width() - containerOuterWidth), parseInt(FRM.box.height() - containerOuterHeight));
					}
				}
				else {
					FRM.box.width(targetWidth);
					FRM.box.height(targetHeight);

					contents = setContentsSize(contents, cw, ch);
					// contents.width(cw);
					// contents.height(ch);

					// contents.attr({
					// 	width: cw,
					// 	height: ch
					// });
				}
			}
			else {	// 横スペースが縦より小さい
				if(ww * settings.resizeRatio < targetWidth) {
					// リサイズ処理
					if(settings.isAutoResize) {
						FRM.box.width(ww * settings.resizeRatio);
						ratio = FRM.box.width() / targetWidth;
						FRM.box.height(targetHeight * ratio);

						// #framerに設定されたサイズから、#framerContainerのpadding、border-sizeを引いたサイズをコンテンツに設定
						// contents.attr({
						// 	width: parseInt(FRM.box.width() - containerOuterWidth),
						// 	height: parseInt(FRM.box.height() - containerOuterHeight)
						// });
						contents = setContentsSize(contents, parseInt(FRM.box.width() - containerOuterWidth), parseInt(FRM.box.height() - containerOuterHeight));
					}
				}
				else {
					FRM.box.width(targetWidth);
					FRM.box.height(targetHeight);

					contents = setContentsSize(contents, cw, ch);

					// contents.attr({
					// 	width: cw,
					// 	height: ch
					// });
				}
			}

			return contents;
		};

		var setContentsSize = function(contents, w, h) {
			// 渡される値は、画面サイズから計算されたcontent部分に利用可能なサイズなので、ここでcontent自体に設定されているpadding、borderを引いたサイズを設定する。

			var contentsWidth = contents.width();
			var contentsHeight = contents.height();

			if(contentsWidth === 0) {
				// そもそもcontentsのサイズを取得できなかったら、パラメータの値そのまま設定する
				contents.width(w);
			}
			else {
				var pbWidth = contents.outerWidth() - contentsWidth;
				if(pbWidth > 0) {
					contents.width(w - pbWidth);
				}
				else {
					contents.width(w);
				}
			}
			
			if(contentsHeight === 0) {
				// そもそもcontentsのサイズを取得できなかったら、パラメータの値そのまま設定する
				contents.height(h);
			}
			else {
				var pbHeight = contents.outerHeight() - contentsHeight;
				if(pbHeight > 0) {
					contents.height(h - pbHeight);
				}
				else {
					contents.height(h);
				}
			}

			if(FRM.type === "video") {
				// console.log("video", contents, contents.width());
				contents.attr({
					width: contents.width(),
					height: contents.height()
				});
			}

			return contents;
		}


		var resizeFramer = function(e) {
			overlay.height($(document).height()).width($(window).width());

			scrollCompleteEvent();
		};


		var scrollEvent = function() {
			if(scrollTimer) {
				clearTimeout(scrollTimer);
			}
			scrollTimer = setTimeout(function() {
				scrollTimer = null;
				$(window).trigger('scrollComplete.Framer');
			}, 500);
		};


		var scrollCompleteEvent = function() {
			FRM.contents = setBoxSize(FRM.contents);

			FRM.box.stop().animate({
				top: Math.floor(($(window).height() - FRM.box.outerHeight()) * 0.5) + $(window).scrollTop(),
				left: Math.floor(($(window).width() - FRM.box.outerWidth()) * 0.5)
			},
			settings.speed);
		};


		var getType = function(url, type) {
			if(url.match(/youtube\.com\/watch/i) || url.match(/youtu\.be/i) || type == 'youtube') {
				return "youtube";
			}
			else if(url.match(/vimeo\.com/i) || type == 'vimeo') {
				return "vimeo";
			}
			else if(url.match(/soundcloud\.com/i) || type == 'soundcloud') {
				return "soundcloud";
			}
			else if(url.match(/twitch\.tv/i) || type == 'twitch') {
				return "twitch";
			}
			else if(url.substr(0, 1) == '#' || type == 'inline') {
				return "inline";
			}
			else if(type == 'video') {
				return 'video';
			}
			else if(type == 'iframe') {
				return 'iframe';
			}
			else if(type =='ajax') {
				return 'ajax';
			}
			else if(url.match(/\.(gif|jpg|jpeg|png)$/i) || type == 'image') {
				return "image";
			}
		};


		var getInlineContents = function() {
			var inline = $(FRM.target.attr('href')).show();

			inline = setBoxSize(inline);

			return inline;
		};


		var getVideoJSContents = function() {
			var video = $('<video id="framer_video"></video>');
			video.addClass(FRM.target.attr('data-framer-video-class'));
			
			var source = FRM.target.attr('href');
			if(source.match(/\.mp4$/i)) {
				video.append('<source src="' + source + '" type="video/mp4" />');
			}
			else if(source.match(/\.webm$/i)) {
				video.append('<source src="' + source + '" type="video/webm" />');
			}
			else if(source.match(/\.ogv$/i)) {
				video.append('<source src="' + source + '" type="video/ogv" />');
			}
			else {
				video.append('<source src="' + source + '.mp4" type="video/mp4" />');
				video.append('<source src="' + source + '.webm" type="video/webm" />');
				video.append('<source src="' + source + '.ogv" type="video/ogv" />');
			}

			video = setBoxSize(video);

			return video;
		};


		var getYoutubeContents = function() {
			var regx = FRM.target.attr('href').match(/(youtube\.com|youtu\.be)\/(v\/|u\/|embed\/|watch\?v=)?([^#\&\?]*).*/i);
			var movieId = regx[3];
			
			var youtube = $('<iframe frameborder="0"></iframe>');

			var option = "";
			if(FRM.target.attr('data-youtube-option')) {
				option = FRM.target.attr('data-youtube-option').replace(/^\?/, "");
			}

			youtube.attr({
				src: "http://www.youtube.com/embed/" + movieId + '?wmode=opaque' + "&" + option
			});

			youtube = setBoxSize(youtube);

			return youtube;
		};


		var getVimeoContents = function() {
			var regx = FRM.target.attr('href').match(/vimeo\.com\/([^#\&\?]*).*/i);
			var movieId = regx[1];
			
			var vimeo = $('<iframe frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');
			
			// <iframe src="http://player.vimeo.com/video/VIDEO_ID" width="WIDTH" height="HEIGHT" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
			vimeo.attr({
				src: "http://player.vimeo.com/video/" + movieId
			});

			vimeo = setBoxSize(vimeo);

			return vimeo;
		};


		var getTwitchContents = function() {
			// <object bgcolor="#000000" data="http://www.twitch.tv/swflibs/TwitchPlayer.swf" height="378" id="clip_embed_player_flash" type="application/x-shockwave-flash" width="620"><param name="movie" value="http://www.twitch.tv/swflibs/TwitchPlayer.swf" /><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="allowFullScreen" value="true" /><param name="flashvars" value="channel=assassinscreed&auto_play=false&start_volume=25&videoId=v3778016&device_id=65b095e38ed0b7d9" /></object><br /><a href="http://www.twitch.tv/assassinscreed" style="padding:2px 0px 4px; display:block; width: 320px; font-weight:normal; font-size:10px; text-decoration:underline;">Watch live video from AssassinsCreed on Twitch</a>
			// <iframe src="http://www.twitch.tv/singi751014/embed" frameborder="0" scrolling="no" height="378" width="620"></iframe><a href="http://www.twitch.tv/singi751014?tt_medium=live_embed&tt_content=text_link" style="padding:2px 0px 4px; display:block; width:345px; font-weight:normal; font-size:10px;text-decoration:underline;">Watch live video from singi751014 on www.twitch.tv</a>

			var twitch;
			var isLive = true;
			if(FRM.target.attr('href').match(/\/[a-z]{1}\/[0-9]+$/i)) {
				isLive = false;
			}

			// ライブプレイヤーの場合
			if(isLive) {
				var twitch = $('<iframe frameborder="0" scrolling="no"></iframe>');
				twitch.attr({
					src: FRM.target.attr('href') + '/embed'
				});

				twitch = setBoxSize(twitch);
			}
			else {
				var regx = FRM.target.attr('href').match(/twitch\.tv\/([^#\&\?\/]*)\/v\/([0-9]*)/i);
				var channel = regx[1];
				var videoId = regx[2];
				var twitch = $('<object bgcolor="#000000" data="http://www.twitch.tv/swflibs/TwitchPlayer.swf" id="clip_embed_player_flash" type="application/x-shockwave-flash"></object>');
				var params = '<param name="movie" value="http://www.twitch.tv/swflibs/TwitchPlayer.swf" /><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="allowFullScreen" value="true" /><param name="flashvars" value="channel=%channel%&auto_play=false&start_volume=25&videoId=v%videoId%&device_id=65b095e38ed0b7d9" />';
				params = params.replace("%channel%", channel);
				params = params.replace("%videoId%", videoId);

				twitch = setBoxSize(twitch);

				twitch.append(params);
			}

			return twitch;
		};


		var getSCContents = function() {
			//<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F34019569"></iframe>

			var soundcloud = $('<iframe frameborder="0"></iframe>');
			soundcloud.attr({
				src: "https://w.soundcloud.com/player/?url=" + FRM.target.attr('href')
			});

			soundcloud = setBoxSize(soundcloud);

			return soundcloud;
		};


		var getiFrameContents = function() {
			var iframe = $(settings.iframe);
			iframe.attr({
				src: FRM.target.attr('href')
			});

			iframe = setBoxSize(iframe);

			return iframe;
		};


		var getAjaxContents = function() {
			$.ajax({
				type: "GET",
				url: FRM.target.attr('href'),
				dataType: FRM.target.attr('data-framer-ajax-type') || settings.ajaxDataType,
				success: function(data) {
					FRM.contents = $(data);
					FRM.contents = setBoxSize(FRM.contents);
					showContents();
				},
				error: function(XMLHttpRequest, textStatus) {
					FRM.contents = $('<div id="framer_error">' + textStatus + '</div>');
					FRM.contents = setBoxSize(FRM.contents);
					showContents();
				}
			});
		};


		var getUrlParams = function(src) {
			var vars = [], hash;
			var hashes = src.slice(src.indexOf('?') + 1).split('&');
			for(var i = 0; i < hashes.length; i++) {
				hash = hashes[i].split('=');
				vars.push(hash[0]);
				vars[hash[0]] = hash[1];
			}

			return vars;
		};

		var getState = function() {
			var url = location.href;
			var ary = url.split("/");

			var str = ary[ary.length - 1];

			if(str.match(/.html$/)) {
				return ary[ary.length - 2];
			}
			else {
				return str;
			}
		};


		var getImageSize = function(img) {
			var w, h;
			if(typeof img.naturalWidth != 'undefined') {
				w = img.naturalWidth;
				h = img.naturalHeight;
			}
			else if(typeof img.runtimeStyle !== 'undefined') {
				//var run = img.runtimeStyle;
				// run.width  = "auto";
				// run.height = "auto";
				w = img.width;
				h = img.height;
			}
			else {
				w = img.width;
				h = img.height;
			}

			return {width: w, height: h};
		};


		var isIE = function() {
			if($.support.checkOn && $.support.noCloneEvent && !$.support.noCloneChecked && !$.support.cors) {
				return true;
			}
			else if(!$.support.opacity) {
				return true;
			}
			else {
				return false;
			}
		};


		var isIE8 = function() {
			if(!$.support.opacity) {
				if(!$.support.hrefNormalized) {
					return false;
				}
				else {
					return true;
				}
			}
			else {
				return false;
			}
		};

		var changeStateEvent = function(e) {
			// console.log("changeStateEvent", e);
			var state = e.originalEvent.state;
			// console.log("popstate : ", e.originalEvent.state, isMove);
			if(state == null && isMove == true) {
				// console.log("ismove");
				$.Framer.close();
			}
		};

		if(settings.isPushState) {
			// console.log("isPushState");
			$(window).on("popstate", $.proxy(changeStateEvent, this));
		}
		
		this.on('click.Framer', $.Framer.open);
		
		return this;
	};
})(jQuery);


/**
 * spin.js
 *
 * @version		2.1.0
 * @copyright	http://spin.js.org/
 *
 */
//fgnass.github.com/spin.js#v2.1.0
!function(a,b){"object"==typeof exports?module.exports=b():"function"==typeof define&&define.amd?define(b):a.Spinner=b()}(this,function(){"use strict";function a(a,b){var c,d=document.createElement(a||"div");for(c in b)d[c]=b[c];return d}function b(a){for(var b=1,c=arguments.length;c>b;b++)a.appendChild(arguments[b]);return a}function c(a,b,c,d){var e=["opacity",b,~~(100*a),c,d].join("-"),f=.01+c/d*100,g=Math.max(1-(1-a)/b*(100-f),a),h=j.substring(0,j.indexOf("Animation")).toLowerCase(),i=h&&"-"+h+"-"||"";return m[e]||(k.insertRule("@"+i+"keyframes "+e+"{0%{opacity:"+g+"}"+f+"%{opacity:"+a+"}"+(f+.01)+"%{opacity:1}"+(f+b)%100+"%{opacity:"+a+"}100%{opacity:"+g+"}}",k.cssRules.length),m[e]=1),e}function d(a,b){var c,d,e=a.style;for(b=b.charAt(0).toUpperCase()+b.slice(1),d=0;d<l.length;d++)if(c=l[d]+b,void 0!==e[c])return c;return void 0!==e[b]?b:void 0}function e(a,b){for(var c in b)a.style[d(a,c)||c]=b[c];return a}function f(a){for(var b=1;b<arguments.length;b++){var c=arguments[b];for(var d in c)void 0===a[d]&&(a[d]=c[d])}return a}function g(a,b){return"string"==typeof a?a:a[b%a.length]}function h(a){this.opts=f(a||{},h.defaults,n)}function i(){function c(b,c){return a("<"+b+' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">',c)}k.addRule(".spin-vml","behavior:url(#default#VML)"),h.prototype.lines=function(a,d){function f(){return e(c("group",{coordsize:k+" "+k,coordorigin:-j+" "+-j}),{width:k,height:k})}function h(a,h,i){b(m,b(e(f(),{rotation:360/d.lines*a+"deg",left:~~h}),b(e(c("roundrect",{arcsize:d.corners}),{width:j,height:d.scale*d.width,left:d.scale*d.radius,top:-d.scale*d.width>>1,filter:i}),c("fill",{color:g(d.color,a),opacity:d.opacity}),c("stroke",{opacity:0}))))}var i,j=d.scale*(d.length+d.width),k=2*d.scale*j,l=-(d.width+d.length)*d.scale*2+"px",m=e(f(),{position:"absolute",top:l,left:l});if(d.shadow)for(i=1;i<=d.lines;i++)h(i,-2,"progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");for(i=1;i<=d.lines;i++)h(i);return b(a,m)},h.prototype.opacity=function(a,b,c,d){var e=a.firstChild;d=d.shadow&&d.lines||0,e&&b+d<e.childNodes.length&&(e=e.childNodes[b+d],e=e&&e.firstChild,e=e&&e.firstChild,e&&(e.opacity=c))}}var j,k,l=["webkit","Moz","ms","O"],m={},n={lines:12,length:7,width:5,radius:10,scale:1,rotate:0,corners:1,color:"#000",direction:1,speed:1,trail:100,opacity:.25,fps:20,zIndex:2e9,className:"spinner",top:"50%",left:"50%",position:"absolute"};if(h.defaults={},f(h.prototype,{spin:function(b){this.stop();var c=this,d=c.opts,f=c.el=e(a(0,{className:d.className}),{position:d.position,width:0,zIndex:d.zIndex});if(e(f,{left:d.left,top:d.top}),b&&b.insertBefore(f,b.firstChild||null),f.setAttribute("role","progressbar"),c.lines(f,c.opts),!j){var g,h=0,i=(d.lines-1)*(1-d.direction)/2,k=d.fps,l=k/d.speed,m=(1-d.opacity)/(l*d.trail/100),n=l/d.lines;!function o(){h++;for(var a=0;a<d.lines;a++)g=Math.max(1-(h+(d.lines-a)*n)%l*m,d.opacity),c.opacity(f,a*d.direction+i,g,d);c.timeout=c.el&&setTimeout(o,~~(1e3/k))}()}return c},stop:function(){var a=this.el;return a&&(clearTimeout(this.timeout),a.parentNode&&a.parentNode.removeChild(a),this.el=void 0),this},lines:function(d,f){function h(b,c){return e(a(),{position:"absolute",width:f.scale*(f.length+f.width)+"px",height:f.scale*f.width+"px",background:b,boxShadow:c,transformOrigin:"left",transform:"rotate("+~~(360/f.lines*k+f.rotate)+"deg) translate("+f.scale*f.radius+"px,0)",borderRadius:(f.corners*f.scale*f.width>>1)+"px"})}for(var i,k=0,l=(f.lines-1)*(1-f.direction)/2;k<f.lines;k++)i=e(a(),{position:"absolute",top:1+~(f.scale*f.width/2)+"px",transform:f.hwaccel?"translate3d(0,0,0)":"",opacity:f.opacity,animation:j&&c(f.opacity,f.trail,l+k*f.direction,f.lines)+" "+1/f.speed+"s linear infinite"}),f.shadow&&b(i,e(h("#000","0 0 4px #000"),{top:"2px"})),b(d,b(i,h(g(f.color,k),"0 0 1px rgba(0,0,0,.1)")));return d},opacity:function(a,b,c){b<a.childNodes.length&&(a.childNodes[b].style.opacity=c)}}),"undefined"!=typeof document){k=function(){var c=a("style",{type:"text/css"});return b(document.getElementsByTagName("head")[0],c),c.sheet||c.styleSheet}();var o=e(a("group"),{behavior:"url(#default#VML)"});!d(o,"transform")&&o.adj?i():j=d(o,"animation")}return h});

