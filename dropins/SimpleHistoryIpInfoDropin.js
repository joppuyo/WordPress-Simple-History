
(function($) {

	var $logItems = $(".SimpleHistoryLogitems");
	var $popup = $(".SimpleHistoryIpInfoDropin__popup");
	var $popupContent = $popup.find(".SimpleHistoryIpInfoDropin__popupContent");

	var templateLoading = _.template( $("#tmpl-simple-history-ipinfodropin-popup-loading").text() );
	var templateLoaded = _.template( $("#tmpl-simple-history-ipinfodropin-popup-loaded").text() );

	// Click on link with IP-number
	$logItems.on("click", ".SimpleHistoryLogitem__anonUserWithIp__theIp", function(e) {

		var $elm = $(this);
		var ipAddress = $elm.closest(".SimpleHistoryLogitem").data("ipAddress");

		if (! ipAddress) {
			return;
		}

		showPopup($elm);

		return lookupIpAddress(ipAddress);

	});

	// Close popup
	$popup.on("click", ".SimpleHistoryIpInfoDropin__popupCloseButton", hidePopup);
	$(window).on("click", maybeHidePopup);

	// Position and then show popup.
	// Content is not added yet
	function showPopup($elm) {

		var offset = $elm.offset();

		$popup.css({
			//top: offset.top + $elm.outerHeight(),
			top: offset.top,
			left: offset.left
		});

		$popupContent.html(templateLoading());

		$popup.addClass("is-visible");

	}

	function hidePopup(e) {

		$popup.removeClass("is-visible");

	}

	function maybeHidePopup(e) {

		var $target = (e.target);

		// Don't hide if click inside popup
		if ($.contains($popup.get(0), $target) ) {
			return true;
		}

		// Else it should be ok to hide
		hidePopup();

	}

	// Init request to lookup address
	function lookupIpAddress(ipAddress) {

		$.get("http://ipinfo.io/" + ipAddress, onIpAddressLookupkResponse, "jsonp");

		return false;

	}

	// Functin called when ip adress lookup succeeded
	function onIpAddressLookupkResponse(d) {

		console.log("got data", d);
		
		$popupContent.html(templateLoaded(d));

	}

})(jQuery);
