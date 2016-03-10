$(document).ready(function () {
    $('img').on('click', function () {
        var id = $(this).attr('alt');
        $("#fileInput" + id).click();
        $('.beautyImageTd img#imgId' + id).css({
            opacity: 0.3
        });
        $('.beautyImageTd#tdId' + id + ' div').css({
            overflow: 'visible',
            height: 'auto'
        })
    });
});