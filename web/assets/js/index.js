$("#confirmCoupon").click(function () {
    Routing.generate('buy', { 'product': $("#confirmCoupon").data().id, 'coupon': $("#inputCoupon").val()});
});

$(".buttonComprar").click(function () {
    $("#confirmCoupon").data( "id", $(this).attr('id'));
});
