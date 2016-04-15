<!DOCTYPE html>
<html>
    <head>
        <title><?php echo(SITE_NAME);?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="shortcut icon" href="../images/favicon.ico" />
        <link href="../styles/template.css" rel="stylesheet" type="text/css" />
        <link href="../styles/style.css" rel="stylesheet" type="text/css" />
        <link href="../styles/demo.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <script src="../js/libraries/jquery.validate.js"></script>
        <script src="../js/general.js"></script>
        <script src="../js/app/movieSeries.js"></script>
        <link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow&v1' rel='stylesheet' type='text/css' />
        <link href='http://fonts.googleapis.com/css?family=Coustard:900' rel='stylesheet' type='text/css' />
        <link href='http://fonts.googleapis.com/css?family=Rochester' rel='stylesheet' type='text/css' />
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        
        
        
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
            <p class="subtitulo">Search Filter</p>
            <section class="filtros">
                <form id="movieSearchForm" method="POST" action="">
                    <div class="sombra">
                        <div class="placeholder">Title</div>
                        <input type="text" id="movieTextSearch" placeholder="Type Movie Title..." class="medium" value="" />            
                    </div>
                    <div class="btn_container">
                        <div class="btnRojo width_70 sinMargenL" id="btnBuscar" title="Buscar" onclick="searchByTitle()">
                            <span class="btnspanLupa"></span>
                        </div>
                    </div>
                    <input type="hidden" id="controllerAction" value="" />
                </form>
            </section>

            <!-- NAVIGATION BAR -->
            <nav class="acciones">
                <p class="subtitulo">Movie/Series List</p>

                <div id="acciones">
                    <div class="content pointer" id="NavNuevo" title="Nuevo" onClick="nuevo()"><span class="btnspan">NEW</span></div>
                </div>
                <div class="clear"></div>
            </nav>
            <!-- RESULTS TABLE -->
            <section class="tabla">
                <table border="0">
                    <thead>
                        <tr>
                            <th>Original Title</th>
                            <th>Movie/Series?</th>                            
                            <th>Imdb Link</th>
                            <th>Year</th>
                            <th>Seasons</th>
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
        <section class="popup hidden" id="popUpDatos" style="height: 450px;">
            <input type="button" value="x" class="popcerrar" title="Cerrar" onClick="cerrarPopUp()"/>
            <p class="subtitulo" id="form_titulo">Movie Details</p>
            <section class="filtros">
                <form  id="formulario" action="#" method="POST" >
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Original Title</div>
                            <input type="text" class="width_300 required" value="" name="originalTitle" id="originalTitle" />
                        </div>
                    </div>                    
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Movie/Series</div>
                                <select id="MovieSeriesFlag" name="MovieSeriesFlag">
                                    <option value="M">Movie</option>
                                    <option value="S">Series</option>
                                </select>
                        </div>
                    </div>                    
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Genre</div>
                            <select id="GenreId" name="GenreId">
<?php
/* @var $genre Genre */
    foreach ($genreList as $genre) {
?>
                                <option value="<?php echo($genre->getGenreId());?>"><?php echo($genre->getGenreDesc());?></option>
<?php
    }
?>
                            </select>
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
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">IMDB Link</div>
                            <input type="text" class="width_300 required" value="" name="ImdbLink" id="ImdbLink" />
                        </div>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Year</div>
                            <input type="text" class="width_300 required" value="" name="Year" id="Year" />
                        </div>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Image Link</div>
                            <input type="text" class="width_300 required" value="" name="MovieImageLink" id="MovieImageLink" />
                        </div>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Trailer Link</div>
                            <input type="text" class="width_300 required" value="" name="TrailerLink" id="TrailerLink" />
                        </div>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Series Seasons</div>
                            <input type="text" class="width_300 " value="" name="Seasons" id="Seasons" />
                        </div>
                    </div>                    
                    <input type="hidden" id="id" name="id" value="">
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
        <section class="popup hidden" id="popUpPerformer">
            <input type="button" value="x" class="popcerrar" title="Cerrar" onClick="cerrarPopUpPerformer()"/>
            <p class="subtitulo" id="form_titulo">Edit Info</p>            
            <section class="tabla">
                <table border="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th style="width: 100px;">Performer Type</th>
                            <th width="100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="resultadosCast">

                    </tbody>
                </table>
            </section>
            <section class="filtros" id="perfomerDetails"  >
                <form id="formulario" action="#" method="POST" >
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Performer Type</div>
                            <select name="performerType" id="performerType">
                                <option value="A">Actor</option>
                                <option value="D">Director</option>
                            </select>
                        </div>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Person</div>
                            <input type="text" class="width_150" id="performerFullName" value="" />
                        </div>
                    </div>
                        <input type="hidden" id="idPerformer" />
                        <input type="hidden" id="idMovie" name="idMovie" value="" />
                </form>
            </section>
            <section class="filtros" id="castLiveSearch">
                <form id="frmBusquedaPerson" method="POST" action="">
                    <div class="sombra">
                        <div class="placeholder">Name</div>
                        <select name="personId" id="personId">
<?php
    if(!empty($personList)){
        foreach ($personList as $person) {   
/* @var $person Person */
?>
                                <option value="<?php echo($person->getPersonId());?>"><?php echo($person->getPersonLastname().', '.$person->getPersonFirstname());?></option>
<?php
        }
    }
?>                        
                            </select>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Performer Type</div>
                            <select name="castPerformerType" id="castPerformerType">
                                <option value="A">Actor</option>
                                <option value="D">Director</option>
                            </select>
                        </div>
                    </div>
                    <div class="btn_container">
                        <div class="btnRojo width_70 sinMargenL" id="btnAdd" title="Add" onclick="saveCast()">
                            <span class="btnspan">Add</span>
                        </div>
                    </div>
                    <input type="hidden" id="personCastId" value="" />
                </form>                
            </section>
            <a class="subtitulo" href="../controllers/person.php" target="_blank">Add an Actor/director to out Database!</a>            
            <footer class="pieDetalle">
                <div class="btnGris width_70 floatR"  title="Cancel" onclick="cerrarPopUpPerformer()">
                    <span class="btnspan" >Cancel</span>
                </div>
                <div class="btnRojo width_70 floatR"  title="Save" onclick="saveCast()">
                    <span class="btnspan" >Save</span>
                </div>
                <div class="clear"></div>
            </footer>
        </section>
        
        <!--
        <section class="popup hidden" id="popUpPerformerType" >
            <input type="button" value="x" class="popcerrar" title="Cerrar" onClick="closePopUpPerformerType()"/>
            <p class="subtitulo" id="form_titulo">Edit Perfomer Type</p>                       
            <section class="filtros">
                <form id="formulario" action="#" method="POST" >
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Performer Type</div>
                            <select name="performerType" id="performerType">
                                <option value="A">Actor</option>
                                <option value="D">Director</option>
                            </select>
                        </div>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder">Person</div>
                            <input type="text" id="perfomerFullName" name="perfomerFullName" value="" disabled="disabled"/>

                    
                    <input type="hidden" id="idMoviePerformer" name="idMoviePerformer" value="0"/>                
                
            </section>
            <footer class="pieDetalle">
                <div class="btnGris width_70 floatR"  title="Cancel" onclick="cerrarPopUpPerformer()">
                    <span class="btnspan" >Cancel</span>
                </div>
                <div class="btnRojo width_70 floatR"  title="Save" onclick="saveCast()">
                    <span class="btnspan" >Save</span>
                </div>
                <div class="clear"></div>
            </footer>
        </section>
        -->
        
        
    </body>
</html>