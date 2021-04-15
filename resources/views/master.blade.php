<?php 
use \App\TimeSessions;
use \App\Http\Controllers\ClientController;
use \App\Http\Controllers\OrderableController;

$lang->page('master');

if(isset($_SESSION['user'])){

    ClientController::generateAvatar();

    if(TimeSessions::where('user_id', ClientController::getId())->count() == 0) {
        TimeSessions::insert([
            'user_id' => ClientController::getId(),
            'ip_address' => $_SERVER['HTTP_X_FORWARDED_FOR'],
            'user_agent' => $_SERVER['REQUEST_URI'],
            'last_activity' => ( time() + 300 )
        ]);
    } else {
        TimeSessions::where('user_id', ClientController::getId())->update(['last_activity' => time() + 300]);
    }

}

?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $lang->get('app_name') }} - @yield('app_title')</title>
    <link href="https://lan-host.net/css/plugins/plugins.css?ver={{ time() }}" rel="stylesheet">
    <link href="https://lan-host.net/plugins/masterslider/style/masterslider.css?ver={{ time() }}" rel="stylesheet">
    <link href="https://lan-host.net/plugins/dzsparallaxer/dzsparallaxer.css?ver={{ time() }}" rel="stylesheet">
    <link href="https://lan-host.net/plugins/dzsparallaxer/scroller.css?ver={{ time() }}" rel="stylesheet">
    <link href="https://lan-host.net/plugins/dzsparallaxer/advancedscroller/plugin.css?ver={{ time() }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://lan-host.net/plugins/cubeportfolio/css/cubeportfolio.min.css?ver={{ time() }}">
    <link href="https://lan-host.net/css/style.css?ver={{ time() }}" rel="stylesheet">
    <link rel="stylesheet" href="https://lan-host.net/css/font-awesome-animation.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
    <script src="/js/bundle/bundle.js?ver={{time()}}" type="text/javascript"></script>
    @if( strstr($_SERVER['REQUEST_URI'], 'overview') )
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
    @endif

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        function copy(e){
            var inp = document.createElement('input');
            document.body.appendChild(inp)
            inp.value = e.textContent
            inp.select();
            document.execCommand('copy',false);
            inp.remove();
        }
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-92311625-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-92311625-1');
    </script>
</head>
    <body>

        <div class="top-bar pt10 pb10 clearfix">
            <div class="container-fluid">
                <div class="float-right top-menu">
                    @if(!isset($_SESSION['user']))
                        <a href="/client/login" class="top-menu-link"><i class="fas fa-sign-in-alt"></i> &nbsp{{ $lang->get('top_menu->link_login') }}</a>
                        <a href="/client/register" class="top-menu-link last-child-menu"><i class="fa fa-plus"></i> &nbsp{{ $lang->get('top_menu->link_register') }}</a>
                    @else
                        @if($clientController->get('client_data', 'permissions') >= 1)
                            <a href="/administration" class="top-menu-link" style="color: #d80c13b8!important;padding-right: 10px;border-right: 1px solid #eee!important;"><i class="fa fa-lock"></i> &nbsp{{ $lang->get('menu->link_admin_section') }}</a>
                        @endif
                        <a href="/client" class="top-menu-link"><i class="fa fa-user"></i> &nbsp{{ $lang->get('menu->link_client_section') }}</a>
                        <a href="/client/logout" class="top-menu-link last-child-menu"><i class="fas fa-sign-out-alt"></i> &nbsp{{ $lang->get('menu->link_logout') }}</a>
                    @endif
                </div>
                <div class="float-left top-menu-left">
                    <img onclick="window.location='/language/cs'" class="top-menu-image @if(!isset($_SESSION["language"]) || $_SESSION["language"] == "cs") top-menu-image-isset @endif" src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cb/Flag_of_the_Czech_Republic.svg/255px-Flag_of_the_Czech_Republic.svg.png">
                    <img class="top-menu-image @if(isset($_SESSION["language"]) && $_SESSION["language"] == "sk") top-menu-image-isset @endif" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/Flag_of_Slovakia.svg/255px-Flag_of_Slovakia.svg.png">
                    <img onclick="window.location='/language/en'" class="top-menu-image @if(isset($_SESSION["language"]) && $_SESSION["language"] == "en") top-menu-image-isset @endif" src="https://upload.wikimedia.org/wikipedia/en/thumb/a/a4/Flag_of_the_United_States.svg/300px-Flag_of_the_United_States.svg.png">
                    <img class="top-menu-image @if(isset($_SESSION["language"]) && $_SESSION["language"] == "de") top-menu-image-isset @endif" src="https://upload.wikimedia.org/wikipedia/en/thumb/b/ba/Flag_of_Germany.svg/255px-Flag_of_Germany.svg.png">
                </div>
            </div>
        </div>

        <nav class="navbar navbar-expand-lg navbar-light bg-faded">
            <a class="navbar-brand" href="/"><img src="/images/logo4.png?ver={{ time() }}" class="app-logo"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse" style="padding:0px!important;">
                <ul class="navbar-nav  ml-auto">
                    <li class="nav-item">
                        
                        <a class="nav-link" href="/">{{ $lang->get('menu->link_homepage') }}</a>

                    </li>

                    <li class="nav-item dropdown">

                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">{{ $lang->get('menu->link_products->title') }}</a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-submenu">
                                <li><a class="dropdown-item-section">{{ $lang->get('menu->link_products->products_dropdown->products_game') }}</a></li>
                                    @forelse($orderableController->getServices('game') as $service)
                                    <li><a href="" class="dropdown-item">{{ getValue($service, 'service_info', 'name') }}</a></li>
                                    @empty
                                    <li class="dropdown-item-andmore-li"><a class="dropdown-item-andmore">{{ $lang->get('menu->link_products->error_empty_services') }}</a></li>
                                    @endforelse
                                <li><a class="dropdown-item-section">{{ $lang->get('menu->link_products->products_dropdown->products_voice') }}</a></li>
                                    @forelse($orderableController->getServices('voice') as $service)
                                    <li><a href="" class="dropdown-item">{{ getValue($service, 'service_info', 'name') }}</a></li>
                                    @empty
                                    <li class="dropdown-item-andmore-li"><a class="dropdown-item-andmore">{{ $lang->get('menu->link_products->error_empty_services') }}</a></li>
                                    @endforelse
                                <li><a class="dropdown-item-section">{{ $lang->get('menu->link_products->products_dropdown->products_other') }}</a></li>
                                    @forelse($orderableController->getServices('other') as $service)
                                    <li><a href="" class="dropdown-item">{{ getValue($service, 'service_info', 'name') }}</a></li>
                                    @empty
                                    <li class="dropdown-item-andmore-li"><a class="dropdown-item-andmore">{{ $lang->get('menu->link_products->error_empty_services') }}</a></li>
                                    @endforelse
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">{{ $lang->get('menu->link_about_us->title') }}</a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-submenu">
                                <li><a href="" class="dropdown-item">{{ $lang->get('menu->link_about_us->about_us_dropdown->about_us_company') }}</a></li>
                                <li><a href="" class="dropdown-item">{{ $lang->get('menu->link_about_us->about_us_dropdown->about_us_vop') }}</a></li>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">{{ $lang->get('menu->link_contact') }}</a>
                    </li>
                    @if(isset($_SESSION['user']))
                        <li class="nav-item">
                            <a class="nav-link" href="/support">{{ $lang->get('menu->link_support') }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>

        @php
            if(isset($_SESSION['user']) && $clientController->get('client_data', 'permissions') >= 4){
                $file = file('/var/log/apache2/error.log');
                foreach($file as $line) {
                    if(strstr($line, "LAN-HOST.NET")){
                        $line1 = preg_replace('/.*(?=PHP Fatal error)/', "", $line);
                        $hash = base64_encode($line);
                        echo "<div class='alert alert-danger alert-dismissible fade show' style='background: #fff; border: 1px solid #bf2a2b21; color: #bf2a2b8a; border-left: 1px solid #bf2a2b21!important;'' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span onclick='removeLine(`" . $hash . "`);' aria-hidden='true'>×</span></button><strong> Nastala chyba!</strong><span style='font-style:italic'> " . $line1 . "</span></div>";
                    }
                   
                }
            }
        @endphp
        @if(isset($_SESSION['user']) && $clientController->get('client_data', 'permissions') >= 4)
        <script type="text/javascript">
            function removeLine(line) {
                var line = line.replace(/\\/g, '\\\\');
                $.ajax({
                    url: `https://lan-host.net/api-administrator/__remove_error__`,
                    type: 'POST',
                    data: {"_token": "{{ csrf_token() }}" ,arguments: line}
              });
            }

        </script>
        @endif
        @yield('section')

        <footer class="footer">

            <div class="container">

                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <h4>{{ $lang->get('app_name') }}</h4>
                        <p>
                            {{ substr($lang->get('about_us->prologue'), 0, 73) }}...
                        </p>
                        <a href="#" class="btn btn-underline">{{ $lang->get('about_us->link_read_more') }}</a>
                    </div>
                    <div class="col-md-6 col-lg-3">

                        <h4>{{ $lang->get('important_links') }}</h4>
                        <ul class="list-unstyled">
                            @if(!isset($_SESSION['user']))
                            <li><a href="/client/login" class="btn btn-underline">{{ $lang->get('top_menu->link_login') }}</a></li>
                            <li><a href="/client/register" class="btn btn-underline">{{ $lang->get('top_menu->link_register') }}</a></li>
                            @else
                            <li><a href="#" class="btn btn-underline">{{ $lang->get('menu->link_support') }}</a></li>
                            <li><a href="/client" class="btn btn-underline">{{ $lang->get('menu->link_client_section') }}</a></li>
                            @endif
                            <li><a href="#" class="btn btn-underline">{{ $lang->get('menu->link_contact') }}</a></li>
                            <li><a href="#" class="btn btn-underline">{{ $lang->get('menu->link_about_us->about_us_dropdown->about_us_vop') }}</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <h4>{{ $lang->get('blog->title') }}</h4>
                        <div class="mb30">
                            <form>
                                <div class="form-group">
                                    <label for="subscribe">{{ $lang->get('blog->description') }}</label>
                                </div>
                            </form>
                        </div>
                    </div>
                    <span class="font400">{{ str_replace(array('%1', '%2', '%3'), array('©', date('Y'), $lang->get('app_name')), $lang->get('copyright')) }}</span>
                </div>
            </div>
        </footer>
        <script src="/js/BigInteger.min.js"></script>
        <script src="/js/main.js?ver={{ time() }}"></script>
        <script src="/plugins/masterslider/masterslider.min.js"></script>
        <script type="text/javascript" src="/plugins/dzsparallaxer/dzsparallaxer.js"></script>
        <script type="text/javascript" src="/plugins/dzsparallaxer/scroller.js"></script>
        <script type="text/javascript" src="/plugins/dzsparallaxer/advancedscroller/plugin.js"></script>
        <script src="/js/master-slider-home.js"></script>
        <script type="text/javascript" src="/plugins/cubeportfolio/js/jquery.cubeportfolio.min.js"></script>
        <script type="text/javascript" src="/js/cube-portfolio-home.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.0/socket.io.js"></script>

        <script>
            
            //projects
               //cube portfolio init
            (function ($, window, document, undefined) {
                'use strict';

                // init cubeportfolio
                $('#js-grid-mosaic-flat').cubeportfolio({
                    filters: '#js-filters-mosaic-flat',
                    layoutMode: 'mosaic',
                    sortToPreventGaps: true,
                    mediaQueries: [{
                            width: 1500,
                            cols: 6
                        }, {
                            width: 1100,
                            cols: 4
                        }, {
                            width: 800,
                            cols: 3
                        }, {
                            width: 480,
                            cols: 2,
                            options: {
                                caption: '',
                                gapHorizontal: 15,
                                gapVertical: 15
                            }
                        }],
                    defaultFilter: '*',
                    animationType: 'fadeOutTop',
                    gapHorizontal: 0,
                    gapVertical: 0,
                    gridAdjustment: 'responsive',
                    caption: 'fadeIn',
                    displayType: 'fadeIn',
                    displayTypeSpeed: 100,
                    // lightbox
                    lightboxDelegate: '.cbp-lightbox',
                    lightboxGallery: true,
                    lightboxTitleSrc: 'data-title',
                    lightboxCounter: '<div class="cbp-popup-lightbox-counter">{current} of {total}</div>',
                    plugins: {
                        loadMore: {
                            selector: '#js-loadMore-mosaic-flat',
                            action: 'click',
                            loadItems: 3
                        }
                    }
                });
            })(jQuery, window, document);
        </script>

    </body>
</html>
