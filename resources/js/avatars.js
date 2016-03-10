$("div.avatar img").one("load", function () {
    var img = new Image(),
        margin;
    img.src = $(this).attr('src');

    if (img.naturalWidth > img.naturalHeight) {
        $(this).css({
            'height': '50px',
            'display': 'block'
        });
        margin = -($(this).width() - 50) / 2;
        $(this).css({
            'margin-left': margin
        });
    } else {
        $(this).css({
            'width': '50px',
            'display': 'block'
        });
        margin = -($(this).height() - 50) / 2;
        $(this).css({
            'margin-top': margin
        });
    }
}).each(function () {
    if (this.complete) {
        $(this).load();
    }
});