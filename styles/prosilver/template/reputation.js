/**
* @package Reputation System
* @copyright (c) 2014 Pico
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/
var reputation = {};
reputation.requestSent = false;

(function ($) {  // Avoid conflicts with other libraries

/*$(document).ready(function() {
	//TO-DO Highlighting and hiding post
	$('.positive').each(function() {
		$(this).parents('.post').addClass("hidden");
	});
});*/

// Close reputation popup
$('body').click(function() {
	if (!reputation.requestSent) {
		$("#reputation-popup").fadeOut('fast');
	}
});

// Stop propagation
$('#reputation-popup').click(function(e) {
	e.stopPropagation();
});

// Rating user
$('#rate-user').click(function(event) {
	reputation.show_popup(this.href, event, 'user', $(this).attr('data-referer'));
});

// Rating post - positive
$('a.rate-good-icon').click(function(event) {
	reputation.show_popup(this.href, event, 'post', $(this).attr('data-referer'));
});

// Rating post - negative
$('a.rate-bad-icon').click(function(event) {
	reputation.show_popup(this.href, event, 'post', $(this).attr('data-referer'));
});

// Display post reputation details
$('a.post-reputation').click(function(event) {
	reputation.show_popup(this.href, event, 'details', $(this).attr('data-referer'));
});

// Display user reputation details
$('.user-reputation a').click(function(event) {
	reputation.show_popup(this.href, event, 'details', $(this).attr('data-referer'));
});

// Save vote
$('#reputation-popup').on("click", '.button1', function(event) {
	event.stopPropagation();
	event.preventDefault();

	reputation.submit_action($('#reputation-popup > form').attr('action'), $('#reputation-popup > form').attr('data-rate'));
});

// Cancel rating
$('#reputation-popup').on("click", '.button2', function(event) {
	event.stopPropagation();
	event.preventDefault();

	$('#reputation-popup').fadeOut('fast').queue(function() {
		$(this).empty();
		$(this).dequeue();
	});
});

// Sort reputation by
$('#reputation-popup').on("click", 'a.sort_order', function(event) {
	event.stopPropagation();
	event.preventDefault();

	reputation.sort_order_by(this.href, $('.footer-popup').attr('data-referer'));
});

// Delete reputation
$('#reputation-popup').on("click", '.reputation-delete', function(event) {
	event.stopPropagation();
	event.preventDefault();

	var confirmation = $('a.reputation-delete').attr('data-lang-confirm');

	if (confirm(confirmation)) {
		reputation.submit_action(this.href, 'delete');
	}
});

// Clear reputation
$('#reputation-popup').on("click", '.clear-reputation', function(event) {
	event.stopPropagation();
	event.preventDefault();

	var confirmation = $('a.clear-reputation').attr('data-lang-confirm');

	if (confirm(confirmation)) {
		reputation.submit_action(this.href, 'clear');
	}
});

/**
* Show the reputation popup with proper data
*/
reputation.show_popup = function(href, event, mode, ref) {
	event.stopPropagation();
	event.preventDefault();

	if (!reputation.requestSent) {
		reputation.requestSent = true;

		$.ajax({
			url: href,
			data: ref,
			dataType: 'html',
			beforeSend: function() {
				$('#reputation-popup').hide().empty().removeClass('small-popup normal-popup');
			},
			success: function(data) {
				// Fix - do not display the empty popup when comment and reputation power are disabled
				if (data.substr(0,1) != '{') {
					$('#reputation-popup').append(data).fadeIn('fast');
				}

				switch(mode) {
					case 'details':
						$('#reputation-popup').addClass('normal-popup');
						targetleft = ($(window).width() - $('#reputation-popup').outerWidth()) / 2;
						targettop = ($(window).height() - $('#reputation-popup').outerHeight()) / 2;
					break;

					default:
						$('#reputation-popup').addClass('small-popup');
						// Center popup relative to clicked coordinate
						targetleft = event.pageX - $('#reputation-popup').outerWidth() / 2;
						// Popup can not be too close or behind the right border of the screen
						targetleft = Math.min (targetleft, $(document).width() - 20 - $('#reputation-popup').outerWidth());
						targetleft = Math.max (targetleft, 20);
						targettop = event.pageY + 10;
					break;
				}

				$('#reputation-popup').css({'top': targettop + 'px', 'left': targetleft + 'px'});

				// It's JSON! Probably an error. Lets clean the reputation popup and show the error there
				if (data.substr(0,1) == '{') {
					reputation.response(jQuery.parseJSON(data), mode);
				}
			},
			complete: function() {
				reputation.requestSent = false;
			}
		});
	}
}

/**
* Submit reputation action
*/
reputation.submit_action = function(href, mode) {
	switch(mode) {
		case 'post':
		case 'user':
			data = $('#reputation-popup form').serialize();
		break;

		default:
			data = '';
		break;
	}

	$.ajax({
		url: href,
		data: data,
		dataType: 'json',
		type: 'POST',
		success: function(r) {
			reputation.response(r, mode);
		}
	});
}

/** 
* Reputation response
*/
reputation.response = function(data, mode) {
	// If there is an error, show it
	if (data.error_msg) {
		$('#reputation-popup').empty().append('<div class="error">' + data.error_msg + '</div>').fadeIn();
	}
	// If there is a comment error, show it
	else if (data.comment_error) {
		$('.error').detach();
		$('.comment').append('<dl class="error"><span>' + data.comment_error + '</span></dl>');
	}
	// Otherwise modify the board outlook
	else {
		switch (mode) {
			case 'post':
				var post_id = data.post_id;
				var poster_id = data.poster_id;

				$('#reputation-popup').empty().append('<div class="error">' + data.success_msg + '</div>').delay(500).fadeOut('fast').queue(function() {
					$('#profile-' + poster_id + ' span').text(data.user_reputation);
					$('#p' + post_id + ' .post-reputation span').text(data.post_reputation);
					$('#p' + post_id + ' .post-reputation').removeClass('neutral negative positive').addClass(data.reputation_class);
					$('#p' + post_id + ' .rate-good-icon').removeClass('rated_good rated_bad').addClass(data.reputation_vote);
					$('#p' + post_id + ' .rate-bad-icon').removeClass('rated_good rated_bad').addClass(data.reputation_vote);
					$(this).empty().dequeue();
				});
			break;

			case 'user':
				$('#reputation-popup').empty().append('<div class="error">' + data.success_msg + '</div>').delay(500).fadeOut('fast').queue(function() {
					$('#user-reputation').html(data.user_reputation);
					$(this).empty().dequeue();
				});
			break;

			case 'delete':
				var post_id = data.post_id;
				var poster_id = data.poster_id;
				var rid = data.rid;

				$('#r' + rid).hide('fast', function() {
					$('#r' + rid).detach();
					if ($('.reputation-list').length == 0) {
						$('#reputation-popup').fadeOut('fast').empty();
					}
				});
				$('#profile-' + poster_id + ' span').text(data.user_reputation);
				$('#p' + post_id + ' .post-reputation span').text(data.post_reputation);
				$('#p' + post_id + ' .post-reputation').removeClass('neutral negative positive').addClass(data.reputation_class);

				if (data.own_vote) {
					$('#p' + post_id + ' .rate-good-icon').removeClass('rated_good rated_bad');
					$('#p' + post_id + ' .rate-bad-icon').removeClass('rated_good rated_bad');
				}
			break;

			case 'clear':
				if (data.clear_post) {
					var post_id = data.post_id;
					var poster_id = data.poster_id;

					$('.reputation-list').slideUp(function() {
						$('#reputation-popup').fadeOut('fast').empty().delay(300).queue(function() {
							$('#profile-' + poster_id + ' span').text(data.user_reputation);
							$('#p' + post_id + ' .post-reputation span').text(data.post_reputation);
							$('#p' + post_id + ' .post-reputation').removeClass('neutral negative positive').addClass(data.reputation_class);
							$('#p' + post_id + ' .rate-good-icon').removeClass('rated_good rated_bad');
							$('#p' + post_id + ' .rate-bad-icon').removeClass('rated_good rated_bad');
						}).dequeue();
					});
					
				}
				else if (data.clear_user) {
					var post_ids = data.post_ids;
					var poster_id = data.poster_id;

					$('.reputation-list').slideUp(function() {
						$('#reputation-popup').fadeOut('fast').empty().delay(300).queue(function() {
							$('#profile-' + poster_id + ' span').text(data.user_reputation);

							$.each(post_ids, function(i, post_id) { 
								$('#p' + post_id + ' .post-reputation span').text(data.post_reputation);
								$('#p' + post_id + ' .post-reputation').removeClass('neutral negative positive').addClass(data.reputation_class);
								$('#p' + post_id + ' .rate-good-icon').removeClass('rated_good rated_bad');
								$('#p' + post_id + ' .rate-bad-icon').removeClass('rated_good rated_bad');
							});
						}).dequeue();
					});
				}
			break;
		}
	}
}

/**
* Sort reputations
*/
reputation.sort_order_by = function(href, ref) {
	$.ajax({
		url: href,
		data: ref,
		dataType: 'html',
		success: function(s) {
			$('#reputation-popup').empty().append(s);
		}
	});
}

})(jQuery); // Avoid conflicts with other libraries
