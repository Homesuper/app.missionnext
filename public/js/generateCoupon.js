(function ($) {
    var $codeInput = $('#code'),
        $generateCoupon = $('#generate-coupon');

    console.log(MD5('12121212'));
    $generateCoupon.on('click', function () {
        var time = (new Date).getTime() + Math.random();
        $codeInput.val(MD5(time.toString()));
    });

})(jQuery);
