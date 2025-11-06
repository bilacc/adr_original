$(document).ready(function () {
	var visina = $(window).height();
	if (visina < 400) {
		visina = 400;
	}

	if ($(window).width() >= 760) {
		$('.container').css("min-height", "" + (visina - 30) + "px");
	} else {
		$('.container').css("min-height", "" + (visina - 10) + "px");
	}

	$('.menu-toggle').click(function () {
		$('.menu').toggle();
	});

	$('.filter-toggle').click(function () {
		$('.filters').toggle();
	});

	$(".toggle").click(function () {
		if ($(this).parent().hasClass("collapsed")) {
			$(this).parent().removeClass("collapsed");
			$(this).removeClass("reversed");
		} else {
			$(this).parent().addClass("collapsed");
			$(this).addClass("reversed");
		};
	});

	$('.tabs4 > a').click(function () {
		if ($(this).hasClass('slc') == false) {
			var id = $(this).attr('id');
			$('.tabs4 > a').removeClass('slc');
			$('.tabs4-container').removeClass('slc');
			$(this).addClass('slc');
			$('#' + id + '_content').addClass('slc');
		}
	});


	$('.tab-section > a').click(function () {
		if ($(this).hasClass('active-section') == false) {
			var id = $(this).attr('id');
			$('.tab-section > a').removeClass('active-section');
			$('.section').removeClass('active-section');
			$(this).addClass('active-section');
			$('#' + id + '_container').addClass('active-section');
		}
	});


	$('.right-tabs > a').click(function () {
		if ($(this).hasClass('active-right') == false) {
			var id = $(this).attr('id');
			$('.right-tabs > a').removeClass('active-right');
			$('.right-tabs-container').removeClass('active-right');
			$(this).addClass('active-right');
			$('#' + id + '_container').addClass('active-right');
		}
	});

	$('.tab-section2 > a').click(function () {
		if ($(this).hasClass('active-section') == false) {
			var id = $(this).attr('id');
			$('.tab-section2 > a').removeClass('active-section');
			$('.section2').removeClass('active-section');
			$(this).addClass('active-section');
			$('#' + id + '_container').addClass('active-section');
		}
	});

	$('.tab-section3 > a').click(function () {
		if ($(this).hasClass('active-section') == false) {
			var id = $(this).attr('id');
			$('.tab-section3 > a').removeClass('active-section');
			$('.section3').removeClass('active-section');
			$(this).addClass('active-section');
			$('#' + id + '_container').addClass('active-section');
		}
	});

	$('.tabs-title > a').click(function () {
		if ($(this).hasClass('slc') == false) {
			var id = $(this).attr('id');
			$('.tabs-title > a').removeClass('slc');
			$('.editor-title').removeClass('slc');
			$(this).addClass('slc');
			$('#' + id + '_content').addClass('slc');
		}
	});
	$('.tabs-page > a').click(function () {
		if ($(this).hasClass('slc') == false) {
			var id = $(this).attr('id');
			$('.tabs-page > a').removeClass('slc');
			$('.editor-page').removeClass('slc');
			$(this).addClass('slc');
			$('#' + id + '_content').addClass('slc');
		}
	});
	$('.tabs-files > a').click(function () {
		if ($(this).hasClass('slc') == false) {
			var id = $(this).attr('id');
			$('.tabs-files > a').removeClass('slc');
			$('.tabs-files-content').removeClass('slc');
			$(this).addClass('slc');
			$('#' + id + '_content').addClass('slc');
		}
	});
	$('.tabs > a').click(function () {
		if ($(this).hasClass('slc') == false) {
			var id = $(this).attr('id');
			$('.tabs > a').removeClass('slc');
			$('.editor').removeClass('slc');
			$(this).addClass('slc');
			$('#' + id + '_content').addClass('slc');
		}
	});
	$('.tabs3 > a').click(function () {
		if ($(this).hasClass('slc') == false) {
			var id = $(this).attr('id');
			$('.tabs3 > a').removeClass('slc');
			$('.tabs3-container').removeClass('slc');
			$(this).addClass('slc');
			$('#' + id + '_content').addClass('slc');
		}
	});
	$('.tabs4 > a').click(function () {
		if ($(this).hasClass('slc') == false) {
			var id = $(this).attr('id');
			$('.tabs4 > a').removeClass('slc');
			$('.tabs4-container').removeClass('slc');
			$(this).addClass('slc');
			$('#' + id + '_content').addClass('slc');
		}
	});
	$('.tabs5 > a').click(function () {
		if ($(this).hasClass('slc') == false) {
			var id = $(this).attr('id');
			$('.tabs5 > a').removeClass('slc');
			$('.tabs5-container').removeClass('slc');
			$(this).addClass('slc');
			$('#' + id + '_content').addClass('slc');
		}
	});
	$('.tabs2 > a').click(function () {
		if ($(this).hasClass('slc') == false) {
			var id = $(this).attr('id');
			$('.tabs2 > a').removeClass('slc');
			$('.editor2').removeClass('slc');
			$(this).addClass('slc');
			$('#' + id + '_content').addClass('slc');
		}
	});
	$("table.list > tbody").sortable({
		placeholder: "ui-state-highlight",
		start: function (event, ui) {
			$("table.list > tbody").addClass('dragged');
			$("body").css("overflow", "hidden");
		},
		stop: function (event, ui) {
			$("table.list > tbody").removeClass('dragged');
			$("body").removeAttr("style");
			var data = $(this).sortable("toArray");
			sjx('save_order', data, $("#table").val());
		},
		cancel: ".no-sort"
	});

	$(".cat-list").sortable({
		placeholder: "ui-state-highlight",
		start: function (event, ui) {
			$(".cat-list").addClass('dragged');
			$("body").css("overflow", "hidden");
			$(this).children("ul.sort").addClass('disabled');
		},
		stop: function (event, ui) {
			$(".cat-list").removeClass('dragged');
			$("body").removeAttr("style");
			$(this).children("ul.sort").removeClass('disabled');
			var data = $(this).sortable("toArray");
			sjx('save_order', data, $("#table").val());
		},
		items: "> li",
		cancel: ".no-sort"
	});

	$(".sort").sortable({
		placeholder: "ui-state-highlight",
		start: function (event, ui) {
			$(".cat-list").addClass('dragged');
			$("body").css("overflow", "hidden");
			$(".cat-list").find("li").addClass('disabled');
			$(this).children("ul.sort").addClass('disabled');
			$(this).children("li").removeClass('disabled');
		},
		stop: function (event, ui) {
			$(".cat-list").removeClass('dragged');
			$("body").removeAttr("style");
			$(this).children("ul.sort").removeClass('disabled');
			$(".cat-list").find("li").removeClass('disabled');
			var data = $(this).sortable("toArray");
			sjx('save_order', data, $("#table").val());
		},
		items: "> li",
		cancel: ".no-sort"
	});

	$(".image-sort").sortable({
		placeholder: "ui-state-highlight",
		start: function (event, ui) {
			ui.placeholder.height(ui.item.height() - 6);
		},
		stop: function (event, ui) {
			var data = $(this).sortable("toArray");
			sjx('save_order', data, 'site_photos');
		},
		items: "> div.unos-slika",
		cancel: ".no-sort"
	});

	$(".file-sort").sortable({
		placeholder: "ui-state-highlight",
		start: function (event, ui) {
			ui.placeholder.height(ui.item.height() - 6);
		},
		stop: function (event, ui) {
			var data = $(this).sortable("toArray");
			sjx('save_order', data, $(this).find(".file_table").val());
		},
		items: "> div.unos-dokument",
		cancel: ".no-sort"
	});

	$("table.list > tbody").disableSelection();
	$(".cat-list").disableSelection();
});

$(window).resize(function () {
	var visina2 = $(window).height();
	if (visina2 < 400) {
		visina2 = 400;
	}
	if ($(window).width() >= 760) {
		$('.container').css("min-height", "" + (visina2 - 30) + "px");
		$('.menu').css('display', '');
		if ($(window).width() >= 960) {
			$('.filters').css('display', '');
		}
	} else {
		$('.container').css("min-height", "" + (visina2 - 10) + "px");
	}
});

$(window).scroll(function () {
	if ($(document).scrollTop() >= 75) {
		$('.save').addClass('scrolled');
	} else {
		$('.save').removeClass('scrolled');
	};
});

$(window).bind("load", function () {
	var max = -1;
	$(".unos-slika").each(function () {
		var h = $(this).height();
		max = h > max ? h : max;
	});
	$(".unos-slika").css("height", max);
});