<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title><?php if (isset($this->titulo)) print $this->titulo; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->	
    <link rel="stylesheet" href="<?php print $_layoutParam["caminho_css"] ?>bootstrap-responsive.min.css" media="screen"/>
    <link rel="stylesheet" href="<?php print $_layoutParam["caminho_css"] ?>bootstrap.min.css" media="screen"/> 
    <link rel="stylesheet" href="<?php print $_layoutParam["caminho_assets"] ?>styles.css" media="screen"/>
    <link rel="stylesheet" href="<?php print URL; ?>public/js/datapicker/src/DateTimePicker.css" /> 

    <script type="text/javascript" src="<?php print $_layoutParam["caminho_vendores"] ?>modernizr-2.6.2-respond-1.1.0.min.js"></script>
    <script type="text/javascript" src="<?php print $_layoutParam["caminho_vendores"] ?>jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="<?php print $_layoutParam["caminho_vendores"] ?>jquery.backstretch.min.js"></script>
    <script type="text/javascript" src="<?php print $_layoutParam["caminho_js"] ?>bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php print URL; ?>public/js/jquery.leanModal.min.js"></script>
    <script type="text/javascript" src="<?php print $_layoutParam["caminho_assets"] ?>scripts.js"></script>
    <script type="text/javascript" src="<?php print $_layoutParam["caminho_assets"] ?>form-validation.js"></script>
    <script type="text/javascript" src="<?php print URL; ?>public/js/datapicker/src/DateTimePicker.js"></script>

    <?php if (isset($_layoutParam['css']) && count($_layoutParam['css'])): ?>
        <?php for ($i = 0; $i < count($_layoutParam['css']); $i++): ?>
            <link rel="stylesheet" href="<?php print $_layoutParam['css'][$i] ?>" />       
        <?php endfor; ?>
    <?php endif; ?>

    <?php if (isset($_layoutParam['js']) && count($_layoutParam['js'])): ?>
        <?php for ($i = 0; $i < count($_layoutParam['js']); $i++): ?>
            <script type="text/javascript" src="<?php print $_layoutParam['js'][$i] ?>"></script>       
        <?php endfor; ?>
    <?php endif; ?>


    <script type="text/javascript">
        $(document).ready(function()
        {

            $("#dtBox").DateTimePicker();

            $(document).on('click', '#enviar', function() {

                var id = $("#pesquisa").val();

                setTimeout("$('#pageContent').load('http://localhost/Ldaps/usuario/pesquisar/?id=" + id + "');", 1000);
                $("#esconder").hide();

            });

        });
    </script>



</head>

<body>

    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="<?php URL ?>index">ZAP</a>
                <div class="nav-collapse collapse">
                    <ul class="nav pull-right">
                        <li class="dropdown">
                            <a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-user"></i> <?php print Session::get('nome'); ?> <i class="caret"></i>

                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a tabindex="-1" href="#">Perfil</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a tabindex="-1" href="<?php print URL ?>login/logof">Sair</a>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <ul class="nav">
                        

                        <?php if (isset($_layoutParam["menu"])): ?>
                            <?php for ($i = 0; $i < count($_layoutParam["menu"]); $i++): ?>
                                <?php (($item && $_layoutParam["menu"][$i]["id"] == $item) ? $item_style = "corrent" : $item_style = ""); ?> 

                                <li class="active" id="<?php print $item_style; ?>"><a href="<?php echo $_layoutParam["menu"][$i]["link"]; ?>"><i class="icon-chevron-right"></i> <?php echo $_layoutParam["menu"][$i]["titulo"]; ?></a></li>
                                <?php
                            endfor;
                        endif;
                        ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <noscript>Para usar todas funcionalidades ativa o javascript no seu navegador</noscript> 

    <div>
        <?php if (isset($this->erro)): ?>
            <h4 class="text-center alert-danger">  <?php print $this->erro; ?> </h4>

        <?php endif; ?>
    </div>
    <div class=""><?php if (isset($this->mensagem)): ?>
            <h3 class="text-center alert-success">  <?php print $this->mensagem; ?> </h3>
        <?php endif; ?>
    </div>

