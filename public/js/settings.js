$(document).ready(function(){
    var account_security_level_current = {{ $security }};
    $("#account_security_level").val(account_security_level_current);
    process();
    $("#account_security_level").on('input', function() {
        account_security_level_current = $("#account_security_level").val()
        process();
    })
    function process() {
        if(account_security_level_current == 1) {
            $("#account_security_level").removeClass('greenslider');
            $("#account_security_level").removeClass('orangeslider');
            $("#account_security_level").addClass('redslider');
        } else if(account_security_level_current == 2) {
            $("#account_security_level").removeClass('greenslider');
            $("#account_security_level").removeClass('redslider');
            $("#account_security_level").addClass('orangeslider');
        } else {
            $("#account_security_level").removeClass('orangeslider');
            $("#account_security_level").removeClass('redslider');
            $("#account_security_level").addClass('greenslider');
        }
    }
})