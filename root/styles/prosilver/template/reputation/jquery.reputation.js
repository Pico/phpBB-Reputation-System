/**
*
* @package Reputation System
* @author Pico88 (https://github.com/Pico88)
* @copyright (c) 2012
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

$(document).ready(function() {
	$("body").click(function(){
		$("#reputation-popup").fadeOut('fast');
	});

	$("#reputation-popup").click(function(e){
		e.stopPropagation();
	});
});

var jRS = {
	showhide: function(a) {
		$(a).parents('.post').toggleClass('hidden');
	},
	positive: function(a, b, c) {
		show_popup('positive', a, b, c);
	},
	negative: function(a, b, c) {
		show_popup('negative', a, b, c);
	},
	postdetails: function(a) {
		show_popup('postdetails', a);
	},
	userdetails: function(a, b) {
		show_popup('userdetails', a, b);
	},
	userrating: function(a, b) {
		show_popup('rateuser', a, b);
	},
	ratepost: function(id) {
		submit_action('post', id);
	},
	rateuser: function(id) {
		submit_action('user', id);
	},
	del: function(id, mode) {
		if(confirm(rsdelete))
		{
			submit_action('delete', id, mode);
		}
	},
	clear: function(id, mode, page) {
		switch(mode)
		{
			case 'post':
				confirm_clear = rsclearpost;
			break;
			case 'user':
				confirm_clear = rsclearuser;
			break;
		}
		if(confirm(confirm_clear))
		{
			submit_action('clear', id, mode, page);
		}
	},
	catchup: function() {
		submit_action('catchup');
	},
}

function show_popup(a, b, c, d)
{
	if (($(d).parents('.post-reputation').hasClass('rated_good') || $(d).parents('.post-reputation').hasClass('rated_bad')) && $(d).parents('.reputation').length == 0)
	{
		return false;
	}

	switch(a)
	{
		case 'positive':
			data = 'mode=ratepost&rpmode=positive&p=' + b;
			mode = 'post';
		break;
		case 'negative':
			data = 'mode=ratepost&rpmode=negative&p=' + b;
			mode = 'post';
		break;
		case 'postdetails':
			data = 'mode=postdetails&p=' + b;
		break;
		case 'userdetails':
			data = 'mode=userdetails&u=' + b;
		break;
		case 'rateuser':
			data = 'mode=rateuser&u=' + b;
			mode = 'user';
		break;
		case 'newpopup':
			data = 'mode=newpopup';
		break;
	}

	$.ajax({
		url: rsfile,
		data: data,
		dataType: 'html',
		beforeSend: function() {
			$('#reputation-popup').hide().empty().removeClass('small-popup normal-popup new-popup');
		},
		success: function(s) {
			$('#reputation-popup').append(s).fadeIn();

			switch(a)
			{
				case 'postdetails':
				case 'userdetails':
					$('#reputation-popup').addClass('normal-popup');
					targetleft = ($(window).width() - $('#reputation-popup').outerWidth()) / 2;
					targettop = ($(window).height() - $('#reputation-popup').outerHeight()) / 2;
				break;
				case 'newpopup':
					$('#reputation-popup').addClass('new-popup');
					targetleft = ($(window).width() - $('#reputation-popup').outerWidth()) / 2;
					targettop = ($(window).height() - $('#reputation-popup').outerHeight()) / 2;
				break;
				default:
					$('#reputation-popup').addClass('small-popup');
					// Center popup relative to clicked coordinate
					targetleft = c.pageX - $('#reputation-popup').outerWidth() / 2;
					// Popup can not be too close or behind the right border of the screen
					targetleft = Math.min (targetleft, $(document).width() - 20 - $('#reputation-popup').outerWidth());
					targetleft = Math.max (targetleft, 20);
					targettop = c.pageY + 10;
				break;
			}

			$('#reputation-popup').css({'top': targettop + 'px', 'left': targetleft + 'px'});

			if (s.substr(0,1) == '{')
			{
				// It's JSON. Probably an error. Let's clean the DIV and show the error there
				r = jQuery.parseJSON(s);
				response(r, mode);
			}
		}
	});
}

function submit_action(a, b, c, d)
{
	var submit = true;

	switch(a)
	{
		case 'post':
		case 'user':
			// Comment required
			if(commenton)
			{
				if(!$.trim($('#comment').val()) & commentreq) 
				{
					submit = false;
					$('.error').detach();
					$('.comment').append('<dl class="error"><span>' + nocomment + '</span></dl>');
				}
				// Comment too long
				else if(commenton & ($('#comment').val().length > toolongcomment) & (toolongcomment > 0))
				{
					submit = false;
					$('.error').detach();
					$('.comment').append('<dl class="error"><span>' + commentlen + ' ' + $('#comment').val().length + '.</span></dl>');
				}
			}
		break;
	}

	if(submit)
	{
		switch(a)
		{
			case 'post':
				data = 'mode=ratepost&p=' + b + '&' + $('#reputation-popup form').serialize();
			break;
			case 'user':
				data = 'mode=rateuser&u=' + b + '&' + $('#reputation-popup form').serialize();
			break;
			case 'delete':
				data = 'mode=delete&id=' + b + '&dm=' + c;
			break;
			case 'clear':
				switch(c)
				{
					case 'post':
						data = 'mode=clear&p=' + b + '&cm=' + c;
					break;
					case 'user':
						data = 'mode=clear&u=' + b + '&cm=' + c + '&cp=' + d;
					break;
				}
			break;
			case 'catchup':
				data = 'mode=catchup';
			break;
		}

		$.ajax({
			url: rsfile,
			data: data,
			dataType: 'json',
			type: 'POST',
			success: function(r) {
				response(r, a, c, d);
			}
		});
	}
}

function response(a, b, c, d)
{
	if(a.error_msg)
	{
		// If there is an error, show it
		$('#reputation-popup').empty().append('<div class="error">' + a.error_msg + '</div>').fadeIn();
	}
	else
	{
		switch (b)
		{
			case 'post':
				var post_id = a.post_id;
				var poster_id = a.poster_id;

				$('#reputation-popup').fadeOut('fast').empty();
				$('#profile' + poster_id + ' .user-reputation a').html(a.user_reputation);
				$('#profile' + poster_id + ' .reputation-rank').html(a.reputation_rank);
				$('#p' + post_id + ' .reputation a').text(a.post_reputation);
				$('#p' + post_id + ' .reputation').removeClass('zero negative positive').addClass(a.reputation_class);
				$('#p' + post_id + ' .post-reputation').removeClass('rated_good rated_bad').addClass(a.reputation_vote);
				
				if(a.highlight)
				{
					$('#p' + post_id).removeClass('highlight hidden').addClass('highlight');
				}
				if(a.hidden)
				{
					$('#p' + post_id + ' #hideshow').detach();
				}
				if(a.hidepost)
				{
					$('#p' + post_id + ' #hideshow').detach();
					$('#p' + post_id + ' .postbody').before(a.hidemessage);
					$('#p' + post_id).removeClass('highlight hidden').addClass('hidden');
				}
			break;
			case 'user':
				$('#reputation-popup').fadeOut('fast').empty();
				$('.user-reputation').html(a.user_reputation);
				$('.reputation-rank').html(a.reputation_rank);
				$('.reputation').removeClass('zero negative positive').addClass(a.reputation_class);
				$('.rs-rank-title').text(a.rank_title);
				$('.empty').detach();
				$('#post-reputation-list').prepend(a.add);
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
						$('#profile' + poster_id + ' .user-reputation a').html(a.user_reputation);
						$('#profile' + poster_id + ' .reputation-rank').html(a.reputation_rank);
						$('#p' + post_id + ' .reputation a').text(a.post_reputation);
						$('#p' + post_id + ' .reputation').removeClass('zero negative positive').addClass(a.reputation_class);
						$('#p' + post_id + ' .post-reputation').removeClass('rated_good rated_bad');

						if(a.highlight)
						{
							$('#p' + post_id).removeClass('highlight');
						}
						if(a.hidden)
						{
							$('#p' + post_id + ' #hideshow').detach();
						}
						if(a.hidepost)
						{
							$('#p' + post_id + ' #hideshow').detach();
							$('#p' + post_id + ' .postbody').before(a.hidemessage);
							$('#p' + post_id).removeClass('highlight hidden').addClass('hidden');
						}
					break;
					case 'user':
						var rep_id = a.rep_id;

						$('.user-reputation').html(a.user_reputation);
						$('.reputation-rank').html(a.reputation_rank);
						$('.reputation').removeClass('zero negative positive').addClass(a.reputation_class);
						$('.rs-rank-title').text(a.rank_title);
						$('#r' + rep_id).hide(function() {
							$('#r' + rep_id).detach();
							if($('#post-reputation-list .bg1').length == 0 && $('#post-reputation-list .bg2').length == 0 )
							{
								$('#post-reputation-list').append(a.empty);
								$('#post-reputation-list .linklist').detach();
							}
						});
					break;
				}
			break;
			case 'clear':
				switch(c)
				{
					case 'post':
						var post_id = a.post_id;
						var poster_id = a.poster_id;

						$('.reputation-list').hide('slow', function() {
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
						if(d == 'topic')
						{
							var post_ids = a.post_ids;
							var poster_id = a.poster_id;

							$('.reputation-list').hide('slow', function() {
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
						else if(d == 'detail')
						{
							$('.user-reputation').html(a.user_reputation);
							$('.reputation-rank').html(a.reputation_rank);
							$('.reputation').removeClass('zero negative positive').addClass(a.reputation_class);
							$('.rs-rank-title').text(a.rank_title);
							$('.reputation-list').hide('slow', function() {
								$('#post-reputation-list').empty().append(a.empty);
								$('#post-reputation-list .linklist').detach();
							});
						}
					break;
				}
			break;
			case 'catchup':
				$('.new-repo').remove();
			break;
		}
	}
}

var sortby = {
	username: function(a, b, c) {
		sort_order_by(a, b, c, 'a');
	},
	time:  function(a, b, c) {
		sort_order_by(a, b, c, 'b');
	},
	points:  function(a, b, c) {
		sort_order_by(a, b, c, 'c');
	},
	action: function(a, b, c) {
		sort_order_by(a, b, c, 'd');
	},
	post: function(a, b, c) {
		sort_order_by(a, b, c, 'e');
	},
}

function sort_order_by(a, b, c, d)
{
	switch(a)
	{
		case 'post':
			data = 'mode=postdetails&p=' + b + '&sk=' + d + '&sd=' + c;
		break;
		case 'user':
			data = 'mode=userdetails&u=' + b + '&sk=' + d + '&sd=' + c;
		break;
	}

	$.ajax({
		url: rsfile,
		data: data,
		dataType: 'html',
		success: function(s) {
			$('#reputation-popup').empty().append(s);
		}
	});
}

function newpopup() {
	show_popup('newpopup');
}