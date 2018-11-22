$("#confirmCoupon").click(function () {
    var coupon = (($("#inputCoupon").val() != '') ? $("#inputCoupon").val()  : 0);
    window.location.href = Routing.generate('buy', { 'product': $("#confirmCoupon").data().id, 'coupon': coupon});
});

$(".buttonComprar").click(function () {
    $("#confirmCoupon").data( "id", $(this).attr('id'));
});
