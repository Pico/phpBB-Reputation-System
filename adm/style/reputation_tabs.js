/**
* Show/hide option panels
*/
function swap_options(cat)
{
	active_option = active_cat;

	var old_tab = document.getElementById('tab_' + active_option);
	var new_tab = document.getElementById('tab_' + cat);

	init_checked();

	// no need to set anything if we are clicking on the same tab again
	if (new_tab == old_tab)
	{
		return;
	}

	// set active tab
	old_tab.className = old_tab.className.replace(/\ activetab/g, '');
	new_tab.className = new_tab.className + ' activetab';

	// save active tab to cookie
	document.cookie = "rs_config=" + cat + "; max-age=" + 120;

	if (cat == active_option)
	{
		return;
	}

	document.getElementById('options' + active_option).style.display = 'none';
	document.getElementById('options' + cat).style.display = '';

	active_cat = cat;
}

function init_check(option)
{
	var input = document.getElementById(option);
	var tab = document.getElementById('tab_' + option);

	if (input.checked == 1)
	{
		tab.className = 'permissions-preset-yes activetab';
	}
	else
	{
		tab.className = 'permissions-preset-no activetab';
	}
}

function init_checked()
{
	for (var i = 0, option; option = options[i]; i++)
	{
		var input = document.getElementById(option);
		var tab = document.getElementById('tab_' + option);

		if (input.checked == 1)
		{
			tab.className = 'permissions-preset-yes';
		}
		else
		{
			tab.className = 'permissions-preset-no';
		}
	}
}

function readCookie(name)
{
	var nameEQ = name + '=';
	var ca = document.cookie.split(';');

	for (var i = 0; i < ca.length; i++)
	{
		var c = ca[i];

		while (c.charAt(0) == ' ')
		{
			c = c.substring(1, c.length);
		}

		if (c.indexOf(nameEQ) == 0)
		{
			return c.substring(nameEQ.length, c.length);
		}
	}

	return null;
}

function load_last_active_tab()
{
	var cookie = readCookie('rs_config');
	var title = cookie ? cookie : 'main';
	swap_options(title);
}