function r(str) {
    console.log(str);
}

$(function () {
    // Menu from side
    $('div#buttonMenu').click(function () {
        $('div#sideMenu').fadeIn(200);
        $('div.opacityBgBlack').fadeIn(200);
    });

    $('div.hideMenu').click(function () {
        $('div#sideMenu').fadeOut(200);
        $('div.opacityBgBlack').hide();
    });

    $('div.opacityBgBlack').click(function () {
        $('#sideMenu').fadeOut(200);
        $('div.opacityBgBlack').hide();
    });

    $(window).resize(function () {
        var widthSize = $(this).width(),
            sideMenuDiv = $('div#sideMenu'),
            hideMenuDiv = $('div.hideMenu');
        if (widthSize >= 1224) {
            sideMenuDiv.show();
            hideMenuDiv.hide();
        } else {
            sideMenuDiv.hide();
            hideMenuDiv.show();
        }
    });

    // Hover beauty
    var selector = $('div.imgContainer');
    selector.mouseenter(function () {
        var target = $(this).children().eq(1);
        $(target).delay(400).fadeIn(300);
    });
    selector.mouseleave(function () {
        var target = $(this).children().eq(1);
        $(target).fadeOut(100);
    });

    // scroll
    var scrollSelector = 'div#navUp';

    $(scrollSelector).hide();

    $(window).scroll(function () {
        if ($(this).scrollTop() > 150) {
            $(scrollSelector).fadeIn();
        } else {
            $(scrollSelector).fadeOut();
        }
    });

    $('div#navUp').click(function () {
            $('html, body').animate({
                scrollTop: '0px'
            }, 1000);
        }
    );

    // Menu drop down
    $('div.verticalMenu ul li span').click(function () {
        var selector = $(this).siblings().eq(0);
        selector.slideToggle(300);
        selector = $(this).children().eq(0);
        selector.fadeToggle(300);
    });

    // Show box in the right side
    $(window).on('scroll', function () {
        if ($(window).scrollTop() > 1200) {
            $('div div#advShowBox').fadeIn(300);
        } else {
            $('div div#advShowBox').fadeOut(200);
        }
    });

    $('span.afterPostFeedback').on('click', function () {
        $(this).siblings('div.ya-share2').fadeToggle(300);
    });

    // Increment like for articles
    $('span#incrementLike').click(function () {
        var id = $(this).attr('data');
        $.ajax({
            url: linkToLike,
            type: 'post',
            data: {
                'id': id,
                'likeFor': 'article',
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                incrementLike(id, result, 'article');
            }
        });
    });

    // Increment like for comment
    $('span#commentLikes').click(function () {
        var id = $(this).attr('data');
        $.ajax({
            url: linkToLike,
            type: 'post',
            data: {
                'id': id,
                'likeFor': 'comment',
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                incrementLike(id, result, 'comment');
            }
        });
    });

    $('span#answerToComment').click(function () {
        var id = $(this).attr('data'),
            name = $(this).attr('dataName');
        $("input[name='answerTo']").val(id);
        $('html, body').animate({
            scrollTop: $('div.addCommentBox').offset().top
        }, 400);
        $('form#comment textarea').val(name + ', ').focus();
    });

    // captcha. Must be set pathToRoot
    $('div.captchaDiv img').on('click', function () {
        $(this).attr('src', '/captcha/flat?' + Math.random());
    });
});

// increment like
function incrementLike(id, result, forWho) {
    if (result) {
        var spanId,
            selector,
            countBefore,
            bgPositionForRed;

        if (forWho == 'article') {
            spanId = 'incrementLike';
            bgPositionForRed = '-211';
        } else if (forWho == 'comment') {
            spanId = 'commentLikes';
            bgPositionForRed = '-168';
        } else {
            exit;
        }

        selector = $('span#' + spanId + '[data="' + id + '"]');

        if (selector.html()) {
            countBefore = parseInt(selector.html());
        } else {
            countBefore = 0;
        }

        selector.html(countBefore + 1);

        selector.css({
            'background-position': '0 ' + bgPositionForRed + 'px',
            'color': '#e10010'
        });

    } else {
        alert('Извините, произошла ошибка. ' +
            'Пожалуйста, сообщите нам об этом по email: asqwas@i.ua ' +
            'и в скором времени все будет работать!');
    }
}