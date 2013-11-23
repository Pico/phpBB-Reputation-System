/**
*
* @package Reputation System
* @copyright (c) 2013 Pico88
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

$(document).ready(function() {
	var arr = [8790, 8792, 8799];
	$.each(arr, function() {
		$("#p" + this).addClass( "highlight" );
	});
});

$('body').click(function(){
	if (!requestSent)
	{
		$("#reputation-popup").fadeOut('fast');
	}
});

$('#reputation-popup').click(function(e){
	e.stopPropagation();
});

$('#rate-user').click(function(event){
	event.stopPropagation()
	event.preventDefault();

	show_popup(this.href, event, 'user');
});

$('.rate-good-icon a').click(function(event){
	event.stopPropagation()
	event.preventDefault();

	show_popup(this.href, event, 'post');
});

$('.rate-bad-icon a').click(function(event){
	event.stopPropagation()
	event.preventDefault();

	show_popup(this.href, event, 'post');
});

$('.post-reputation a').click(function(event){
	event.stopPropagation()
	event.preventDefault();

	show_popup(this.href, event, 'details');
});

$('.user-reputation a').click(function(event){
	event.stopPropagation()
	event.preventDefault();

	show_popup(this.href, event, 'details');
});

$('#reputation-popup').on("click", '.button1', function(event){
	event.stopPropagation()
	event.preventDefault();

	submit_action($('#reputation-popup > form').attr('action'), 'post');
});

$('#reputation-popup').on("click", '.button2', function(event){
	event.stopPropagation()
	event.preventDefault();

	$('#reputation-popup').fadeOut('fast').queue(function () {
		$(this).empty();
		$(this).dequeue();
	});
});

$('#reputation-popup').on("click", '.footer-popup a', function(event){
	event.stopPropagation()
	event.preventDefault();

	sort_order_by(this.href)
});

/**
* Show the repuation popup with proper data
*/
function show_popup(href, event, mode)
{
	if (!requestSent)
	{
		requestSent = true;

		$.ajax({
			url: href,
			dataType: 'html',
			beforeSend: function() {
				$('#reputation-popup').hide().empty().removeClass('small-popup normal-popup');
			},
			success: function(data) {
				// Fix - do not display the empty popup when comment and reputation power are disabled
				if (data.substr(0,1) != '{')
				{
					$('#reputation-popup').append(data).fadeIn('fast');
				}

				switch(mode)
				{
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

				if (data.substr(0,1) == '{')
				{
					// It's JSON! Probably an error. Lets clean the reputation popup and show the error there
					response(jQuery.parseJSON(data), mode);
				}
			},
			complete: function() {
				setTimeout('request_sent()', 750);
			}
		});
	}
}

/**
* Function which allow to sent request after popup time out
*/
function request_sent()
{
	requestSent = false;
}

/**
* Submit reputation action
*/
function submit_action(href, mode)
{
	switch(mode)
	{
		case 'post':
		case 'user':
			data = $('#reputation-popup form').serialize();
		break;
	}

	$.ajax({
		url: href,
		data: data,
		dataType: 'json',
		type: 'POST',
		success: function(r) {
			response(r, mode);
		}
	});
}

/** 
* Reputation response
*/
function response(data, mode)
{
	if (data.error_msg)
	{
		// If there is an error, show it
		$('#reputation-popup').empty().append('<div class="error">' + data.error_msg + '</div>').fadeIn();
	}
	else if (data.comment_error)
	{
		// If there is a comment error, show it
		$('.error').detach();
		$('.comment').append('<dl class="error"><span>' + data.comment_error + '</span></dl>');
	}
	else
	{
		// Otherwise modify the board outlook
		switch (mode)
		{
			case 'post':
				var post_id = data.post_id;
				var poster_id = data.poster_id;

				$('#reputation-popup').empty().append('<div class="error">' + data.success_msg + '</div>').delay(800).fadeOut('fast').queue(function() {
					$(this).empty();
					$('#profile' + poster_id + ' a').html(data.user_reputation);
					$('#p' + post_id + ' .post-reputation a').text(data.post_reputation);
					$('#p' + post_id + ' .post-reputation').removeClass('neutral negative positive').addClass(data.reputation_class);
					$('#p' + post_id + ' .rate-good-icon').removeClass('rated_good rated_bad').addClass(data.reputation_vote);
					$('#p' + post_id + ' .rate-bad-icon').removeClass('rated_good rated_bad').addClass(data.reputation_vote);
					$(this).dequeue();
				});
			break;
			case 'user':
				$('#reputation-popup').empty().append('<div class="error">' + data.success_msg + '</div>').delay(800).fadeOut('fast').queue(function() {
					$(this).empty();
					$('#user-reputation').html(data.user_reputation);
					$('#rate-user').html(data.user_reputation);
					$(this).dequeue();
				});
			break;
			case 'delete':
				switch (c)
				{
					case 'post':
						var post_id = a.post_id;
						var poster_id = a.poster_id;
						var rep_id = a.rep_id;

						$('#r' + rep_id).hide('fast', function() {
							$('#r' + rep_id).detach();
							if ($('.reputation-list').length == 0)
							{
								$('#reputation-popup').fadeOut('fast').empty();
							}
						});
						$('#profile' + poster_id + ' a').html(a.user_reputation);
						$('#profile' + poster_id + ' .reputation-rank').html(a.reputation_rank);
						$('#p' + post_id + ' .reputation a').text(a.post_reputation);
						$('#p' + post_id + ' .reputation').removeClass('zero negative positive').addClass(a.reputation_class);

						if (a.own_vote)
						{
							$('#p' + post_id + ' .post-reputation').removeClass('rated_good rated_bad');
						}
					break;
					case 'user':
						location.reload();
					break;
				}
			break;
			case 'clear':
				switch(c)
				{
					case 'post':
						var post_id = a.post_id;
						var poster_id = a.poster_id;

						$('.reputation-list').slideUp(function() {
							$('#reputation-popup').fadeOut('fast').empty();
						});
						$('#profile' + poster_id + ' .user-reputation a').html(a.user_reputation);
						$('#profile' + poster_id + ' .reputation-rank').html(a.reputation_rank);
						$('#p' + post_id + ' .reputation a').text(a.post_reputation);
						$('#p' + post_id + ' .reputation').removeClass('zero negative positive').addClass(a.reputation_class);
						$('#p' + post_id + ' .post-reputation').removeClass('rated_good rated_bad');
						$('#p' + post_id).removeClass('highlight hidden');
						$('#p' + post_id + ' #hideshow').detach();
					break;
					case 'user':
						if (d == 'topic')
						{
							var post_ids = a.post_ids;
							var poster_id = a.poster_id;

							$('.reputation-list').slideUp(function() {
								$('#reputation-popup').fadeOut('fast').empty();
							});
							$('#profile' + poster_id + ' .user-reputation a').html(a.user_reputation);
							$('#profile' + poster_id + ' .reputation-rank').html(a.reputation_rank);

							$.each(post_ids, function() { 
								$('#p' + this + ' .reputation a').text(a.post_reputation);
								$('#p' + this + ' .reputation').removeClass('zero negative positive').addClass(a.reputation_class);
								$('#p' + this + ' .post-reputation').removeClass('rated_good rated_bad');
								$('#p' + this).removeClass('highlight hidden');
								$('#p' + this + ' #hideshow').detach();
							});
						}
						else if (d == 'detail')
						{
							location.reload();
						}
					break;
				}
			break;
		}
	}
}

/**
* Sort reputations
*/
function sort_order_by(href)
{
	$.ajax({
		url: href,
		dataType: 'html',
		success: function(s) {
			$('#reputation-popup').empty().append(s);
		}
	});
}