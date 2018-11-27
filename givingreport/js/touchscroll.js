// JavaScript Document

  $(document).ready(function () {
    $("#makeMeScrollable").smoothDivScroll({
      touchScrolling: true, 
			mousewheelScrolling: "horizontal",
			hotSpotScrolling: false,
			autoScrollingDirection: "backAndForth"
		      //more settings
    });
  });
