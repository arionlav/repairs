// set cursor position
$.fn.selectRange = function (start, end) {
    if (typeof end === 'undefined') {
        end = start;
    }
    return this.each(function () {
        if ('selectionStart' in this) {
            this.selectionStart = start;
            this.selectionEnd = end;
        } else if (this.setSelectionRange) {
            this.setSelectionRange((
                start, end
            ))
        } else if (this.createTextRange) {
            var range = this.createTextRange();
            range.collapse(true);
            range.moveEnd('character', end);
            range.moveStart('character', start);
            range.select();
        }
    });
};

// surround with tag
function wrapText(elementId, openTag, closeTag) {
    var textArea = $('#' + elementId),
        len = textArea.val().length,
        start = textArea[0].selectionStart,
        end = textArea[0].selectionEnd,
        selectedText = textArea.val().substring(start, end),
        replacement = openTag + selectedText + closeTag;
    textArea.val(textArea.val().substring(0, start) + replacement + textArea.val().substring(end, len));
    r(end);
    //textArea.selectRange(end);
}
var i = 1;

$('span#tagStrong').on('click', function () {
    wrapText($(this).attr('datafld'), '<strong>', '</strong>');
});
$('span#tagP').on('click', function () {
    wrapText($(this).attr('datafld'), '<p>', '</p>');
});
$('span#tagPAttantion').on('click', function () {
    wrapText($(this).attr('datafld'), '<p class="textAttention"><span>Внимание! </span>', '</p>');
});
$('span#tagPTip').on('click', function () {
    wrapText($(this).attr('datafld'), '<p class="textTip"><span>Совет! </span>', '</p>');
});
$('span#tagH2').on('click', function () {
    wrapText($(this).attr('datafld'), '<h2 id="sub' + i++ + '">', '</h2>');
});
$('span#tagImgDesc').on('click', function () {
    wrapText($(this).attr('datafld'), '<p class="imgDescription">', '</p>');
});
$('span#tagUl').on('click', function () {
    wrapText($(this).attr('datafld'), '<ul>', '</ul>');
});
$('span#tagLi').on('click', function () {
    wrapText($(this).attr('datafld'), '<li>', '</li>');
});
$('span#tagVideo').on('click', function () {
    wrapText($(this).attr('datafld'), '<div class="iframeVideo">', '</div>');
});
$('span#tagLink').on('click', function () {
    wrapText($(this).attr('datafld'), '<a href="">Здесь', '</a>');
});