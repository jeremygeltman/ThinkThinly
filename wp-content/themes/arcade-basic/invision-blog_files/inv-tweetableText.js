function invTweetPopup( url, winW, winH ){
	var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left,
		dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top,
		left, top, newWindow;

	width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
	height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
	left = ((width / 2) - (winW / 2)) + dualScreenLeft;
	top = ((height / 2) - (winH / 2)) + dualScreenTop;
	newWindow = window.open( url, 'invTweetable', 'scrollbars=yes, width='+winW+', height='+winH+', top=' + top + ', left=' + left);
	if (window.focus){ newWindow.focus(); }
}


jQuery(function() {

	jQuery('.inv-tweet, .inv-tweet-sa').on('click', function(e){
		e.preventDefault();
		e.stopPropagation();

		var href = jQuery(this).attr('href');
		
		invTweetPopup( href, 800, 400 );
	});
});