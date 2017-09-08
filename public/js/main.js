$( document ).ready(function() {
    var btn = $(".navbar-toggle");

	btn.on("click", function(){

	$(".dropdown").slideToggle(300, function(){
		if($(this).css("display") === "none")
			$(this).removeAttr("style");
	});
});

	var alert = $('.alert')

	function hide(){
			if(alert.hasClass('fadeInDown')){
			alert.removeClass('fadeInDown').addClass('fadeOutUp')
		}
	}

	setTimeout(hide , 7000)

});













