<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if  (Empresa::first() )
        <title>
            {{ Empresa::first()->razon_social }}
        </title>
    @else
        <title>
            Frappe
        </title>
    @endif
    <script src="/jquery.js" type="text/javascript"></script>
    <link rel="shortcut icon" href="/frappe-framework-logo.png" type="image/x-icon">
    <link rel="stylesheet" href="/css/desk.min.css">
    <link rel="stylesheet" href="/css/list.min.css">
    <link rel="stylesheet" href="/css/form.min.css">
    <link rel="stylesheet" href="/css/module.min.css">
    <link rel="stylesheet" href="/css/report.min.css">
    <link rel="stylesheet" href="/css/erpnext.css">
    <link rel="stylesheet" href="/font/css/all.css">

</head>

<body data-ajax-state="complete" class="full-width" data-route="caja-del-restaurante" data-sidebar="1">

    <div class="main-section">
        <header>
            <div class="navbar navbar-default navbar-fixed-top" role="navigation">
                <div class="container">
                    <div class="navbar-header navbar-desk"> 
                        <a class="navbar-brand toggle-sidebar visible-xs visible-sm"> 
                            <i class="octicon octicon-three-bars"></i> 
                        </a> 
                        <a class="navbar-brand navbar-home hidden-xs hidden-sm" href="/"> 
                            <img class="app-logo" src="https://litoral480.com/assets/erpnext/images/erp-icon.svg"> 
                        </a>
                        <ul class="nav navbar-nav" id="navbar-breadcrumbs"> </ul>
                    </div>
                    <div class="navbar-center ellipsis" style="display: none;"></div>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" onclick="return false;">
                                <span class="avatar avatar-small" title="Administrator">
                                    <div class="standard-image" style="background-color: #fafbfc;">
                                        F
                                    </div>
                                </span>
                                <span class="ellipsis toolbar-user-fullname hidden-xs hidden-sm">
                                    @if  (Empresa::first() )
                                        {{ Empresa::first()->razon_social }}
                                    @endif
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        <div id="body_div">
            <div class="content page-container" id="page-caja-del-restaurante" data-page-route="caja-del-restaurante"
                style="">
                <div class="page-head">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-7 col-sm-8 col-xs-6 page-title">
                                <h1>
                                    <div class="title-image hide hidden-md hidden-lg"> </div>
                                    <div class="ellipsis title-text" id="Titulo">@yield('titulo')</div>
                                </h1>
                            </div>
                            <div class="text-right col-md-5 col-sm-4 col-xs-6 page-actions" id="page-actions">
                                @yield('buttons')
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container page-body">
                    <div class="page-wrapper">
                        <div class="page-content">
                            <div class="row layout-main">
                                <div class="col-md-12 layout-main-section-wrapper">
                                    <div class="layout-main-section">
                                        <div class="form-inner-toolbar"></div>
                                        <div class="page-form row hide"></div>
                                        <div>
                                            <div>
                                                <div class="form-layout">
                                                    <div class="form-message text-muted small hidden"></div>
                                                    <div class="form-page">
                                                        <div class="row form-section visible-section ">
                                                            <div class="section-body">
                                                                @yield('content')

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/bootstrap.js" type="text/javascript"></script>
    <script src="/socket.io.js" type="text/javascript"></script>
    <script src="/popover.js" type="text/javascript"></script>
    <script src="/demo.js" type="text/javascript"></script>
    <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    </script>
    @yield('scripts')
</body>

</html>