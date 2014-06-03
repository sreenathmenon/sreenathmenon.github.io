function switchImage(imageHref, currentBigImage, currentThumbSrc, largeImage) {
	var theBigImage = $('.main-image img');
	if (imageHref != currentBigImage) {
		theBigImage.hide(0, function(){
			theBigImage.attr('src', imageHref).show(0);
		});

	}

	$(".view-larger a").attr('href', largeImage);

};

$(document).ready(function(){
	$(".side-nav.expand ul > li").click(function(){
		$(this).toggleClass("active");
	});

	$('.fancybox-media').fancybox({
		openEffect  : 'none',
		closeEffect : 'none',
		helpers : {
			media : {}
		}
	});

	$('.fancybox-iframe').fancybox({
		maxWidth	: 600,
		maxHeight	: 400,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none',
		type 		: 'ajax'
	});

	// Image Swap

	$('.thumbnails li a').click(function() {
		var currentBigImage = $('.main-image img').attr('src');
		var newBigImage = $(this).attr('href');
		var currentThumbSrc = $(this).attr('rel');
		var largeImage = $(this).attr('data-ext');
		switchImage(newBigImage, currentBigImage, currentThumbSrc, largeImage);
		return false;
	});

	$('.shipping-billing').click(function(){
		$('.shipping-address-fields').show();
		$(this).toggleClass("checked");
	});

	$('.payment-options input').click(function(){
		$(this).parent().next().toggleClass("show");
		$(this).parent().parent().find("div").hide();
		$(".show").show();
	});
});