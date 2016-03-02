//
// mwitter.js
//

function Mwitter() {
	// construct
}


/**
 * Signup ビュー オンロード処理
 */
Mwitter.prototype.onLoadSignup = function() {
	var $signupForm = $('#signup-form');

	$signupForm.find('input[name="username"]').keyup(mw.onChangeSignupUsername);
}

/**
 * Signup ビュー Username 入力時の処理
 */
Mwitter.prototype.onChangeSignupUsername = function() {
	var $input = $('#signup-form input[name="username"]');
	var $submit = $('#signup-form button[type="submit"]');

	if ($input.val() == "") {
		$submit.prop('disabled', true);
		$input.closest('.form-group').removeClass('has-success').removeClass('has-error')
		$input.closest('.form-group').find('.status-ok').hide();
		$input.closest('.form-group').find('.status-ng').hide();
	} else {
		mw
		.checkSignupUsername($input.val())
		.success(function(data) {
			if (data['result'] == true) {
				// success
				$submit.prop('disabled', false);
				$input.closest('.form-group').removeClass('has-error').addClass('has-success');
				$input.closest('.form-group').find('.status-ng').hide();
				$input.closest('.form-group').find('.status-ok').show().insertAfter($input);
			} else {
				// not good
				$submit.prop('disabled', true);
				$input.closest('.form-group').removeClass('has-success').addClass('has-error');
				$input.closest('.form-group').find('.status-ok').hide();
				$input.closest('.form-group').find('.status-ng').show().insertAfter($input);
			}
		})
		.error(function(jqXHR, status ,err) {
			$input.closest('.form-group').removeClass('has-success').removeClass('has-error')
			console.log("ERROR at checkSignupUsername: "+status+", "+err);
		})
	}
};

/**
 * 新規 Username が有効か確認
 */
Mwitter.prototype.checkSignupUsername = function(username, options) {
	options = options || {};
	return ajax($.extend(options, {
		method: 'GET',
		url: '?controller=api/welcome&action=checkSignupUsername&username='+username,
		dataType: 'json',
	}));
};




/**
 * Timeline オンロード処理
 */
Mwitter.prototype.onLoadTimeline = function() {
	var $items = $('.timeline > li');

	// リプライの省略
	$items.each(function(i, item) {
		var $omits = $(item).find('.replies > li').slice(0,-1);
		$omits.hide();
	});

	// ツイートクリック時のイベント登録
	$items.find('.tweet').click(mw.onClickTimelineTweet);

	// ツイート入力時のイベント登録
	$items.find('.tweet-create form textarea[name="content"]').keyup(mw.onChangeCreateTweet);

	// ツイートボタンクリック時のイベント登録
	$items.find('.tweet-create form .btn-tweet').click(mw.onClickTweetButton);
};

/**
 * Timeline-item クリック時の動作
 */
Mwitter.prototype.onClickTimelineTweet = function(e) {
	var tweet = e.delegateTarget;
	var $item = $(tweet).closest('li.timeline-item');

	var $omits = $item.find('.replies > li').slice(0,-1);
	$omits.wrapAll('<div/>');
	if ($omits.is(':hidden')) {
		$omits.toggle();
		$omits.parent().hide().slideToggle(200,function() {
			$omits.unwrap();
		});
	} else {
		$omits.parent().slideToggle(200,function() {
			$omits.toggle();
			$omits.unwrap();
		});
	}
	
	$item.find('.reply').slideToggle(400);

	return false;
};

/**
 * ツイート入力時の動作
 */
Mwitter.prototype.onChangeCreateTweet = function(e) {
	var textarea = e.delegateTarget;
	var $form = $(textarea).closest('form');
	var $indicator = $form.find('span.lc-indicator');
	var $injection = $indicator.find('.injection');
	var $submit = $form.find('button.btn-tweet');

	var lc = 140 - textarea.value.length;
	$injection.text(lc);
	if (lc < 0) {
		$submit.prop('disabled', true);
	} else {
		$submit.prop('disabled', false);
	}
}

/**
 * ツイートボタンクリック時の動作
 */
Mwitter.prototype.onClickTweetButton = function(e) {
	var button = e.delegateTarget;
	var $form = $(button).closest('form');
	var param = $form.serializeObject();

	mw
	.postTweet(param)
	.success(mw.onSuccessPostTweet)
	.error(mw.onErrorPostTweet);
};

/**
 * ツイート後
 */
Mwitter.prototype.onSuccessPostTweet = function(data) {
	mw
	.getTweetView(data.tid)
	.success(function(view) {
		var $item = $('<li/>').html(view);
		if (data.parent_tid) {
			$item
			.insertAfter('.timeline #tweet-'+data.parent_tid).closest('li');
		} else {
			$item
			.addClass('timeline-item')
			.insertBefore('.timeline > li.timeline-item:first');
		}
	})
	.error(function(jqXHR, status ,err) {
		console.log(err);
	});
};
Mwitter.prototype.onErrorPostTweet = function(jqXHR, textStatus, errorThrown) {

};

/**
 * ツイート取得
 */
Mwitter.prototype.getTweetView = function(tid, options) {
	options = options || {};
	return ajax($.extend(options, {
		method: 'GET',
		url: '?controller=tweet&action=tweet&tid='+tid,
		dataType: 'text',
	}));
};


/**
 * Tweet 取得
 */
Mwitter.prototype.getTweet = function(tid, options) {
	options = options || {};
	return ajax($.extend(options, {
		method: 'GET',
		url: '?controller=api/tweet&action=tweet&tid='+tid,
		dataType: 'json',
	}));
};

/**
 * Tweet する
 */
Mwitter.prototype.postTweet = function(data, options) {
	options = options || {};
	return ajax($.extend(options, {
		method: 'POST',
		url: '?controller=api/tweet&action=tweet',
		contentType: 'application/json; charset=utf-8',
		dataType: 'json',
		data: JSON.stringify(data),
	}));
};







/**
 * ajax
 */
function ajax(options) {
	var debug = false;
	var opts = $.extend({},options);
	opts.success = function(data, textStatus, jqXHR) {
		if (debug) {
			console.log('AJAX: success '+textStatus+': '+data);
		}
		if (options.success) {
			options.success(data, textStatus, jqXHR);
		}
	}
	opts.error = function(jqXHR, textStatus, errorThrown) {
		if (debug){
			console.log('AJAX: error '+textStatus+': '+errorThrown);
		}
		if (options.error) {
			options.error(jqXHR, textStatus, errorThrown);
		}
	}
	return $.ajax(opts);
}


// the instance
var mw = new Mwitter();