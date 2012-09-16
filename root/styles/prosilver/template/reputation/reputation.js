$(document).ready(function()
{
	$("a.repo-link").click(function(e){
		e.stopPropagation();
		e.preventDefault();
		//Do not vote again if you voted
		if (($(this).parents('.profile-icons').hasClass('rated_good') || $(this).parents('.profile-icons').hasClass('rated_bad') || $(this).parents('.post').hasClass('own')) && $(this).parents('.reputation').length == 0 && $(this).parents('.postprofile').length == 0)
		{
			return false;
		}
		show_repo_popup(this.href+"&ajax=1", e.pageX, e.pageY);
		return false;
	});

	$("body").click(function(){
		$("#repo-popup").fadeOut('fast');
	});

	$("#repo-popup").click(function(e){
		e.stopPropagation();
	});

	$(".show_hide_post").click(function(e){
		$(this).parents('.post').toggleClass('hidden');
		e.preventDefault();
	});
});

function show_repo_popup($url, clickedx, clickedy)
{
	$('#repo-popup').empty();

	//Center popup relative to clicked coordinate
	targetleft = clickedx - $('#repo-popup').width() / 2;
	//Popup can not be too close or behind the right border of the screen
	targetleft = Math.min (targetleft, $(document).width() - 20 - $('#repo-popup').width());
	targetleft = Math.max (targetleft, 20);

	$('#repo-popup').load($url, function(response, status, xhr){

		$('#repo-popup').css('top', clickedy+15+'px');
		$('#repo-popup').css('left', targetleft+'px');
		if (response.substr(0,1) == '{') {
			//It's JSON. Probably an error. Let's clean the DIV and show the error there.
			response = jQuery.parseJSON(response);
			update_points_or_show_error(response);
			return true;
		}
		$('#repo-popup').fadeIn();
	});
}

//Function for converting form into JSON
$.fn.serializeObject = function()
{
	var o = {};
	var a = this.serializeArray();
	$.each(a, function() {
		if (o[this.name] !== undefined) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push(this.value || '');
		} else {
			o[this.name] = this.value || '';
		}
	});
	return o;
};

function submit_vote()
{
	if (!$.trim($('#comment').val()) & commentreq) 
	{
		$('.error').detach();
		$('.comment').append('<dl class="error"><span>' + nocomment + '</span></dl>');
	}
	else if ($('#comment').val().length > toolongcomment)
	{
		$('.error').detach();
		$('.comment').append('<dl class="error"><span>' + commentlen + ' ' + $('#comment').val().length + '.</span></dl>');
	}
	else
	{
		$.ajax({
			url: $('#repo-popup > form').attr('action'),
			data: $('#repo-popup > form').serializeObject(),
			dataType: 'json',
			type: 'POST',
			success: function(json_reply) {
				update_points_or_show_error(json_reply);
			}
		});
	}
}

function update_points_or_show_error(json_reply)
{
	if (json_reply.error_msg)
	{
		//If there is an error, show it
		$('#repo-popup').empty();
		$('#repo-popup').append('<div class="error">' + json_reply.error_msg + '</div>');
		$('#repo-popup').fadeIn();
	}
	else if (json_reply.post_id)
	{
		var post_id = json_reply.post_id;
		var poster_id = json_reply.poster_id;
		var fadeout = '#p'+post_id+' ' + json_reply.what_to_fadeout;

		$('#repo-popup').fadeOut('fast');
		//No error? Then it's rating info. Let's update it
		$('#p'+post_id+' .reputation a.repo-link').text(json_reply.new_post_rating);
		$('#profile'+poster_id+' .user-reputation a.repo-link').html(json_reply.new_user_reputation);
		$('#profile'+poster_id+' .reputation-rank').html(json_reply.new_reputation_rank);
		$('#p'+post_id+' .reputation').removeClass('zero negative positive');
		$('#p'+post_id+' .reputation').addClass(json_reply.new_post_rating_class);
		$('#p'+post_id).removeClass('rated_good rated_bad');
		//Check if negative points are disabled. If yes, change behaviour.
		if (json_reply.check_vote)
		{
			$(fadeout).fadeOut(function(){
				$('#p'+post_id+' .post-reputation').addClass(json_reply.new_post_class);
			});
		}
		else
		{
			$('#p'+post_id+' .post-reputation').addClass(json_reply.new_post_class);
		}
	}
	else if (json_reply.user_id)
	{
		$('#repo-popup').fadeOut('fast');
		$('.user-reputation').html(json_reply.user_reputation);
	}
}