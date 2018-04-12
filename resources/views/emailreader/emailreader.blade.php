<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Email reader</title>

        <link rel="apple-touch-icon" href="{{ asset('img/kit/free/apple-icon.png') }}">
        <link rel="icon" href="{{ asset('img/kit/free/favicon.png') }}">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />
        <link rel="stylesheet" href="{{asset('css/material-kit.css')}}">

        <!-- Styles -->

    </head>
    <body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg bg-primary mb-5">
            <div class="container-fluid">
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav">
                        <li class="active nav-item">
                            <a class="nav-link mr-4 max-45" href="{{$url . ($unseen ? '' : 'unseen')}}">
                                {{$unseen ? $labels['show_all'] : $labels['show_unseen']}}
                                <div class="ripple-container"></div></a>
                        </li>
                        <li class="active nav-item mr-4">
                            <a class="nav-link mr-5"><i class="material-icons">face</i> From<div class="ripple-container"></div></a>
                        </li>
                        <li class="active nav-item pl-4">
                            <a class="nav-link mx-5"><i class="material-icons">date_range</i>Date<div class="ripple-container"></div></a>
                        </li>
                        <li class="active nav-item">
                            <a class="nav-link "><i class="material-icons">message</i> Subject<div class="ripple-container"></div></a>
                        </li>
                    </ul>
                    @if(!isset($_GET['hide']))
                    <form class="form-inline ml-auto" method="GET" action="{{$url}}" >
                        @if(isset($_GET['q']))
                            <a href="{{ preg_replace('/q=[A-Z0-9a-z]*&*/', '',$url) }}" class="btn-clear"><i class="material-icons">clear</i></a>
                        @endif
                        <div class="form-group has-white bmd-form-group">
                            <input type="text" name='q' class="form-control" placeholder="Search" value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}" >
                        </div>
                        <button type="submit" class="btn btn-white btn-raised btn-fab btn-round">
                            <i class="material-icons">search</i>
                        </button>

                        @foreach($_GET as $name => $value)
                            @if($name == 'q') @break @endif
                            <?php $name = htmlspecialchars($name);
                            $value = htmlspecialchars($value);?>
                            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                        @endforeach
                        @if($unseen) <input type="hidden" name="unseen">@endif


                    </form>
                    @endif

                    <a class="btn btn-secondary ml-4" href="{{$return_link}}">{{$labels['return']}}</a>
                </div>
            </div>
        </nav>
        <div id="mails">

            @foreach ($email_list as $i => $email)
                <div class="card card-nav-tabs mail-fields">
                    <a data-toggle="collapse" href="#msg{{$i}}" role="button" aria-expanded="false" aria-controls="msg{{$i}}">
                        <div class="card-header card-header-primary">
                            <div class="nav-tabs-navigation">
                                <div class="nav-tabs-wrapper mail-fields" >
                                    <div class="row">

                                        <div class="mail-field-sm tx-center mx-2 ml-4">
                                            <i class="material-icons seen">{{$email->getSeen() ? "done" : "email"}}</i>
                                        </div>
                                        <div class="mail-field-md ml-4">
                                            <?php echo $email->getSender();?>
                                        </div>
                                        <div class="mail-field-sm ml-2 mr-1 tx-center">
                                            <?php echo $email->getDate();?>
                                        </div>
                                        <div class="mx-5">
                                            <?php echo $email->getSubject();?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <div class="collapse" id="msg{{$i}}" data-parent="#mails" >
                        <div class="card-body ">
                            <div class="tab-content mail-msg">
                                <div class="tab-pane active" id="profile">

                                    <pre style="white-space:pre-line"><?php echo $email->getMessage();?></pre>

                                    <div class="center text-center">
                                        <button class='btn bg-primary complete' data-emailId="{{$email->getId()}}" data-readed="{{$email->getSeen()}}" >

                                            {{$email->getSeen() ? $labels['mark_uncompleted'] : $labels['mark_completed']}}
                                            <i class="material-icons">{{ $email->getSeen() ? "undo" : "done"}}</i>

                                        </button>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            @endforeach

        </div>
    </div>





    <script>
        label_uncomplete = "{{$labels['mark_uncompleted']}}";
        label_complete = "{{$labels['mark_completed']}}";
    </script>
    <script src="{{asset('js/core/jquery.min.js')}}"></script>
    <script src="{{asset('js/core/popper.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-material-design.js')}}"></script>
    <!--  Plugin for Date Time Picker and Full Calendar Plugin  -->
    <script src="{{asset('js/plugins/moment.min.js')}}"></script>
    <!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
    <script src="{{asset('js/plugins/bootstrap-datetimepicker.min.js')}}"></script>
    <!--	Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="{{asset('js/plugins/nouislider.min.js')}}"></script>
    <!-- Material Kit Core initialisations of plugins and Bootstrap Material Design Library -->
    <script src="{{asset('js/material-kit.js?v=2.0.2')}}"></script>
    <script src="{{asset('js/completed.js')}}"></script>



    </body>
</html>
