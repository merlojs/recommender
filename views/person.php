<!DOCTYPE html>
<html>
    <head>
        <title><?php echo(SITE_NAME);?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="shortcut icon" href="../images/favicon.ico" />
        <link href="../styles/template.css" rel="stylesheet" type="text/css" />
        <link href="../styles/demo.css" rel="stylesheet" type="text/css" />
        <link href="../styles/style.css" rel="stylesheet" type="text/css" />
        <link href="../styles/jquery-ui.css" rel="stylesheet" type="text/css" />
        <link href="../styles/jquery.datepicker.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="../js/libraries/jquery.validate.js"></script>
        <script src="../js/libraries/jquery-ui.min.js"></script>
        <script src="../js/libraries/jquery.ui.datepicker-es.js"></script>
        <script src="../js/general.js"></script>
        <script src="../js/app/person.js"></script>
        <link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow&v1' rel='stylesheet' type='text/css' />
        <link href='http://fonts.googleapis.com/css?family=Coustard:900' rel='stylesheet' type='text/css' />
        <link href='http://fonts.googleapis.com/css?family=Rochester' rel='stylesheet' type='text/css' />
    </head>
    <body>
        <div class="container">
            <?php require_once(ROOT_PATH . "/views/includes/menu.php"); ?>
        </div>
        <div class="overlay hidden"></div>

        <section class="main_content">
            <div class="msg m_red" id="mensaje">
                <span>Loading...</span>
                <input type="button" value="x" class="cerrarMsg" onclick="ocultarMensaje()" title="Cerrar"/>
            </div>
            <p class="subtitulo">Search Filters</p>
            <section class="filtros">
                <form id="frmBusqueda" method="POST" action="">
                    <div class="sombra">
                        <div class="placeholder">Name</div>
                        <input type="text" id="personTextSearch" placeholder="Type Name..." class="medium" value="" />
                    </div>
                    <div class="btn_container">
                        <div class="btnRojo width_70 sinMargenL" id="btnBuscar" title="Buscar" onclick="searchByName()">
                            <span class="btnspanLupa"></span>
                        </div>
                    </div>
                </form>
            </section>
            <!-- NAVIGATION BAR -->
            <nav class="acciones">
                <p class="subtitulo">Actor & Director List</p>

                <div id="acciones">
                    <div class="content pointer" id="NavNuevo" title="Nuevo" onClick="nuevo()"><span class="btnspan">new</span></div>
                </div>
                <div class="clear"></div>
            </nav>
            <!-- RESULTS TABLE -->
            <section class="tabla">
                <table border="0">
                    <thead>
                        <tr>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Date Of Birth</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="resultados">

                    </tbody>
                </table>
            </section>
            <!-- PAGINADO -->
            <section class="paginado">
                <div class="pagina_info" >
                    <p>
                        <span id="paginador_desde">  0</span> -
                        <span id="paginador_hasta">  0</span> of
                        <span id="paginador_totales">0</span> Results
                    </p>
                </div>
                <div class="pagina_numbers">
                    <div class="pagina_button btnGris width_70" title="Primer Pagina">
                        <a href="#" onclick="irPagina(1)" id="paginador_primera">First</a>
                    </div>
                    <div class="pagina_button btnGris width_70" title="Pagina Anterior">
                        <a href="#" onclick="irPaginaAnterior()" id="paginador_anterior">Previous</a>
                    </div>
                    <span id="paginador_paginas">
                        <div class="pagina_button btnGris width_25" title="Ir a pagina 1"> <a href="?pagina=1" tabindex="0" class="paginate">1</a></div>
                        <div class="pagina_button btnGris width_25" title="Ir a pagina 2"> <a href="?pagina=2" tabindex="0" class="paginate">2</a></div>
                        <div class="pagina_button btnRojo width_25" title="Pagina Actual"> <a href="?pagina=3" tabindex="0" class="paginate">3</a></div>
                        <div class="pagina_button btnGris width_25" title="Ir a pagina 4"> <a href="?pagina=4" tabindex="0" class="paginate">4</a></div>
                        <div class="pagina_button btnGris width_25" title="Ir a pagina 5"> <a href="?pagina=5" tabindex="0" class="paginate">5</a></div>
                    </span>
                    <div class="pagina_button btnGris width_70" title="Pagina Siguiente">
                        <a href="#" onclick="irPaginaSigiente()" id="paginador_siguiente">Next</a>
                    </div>
                    <div class="pagina_button btnGris width_70" title="Ultima Pagina">
                        <a href="#" onclick="irPaginaUltima()" id="paginador_ultima">Last</a>
                    </div>
                </div>
                <div class="clear"></div>
            </section>
        </section>
        <section class="popup hidden" id="popUpDatos" >
            <input type="button" value="x" class="popcerrar" title="Cerrar" onClick="cerrarPopUp()"/>
            <p class="subtitulo" id="form_titulo">Edit Information</p>
            <section class="filtros">
                <form  id="formulario" action="#" method="POST" >
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Last Name</div>
                            <input type="text" class="width_300 required" value="" name="PersonLastname" id="PersonLastname" />
                        </div>
                        <img class="listImg" style="margin-bottom: -152px; margin-left: 30px; width: 130px; height: 185px;" id="personImg" src="" alt="" title=""/>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">First Name</div>
                            <input type="text" class="width_300 required" value="" name="PersonFirstname" id="PersonFirstname" />
                        </div>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Birth Date</div>
                            <input type="text" class="width_300 required fecha" value="" name="BirthDate" id="BirthDate" />
                        </div>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Image Link</div>
                            <input type="text" class="width_300 required" value="" name="PersonImageLink" id="PersonImageLink" />
                        </div>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Country</div>
                            <select id="countryId" name="countryId">
<?php
/* @var $country Country */
    foreach ($countryList as $country) {
?>
                                <option value="<?php echo($country->getCountryId());?>"><?php echo($country->getCountryDesc());?></option>
<?php
    }
?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="id" name="id" value="0">
                </form>
            </section>
            <footer class="pieDetalle">
                <div class="btnGris width_70 floatR"  title="Cancel" onclick="closeForm()">
                    <span class="btnspan" >Cancel</span>
                </div>
                <div class="btnRojo width_70 floatR"  title="Save" onclick="save()">
                    <span class="btnspan" >Save</span>
                </div>
                <div class="clear"></div>
            </footer>
        </section>
    </body>
</html>