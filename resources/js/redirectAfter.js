$(document).ready(function() {
    window.setInterval(function() {
        var selector = $("#timeLeft"),
            timeLeft = selector.html();
        if (eval(timeLeft) == 0) {
            window.location = "/";
        } else {
            selector.html(eval(timeLeft) - eval(1));
        }
    }, 1000);
});