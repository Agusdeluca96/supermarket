$(".confirmationButton").click(function () {
    window.location.href = Routing.generate('buyConfirmation', { 'value': $(this).attr('id') });
});
