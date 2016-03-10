var speed = 300;
//$.ajaxSetup({async: true});

$(function () {
    $(document.body).on('click', "a[rel='leanModal']", function (e) {
        $("div#overlay").fadeIn(speed);

        var id = e.target.id;
        $("div#orderModifyContent").load(link + "/" + id);
    });
});

// click anywhere hide modal
$(document).mouseup(function (e) {

    var container = $(".overlayContent");
    var target = e.target;

    // if the target of the click isn't the container and not in button, nor a descendant of the container
    if (
        !container.is(target)
        && !$(target).is("a[rel='leanModal']")
        && container.has(target).length === 0
    ) {
        $('div#overlay').fadeOut(speed);
        $('div#orderModifyContent').html('');
    } else if (container.has(target).length !== 0 && $(target).is(".modifyCloseButton")) {
        $('div#overlay').fadeOut(speed);
        $('div#orderModifyContent').html('');
    }
});

// show/hide loading img
$(document).ready(function () {
    $(document).ajaxStart(function () {
        $('div.overlay div.overlayContent').css({
            background: "#fff"
        });
        $("div.loading").show();
    });
    $(document).ajaxSuccess(function () {
        $("div.loading").hide();
    });
});