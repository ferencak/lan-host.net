$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
    $(".dropdown-search > .search-dropdown-menu").click(function (e) {
        e.stopPropagation();
    });
    /**
    * Calculate
    */

    function calculateRam(total)
    {
        var price = 0;
        var ramPrice = 0.03;
        for(var i=0;i<total;i++){
            price = price + ( total * ramPrice / total );
        }
        return price;
    }
    function calculateSsd(total)
    {
        var price = 0;
        var ssdPrice = 0.005;
        for(var i=0;i<total;i++){
            price = price + ( total * ssdPrice / total );
        }
        return price;
    }
    //var socket = io.connect('https://socket.lan-host.net/socket.io/?EIO=3&transport=polling&t=MQIPxgV:8001', {secure: true, transports: ['websocket'], upgrade: false});

    console.log("%cLAN-HOST.NET", "-webkit-text-stroke-width: 1px; -webkit-text-stroke-color: black; color: red; font-size: 70px");
    console.log("%cTato funkce prohlížeče je určena pro vývojáře. Pokud vám někdo řekl, ať sem něco zkopírujete, abyste tím aktivovali nějakou funkci nebo hackovali něčí účet, pak byste měli vědět, že jde o podvod a že danému člověku poskytnete přístup ke svému účtu.", "-webkit-text-stroke-width: 0.1px; -webkit-text-stroke-color: black; font-size: large");
    if(window.location.href.includes('/services/order/')) {
    var packages;
    $.ajax({
       url: 'https://lan-host.net/api/getServiceData/' + window.location.pathname.split('/')[4],
       type: 'GET',
       success: function(callback){
            var call = $.parseJSON(callback);
            packages = call['service_data']['packages'];
            ramRange.val($.parseJSON(packages['small']['params']['ram']));
            ramValue.html($.parseJSON(packages['small']['params']['ram']) + ' MB');
            ssdRange.val($.parseJSON(packages['small']['params']['ssd']));
            ssdValue.html($.parseJSON(packages['small']['params']['ssd']) + ' MB');
            actualRam = ramRange.val();
            actualSsd = ssdRange.val();
            $("#recomended").show('fast');
            $("#alert-custom-package").hide();
            $("#recomended-slots").html(mc_recomended_slots(ramRange.val()));
       },
    });
}
    

    var ramSlider = $('.range-slider-ram'),
        ramRange = $('.range-slider__range-ram'),
        ramValue = $('.range-slider__value-ram'),
        ssdSlider = $('.range-slider-ssd'),
        ssdRange = $('.range-slider__range-ssd'),
        ssdValue = $('.range-slider__value-ssd'),
        ramCprice = calculateRam(ramRange.val()),
        ssdCprice = calculateSsd(ssdRange.val()),
        actualRam = ramRange.val(),
        actualSsd = ssdRange.val();

    function calculateCustomPrice()
    {
        var pr = ramCprice + ssdCprice;
        if(pr > 1000){
            $("#customPrice").addClass('price-tag-full-w');
        }else{
            $("#customPrice").removeClass('price-tag-full-w');
        }
        $("#customPrice").html(Math.round(pr));
    }

    function mc_recomended_slots(ram)
    {
        return ram / 125 | 0;
    }

    var pt1 = $("#pt-1"); 
    var pt2 = $("#pt-2"); 
    var pt3 = $("#pt-3");
    var pt4 = $("#pt-4"), customPrice = 0;
    var ptActive = pt1;
    $("#customPrice").html('???');

    pt1.on('click', function(){
        if(ptActive != pt1){
            ptActive.removeClass('pricing-card-modern-active');
            pt1.addClass('pricing-card-modern-active');
            ptActive = pt1;
            $("#customPrice").html('???');

            ramRange.val($.parseJSON(packages['small']['params']['ram']));
            ramValue.html($.parseJSON(packages['small']['params']['ram']) + ' MB');
            ssdRange.val($.parseJSON(packages['small']['params']['ssd']));
            ssdValue.html($.parseJSON(packages['small']['params']['ssd']) + ' MB');
            actualRam = ramRange.val();
            actualSsd = ssdRange.val();
            $("#recomended").show('fast');
            $("#alert-custom-package").hide('fast');
            $("#recomended-slots").html(mc_recomended_slots(ramRange.val()));

        }
    });
    pt2.on('click', function(){
        if(ptActive != pt2){
            ptActive.removeClass('pricing-card-modern-active');
            pt2.addClass('pricing-card-modern-active');
            ptActive = pt2;
            $("#customPrice").html('???');

            ramRange.val($.parseJSON(packages['medium']['params']['ram']));
            ramValue.html($.parseJSON(packages['medium']['params']['ram']) + ' MB');
            ssdRange.val($.parseJSON(packages['medium']['params']['ssd']));
            ssdValue.html($.parseJSON(packages['medium']['params']['ssd']) + ' MB');
            actualRam = ramRange.val();
            actualSsd = ssdRange.val();
            $("#recomended").show('fast');
            $("#alert-custom-package").hide('fast');
            $("#recomended-slots").html(mc_recomended_slots(ramRange.val()));

        }
    });
    pt3.on('click', function(){
        if(ptActive != pt3){
            ptActive.removeClass('pricing-card-modern-active');
            pt3.addClass('pricing-card-modern-active');
            ptActive = pt3;
            $("#customPrice").html('???');
            ramRange.val($.parseJSON(packages['big']['params']['ram']));
            ramValue.html($.parseJSON(packages['big']['params']['ram']) + ' MB');
            ssdRange.val($.parseJSON(packages['big']['params']['ssd']));
            ssdValue.html($.parseJSON(packages['big']['params']['ssd']) + ' MB');
            actualRam = ramRange.val();
            actualSsd = ssdRange.val();
            $("#recomended").show('fast');
            $("#alert-custom-package").hide('fast');
            $("#recomended-slots").html(mc_recomended_slots(ramRange.val()));
        }
    });
    ramSlider.each(function(){
        ramValue.each(function(){
          var ramValue = $(this).prev().attr('value');
          $(this).html(ramValue);
        });

        ramRange.on('input', function(){
            ptActive.removeClass('pricing-card-modern-active');
            pt4.addClass('pricing-card-modern-active');
            ptActive = pt4;
            $("#alert-custom-package").show('fast');

            ssdCprice = calculateSsd(ssdRange.val());
            ramCprice = calculateRam(this.value);
            calculateCustomPrice();
            $("#recomended-slots").html(mc_recomended_slots(ramRange.val()));
            $(this).next(ramValue).html(this.value + ' MB');
            if(this.value == packages['big']['params']['ram'] && ssdRange.val() == packages['big']['params']['ssd']){
                ptActive.removeClass('pricing-card-modern-active');
                $("#customPrice").html('???');
                pt3.addClass('pricing-card-modern-active');
                ptActive = pt3;
                $("#alert-custom-package").hide('fast');
            }else if(this.value == packages['medium']['params']['ram'] && ssdRange.val() == packages['medium']['params']['ssd']){
                ptActive.removeClass('pricing-card-modern-active');
                $("#customPrice").html('???');
                pt2.addClass('pricing-card-modern-active');
                ptActive = pt2;
                $("#alert-custom-package").hide('fast');
            }
            else if(this.value == packages['small']['params']['ram'] && ssdRange.val() == packages['small']['params']['ssd']){
                ptActive.removeClass('pricing-card-modern-active');
                $("#customPrice").html('???');
                pt1.addClass('pricing-card-modern-active');
                ptActive = pt1;
                $("#alert-custom-package").hide('fast');
            }
        });
    });

    ssdSlider.each(function(){

        ssdValue.each(function(){
          var ssdValue = $(this).prev().attr('value');
          $(this).html(ssdValue);
          $(this).val(ssdValue)
        });

        ssdRange.on('input', function(){
            ptActive.removeClass('pricing-card-modern-active');
            pt4.addClass('pricing-card-modern-active');
            ptActive = pt4;
            $("#alert-custom-package").show('fast');
            ramCprice = calculateRam(ramRange.val());
            ssdCprice = calculateSsd(this.value);
            calculateCustomPrice();
            $(this).next(ssdValue).html(this.value + ' MB');
            if(this.value == packages['big']['params']['ssd'] && ramRange.val() == packages['big']['params']['ram']){
                ptActive.removeClass('pricing-card-modern-active');
                $("#customPrice").html('???');
                pt3.addClass('pricing-card-modern-active');
                ptActive = pt3;
                $("#alert-custom-package").hide('fast');
            }else if(this.value == packages['medium']['params']['ssd'] && ramRange.val() == packages['medium']['params']['ram']){
                ptActive.removeClass('pricing-card-modern-active');
                $("#customPrice").html('???');
                pt2.addClass('pricing-card-modern-active');
                ptActive = pt2;
                $("#alert-custom-package").hide('fast');
            }
            else if(this.value == packages['small']['params']['ssd'] && ramRange.val() == packages['small']['params']['ram']){
                ptActive.removeClass('pricing-card-modern-active');
                $("#customPrice").html('???');
                pt1.addClass('pricing-card-modern-active');
                ptActive = pt1;
                $("#alert-custom-package").hide('fast');
            }
        });
    });
    /*
    $("#order_next_step").click(function(){

        if($("#service_name").val() == ''){
            return;
        }
        if($("#service_slots").val() == ''){
            return;
        }
        if($("#service_name").val() > 100000){
            return;
        }
        if($("#service_name").val() < 1){
            return;
        }
        var ramValueContinue = $(".range-slider__range-ram").val();
        var ssdValueContinue = $(".range-slider__range-ssd").val();

        $.ajax({
            url: '/api/createSession/ram|' + ramValueContinue,
            type: 'GET'
        });
        $.ajax({
            url: '/api/createSession/ssd|' + ssdValueContinue,
            type: 'GET'
        });
        $.ajax({
            url: '/api/createSession/package|' + ptActive.attr('id'),
            type: 'GET'
        });
        var time = new Date().getTime() / 1000 | 0;
        $.ajax({
            url: '/api/createSession/created_at|' + time,
            type: 'GET'
        });
        location.reload();
    });
    */
    /*
    $("#order_next_step2").on('click', function(){
        if(ptActive == pt4){
            $.ajax({
                url: '/api/createSession/ram|' + ramRange.val(),
                type: 'GET',
                success: function(callback){
                
                }
            });
            $.ajax({
                url: '/api/createSession/ssd|' + ssdRange.val(),
                type: 'GET',
                success: function(callback){
                
                }
            });
        }else{
            if(ptActive == pt1){
                window.location = '/client/services/order/' + window.location.pathname.split('/')[4] + "/small";
            }else{
                if(ptActive == pt2){
                    window.location = '/client/services/order/' + window.location.pathname.split('/')[4] + "/medium";
                }else{
                    if(ptActive == pt3){
                        window.location = '/client/services/order/' + window.location.pathname.split('/')[4] + "/big";
                    }else{
                        // Jsi kokot a nevím co děláš
                        window.location = '/client/services/order/' + window.location.pathname.split('/')[4];
                    }
                }
            }
        }
    });
    */
    $('.owl-testimonials').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 3
            }
        }
    });
    $('.owl-testimonials-full').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });
    $('.owl-image-header').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });
    $('.owl-clients').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 3
            },
            1000: {
                items: 5
            }
        }
    });
    $('.nav-tabs.hover-tabs > li > a').hover(function () {
        $(this).tab('show');
    });
    setTimeout(function () {
        if ($('#onloadModal').length) {
            $.magnificPopup.open({
                items: {
                    src: '#onloadModal'
                },
                type: 'inline'
            });
        }
    }, 500);
    $('.video-popup').magnificPopup({
        type: 'iframe'
    });
    $(window).scroll(function () {
        var winTop = $(window).scrollTop();
        if (winTop >= 100) {
            $(".navbar-sticky").addClass("sticky-active");
        } else {
            $(".navbar-sticky").removeClass("sticky-active");
        }
    });
    $(".typed").typed({
        strings: ["Beautifully handcrafted", "Easily customizable", "Modern tools for startups"],
        typeSpeed: 50,
        backSpeed: 10,
        backDelay: 2000,
        showCursor: false,
        fadeOut: true,
        loop: true
    });
    wow = new WOW(
            {
                boxClass: 'wow',
                animateClass: 'animated',
                offset: 0,
                mobile: true,
                live: true
            }
    );
    wow.init();
    smoothScroll.init({
        selector: '[data-scroll]', 
        speed: 1000, 
        easing: 'easeInOutCubic',
        offset: 56,
        callback: function (anchor, toggle) {}
    });
});

