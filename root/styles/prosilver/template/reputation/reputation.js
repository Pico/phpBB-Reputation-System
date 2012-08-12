$(document).ready(function()
{
	$("a.repo-link").click(function(e){
		e.stopPropagation();
		e.preventDefault();
		//Do not vote again if you voted
		if (($(this).parents('.post').hasClass('rated_good') || $(this).parents('.post').hasClass('rated_bad') || $(this).parents('.post').hasClass('own')) && $(this).parents('.reputation').length == 0 && $(this).parents('.postprofile').length == 0){return false;}
		show_repo_popup(this.href, e.pageX, e.pageY);
		return false;
	});

	$("body").click(function(){
		$("#repo-popup").fadeOut();
	});

	$("#repo-popup").click(function(e){
		e.stopPropagation();
	});

	$(".show_hide_post").click(function(e){
		$(this).parents('.post').toggleClass('too_low_rating');
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

	$('#repo-popup').load($url,function(response, status, xhr){

		$('#repo-popup').css('top',clickedy+15+'px');
		$('#repo-popup').css('left',targetleft+'px');
		if (response.substr(0,1) == '{') {
			//It's JSON. Probably an error. Let's clean the DIV and show the error there.
			response = jQuery.parseJSON(response);
			update_points_or_show_error(response);
			return true;
		}
		$('#repo-popup').show();
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
	$.ajax({
		url: $('#repo-popup > form').attr('action'),
		data: $('#repo-popup > form').serializeObject(),
		dataType: 'json',
		type:   'POST',
		success: function(json_reply) {
			update_points_or_show_error(json_reply);
		}
	});
}

function update_points_or_show_error(json_reply)
{
	/** @namespace json_reply.post_id */
	/** @namespace json_reply.error_msg */
	/** @namespace json_reply.new_post_rating_class */
	/** @namespace json_reply.new_post_rating */

	if (json_reply.error_msg){
		//If there is an error, show it
		$('#repo-popup').empty();
		$('#repo-popup').append('<div class="error">' + json_reply.error_msg + '</div>');
		$('#repo-popup').show();
	} else {
		$('#repo-popup').hide();
		var post_id = json_reply.post_id;
		//No error? Then it's rating info. Let's update it
		$('#p'+post_id+' .reputation a.repo-link').text(json_reply.new_post_rating);
		$('#p'+post_id+' .user-reputation a.repo-link').text(json_reply.new_user_reputation);
		$('#p'+post_id+' .reputation').removeClass('zero negative positive');

		$('#p'+post_id+' .reputation').addClass(json_reply.new_post_rating_class);

		$('#p'+post_id).removeClass('rated_good rated_bad');
		var fadeout = '#p'+post_id + ' ' + json_reply.what_to_fadeout;
		$(fadeout).fadeOut('slow',function(){$('#p'+post_id).addClass(json_reply.new_post_class);});

	}
}

