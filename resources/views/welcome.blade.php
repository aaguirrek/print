<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @if  (Empresa::first() )
            <title>
                {{ Empresa::first()->razon_social }}
            </title>
        @else
            <title>
                Frappe
            </title>
        @endif
        <link rel="shortcut icon" href="/frappe-framework-logo.png" type="image/x-icon">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        
        <link href="/bootstrap.css" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
                <div class="top-right links">
                        <a href="/printer-list">impresoras</a>
                </div>

            <div class="content">
                <div class="title m-b-md">
                        {{$empresa->razon_social}}
                </div>

                <div class="links">
                    <a href=javascript:void(0)">Frappe :: Erpnext</a>
                </div>
            </div>
        </div>
        <script src="/jquery.js" type="text/javascript"></script>
        <script src="/popover.js" type="text/javascript"></script>
        <script src="/bootstrap.js" type="text/javascript"></script>
        <script src="/demo.js" type="text/javascript"></script>
        <script src="/socket.io.js" type="text/javascript"></script>
        <script>
            var print = io('http://frappe.cf:4003');
            print.on('print-socket',function(doc){
                doc = JSON.parse(doc);
                if(doc.sunat.ruc == "{{$empresa->ruc}}" ){
                    console.log(doc);
                    $.post('/api/print', doc, function(result){
                    });
                }
            });
        </script>        
    </body>
</html>
