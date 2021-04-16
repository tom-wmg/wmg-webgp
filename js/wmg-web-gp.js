(function($)
{
	var getCookie = function(cname)
	{
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) 
		{
			var c = ca[i];
			while (c.charAt(0) == ' ')
			{
				c = c.substring(1);
			}
	
			if (c.indexOf(name) == 0) 
			{
				return c.substring(name.length, c.length);
			}
		}
	
		return "";
	}
	
	var setCookie = function(cname, cvalue, exdays) 
	{
		var d = new Date();
		d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
		var expires = "expires=" + d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	
/*
	$('a.submit').click(function()
	{
		$('.popup-overlay').fadeOut();
		//sets the coookie to five minutes if the popup is saved (whole numbers = days)
		setCookie( 'webgpPopupCookie', 'saved', 14);
	});
*/



	//toggleBannerVisibility(false, undefined, this);

	//check if web gp button exists in DOM
	$.initialize("#webgpBtnToggle", function()
	{
		var button = $(this);
		
		//check to see if a cookie was previously set as closed, if not then display the popup
		//if remote banner is open then close it
		if (button.hasClass('webgpBtnToggle-close')) button.trigger('click');
		
		//show web gp banner
		//body.banner();
	});
	

    $(document).ready(function()
    {
		var body = $('body');

		//check to see if a cookie was previously set as closed, if not then display the popup
		if (getCookie('webgpPopupCookie') != 'closed' )
		{
			//$.log('cookie found - prevent banner opening');
			body.find('.wgp-overlay').removeClass('hide');
		}

		//close local banner and store cookie
		body.find('.wgp .wgp-btn-close').click(function(e)
		{
			e.preventDefault();
			//$.log('setting cookie');
			setCookie('webgpPopupCookie', 'closed', 1);

			//hide local banner
			//$.log('hiding banner');
			body.find('.wgp-overlay').addClass('hide');
		});

    });

	
})(jQuery);




