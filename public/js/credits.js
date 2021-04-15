$(document).ready(function(){
 var active;
 $(".gateway-pay").hide();
 $(".select-gateway").on('click', function(){
     if(active != null){
         var element = $(document).find(`[data-gateway='${active}']`);
         element.css('border-color', 'rgba(0,0,0,.125)');
         element.children().css('background', 'rgba(0, 0, 0, 0.42)');
         hideInfo();
         active = $(this).data('gateway');
         $(this).css('border-color', 'rgba(71, 180, 243, 0.8)');
         $(this).children().css('background', 'rgba(71, 180, 243, 0.8)');
         $(".gateway-input").val(active);
         window.showInfo();
     } else {
         active = $(this).data('gateway');
         $(".gateway-input").val(active);
         $(this).css('border-color', 'rgba(71, 180, 243, 0.8)');
         $(this).children().css('background', 'rgba(71, 180, 243, 0.8)');
         window.showInfo();
     }
 });

 window.showInfo = function(){
     $(".gateway-pay").fadeToggle();
     $(`.${active}-info`).fadeToggle();
 }

 function hideInfo(){
     $(".gateway-pay").hide();
     $(`.${active}-info`).hide();
 }

});

var input = document.querySelector('#credits_num');

input.addEventListener('input', function()
{
if(parseInt(input.value) < 25 || input.value == "" || parseInt(input.value) > 90000) {
    document.getElementById("span_receive_credits").innerHTML = "???";
    document.getElementById("span_pay_money").innerHTML = "??? KČ";
} else {
    document.getElementById("span_receive_credits").innerHTML = input.value;
    document.getElementById("span_pay_money").innerHTML = Math.round(parseInt(input.value) + (10 / 100) * parseInt(input.value)) + " KČ";
}
});