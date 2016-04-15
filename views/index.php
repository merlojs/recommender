<!DOCTYPE html>
<html lang="es">
    <head>
        <title><?php echo(SITE_NAME); ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Movie & Series Recommender" />
        <meta name="keywords" content="jquery, conent slider, content carousel, circular, expanding, sliding, css3" />

        <link rel="shortcut icon" href="../images/favicon.ico" />
        <link href="../styles/template.css" rel="stylesheet" type="text/css" />

        <link href="../styles/demo.css" rel="stylesheet" type="text/css" />
        <link href="../styles/jquery.jscrollpane.css" rel="stylesheet" type="text/css" media="all" />
        <!--<link href="../styles/reset.css" rel="stylesheet" type="text/css" />-->
        <link href="../styles/style.css" rel="stylesheet" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow&v1' rel='stylesheet' type='text/css' />
        <link href='http://fonts.googleapis.com/css?family=Coustard:900' rel='stylesheet' type='text/css' />
        <link href='http://fonts.googleapis.com/css?family=Rochester' rel='stylesheet' type='text/css' />
    </head>
    <body>
        <div class="overlay hidden"></div>
        <div class="container">
            <?php require_once(ROOT_PATH . "/views/includes/menu.php"); ?>
            <form action="logout.php" method="POST" id="logout">
            </form>

            <h1>Movie & Series Recommender<span>Rate and share your favourite movies and series!</span></h1>
            <div id="ca-container" class="ca-container">
                <div class="ca-wrapper">
                    <?php
                    if (!empty($movieSeriesList)) {
                        $itemIndex = 1;
                        /* @var $movieSeries MovieSeries */
                        /* @var $genre Genre */
                        /* @var $director Person */
                        /* @var $actor Person */
                        foreach ($movieSeriesList as $movieSeries) {
                            ?>
                            <div class="ca-item ca-item-<?php echo($itemIndex); ?>">
                                <div class="ca-item-main">
                                    <div class="ca-icon">
                                        <img class="listImg" src="<?php echo($movieSeries->getMovieImageLink()); ?>" alt="<?php echo($movieSeries->getOriginalTitle()); ?>" title="<?php echo($movieSeries->getOriginalTitle()); ?>"/>
                                    </div>
                                    <h3 style="margin-top: 15px;"><?php echo($movieSeries->getOriginalTitle()); ?></h3>
                                    <h4>
                                        <span class="ca-quote">&ldquo;</span>
                                        <span><?php echo($movieSeries->getGenre()->getGenreDesc()); ?><br/> 
                                            <?php
                                            $actorList = $movieSeries->getActorList();
                                            if (!empty($actorList)) {
                                                $num = count($actorList);
                                                $i = 1;
                                                foreach ($actorList as $actor) {
                                                    echo($actor->getPersonFirstname() . " " . $actor->getPersonLastname());
                                                    if ($num > $i) {
                                                        echo(" / ");
                                                    }
                                                    $i++;
                                                }
                                            }
                                            ?>
                                        </span>
                                    </h4>
                                    <a href="#" class="ca-more">More...</a>
                                </div>
                                <div class="ca-content-wrapper">
                                    <div class="ca-content">
                                        <h6><?php echo($movieSeries->getOriginalTitle()); ?></h6>
                                        <a href="#" class="ca-close">close</a>
                                        <div class="ca-content-text">
                                            <p>Cast: 
                                                <?php
                                                if (!empty($actorList)) {
                                                    $num = count($actorList);
                                                    $i = 1;
                                                    foreach ($actorList as $actor) {
                                                        echo($actor->getPersonFirstname() . " " . $actor->getPersonLastname());
                                                        if ($num > $i) {
                                                            echo(", ");
                                                        }
                                                        $i++;
                                                    }
                                                }
                                                ?>                                                                            
                                            </p>
                                            <p>Director:
                                                <?php
                                                $directorList = $movieSeries->getDirectorList();
                                                foreach ($directorList as $director) {
                                                    echo($director->getPersonFirstname() . " " . $director->getPersonLastname());
                                                }
                                                ?>                                                                            
                                            </p>
                                            <p>Genre: 
                                                <?php echo($movieSeries->getGenre()->getGenreDesc()); ?>
                                            </p>
                                            <p>Year: 
                                                <?php echo($movieSeries->getYear()); ?>
                                            </p>
                                        </div>
                                        <ul>
                                            <li><a href="<?php echo($movieSeries->getImdbLink()); ?>" target="_blank">IMDB Link</a></li>
                                            <li><a href="<?php echo($movieSeries->getTrailerLink()); ?>" target="_blank">Trailer</a></li>
                                            <?php
                                            if (isset($_SESSION['userId'])) {
                                                ?>
                                                <li onclick="shareThis(<?php echo($movieSeries->getMovieSeriesId()); ?>);"><span>Share this movie!</span></li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                        <input type="hidden" id="movieSeriesId" name="movieSeriesId" value="<?php echo($movieSeries->getMovieSeriesId()); ?>" />
                                        <div class='movie_choice'>
                                            Rate: <?php echo($movieSeries->getOriginalTitle()); ?>
                                            <div id="r_<?php echo($movieSeries->getMovieSeriesId());?>" class="rate_widget">
                                                    <div class="star_1 ratings_stars"></div>
                                                    <div class="star_2 ratings_stars"></div>
                                                    <div class="star_3 ratings_stars"></div>
                                                    <div class="star_4 ratings_stars"></div>
                                                    <div class="star_5 ratings_stars"></div>
                                                    <div class="total_votes">vote data</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $itemIndex++;
                        }
                    }
                    ?>                         
                </div>               
            </div>
        </div>
        <section class="popup hidden" id="popUpDatos" style="height: 250px;">
            <input type="button" value="x" class="popcerrar" title="Cerrar" onClick="cerrarPopUp()"/>
            <p class="subtitulo" id="form_titulo">Share with...</p>
            <section class="filtros">
                <div class="div_row">
                    <div class="sombra">
                        <div class="placeholder">Users</div>
                        <select id="userId" name="userId">
                            <?php
                            /* @var $user User */
                            foreach ($userList as $user) {
                                if ($user->getUserId() != $_SESSION['userId']) {
                                    ?>
                                    <option value="<?php echo($user->getUserId()); ?>"><?php echo($user->getUsername()); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="div_row">
                    <div class="placeholder_textarea">Message</div>
                    <textarea name="message" id="message" class="textArea width_500" rows="25" style="padding: 5px; margin: 8px 0px 4px 0;"></textarea>
                </div>
                <input type="hidden" id="idMovie" name="idMovie" value="" />

            </section>
            <footer class="pieDetalle">
                <div class="btnGris width_70 floatR"  title="Cancel" onclick="closeForm()">
                    <span class="btnspan" >Close</span>
                </div>
                <div class="btnRojo width_70 floatR"  title="Save" onclick="share()">
                    <span class="btnspan" >Share</span>
                </div>
                <div class="clear"></div>
            </footer>
        </section>      
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script type="text/javascript" src="../js/libraries/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="../js/general.js"></script>
        <!-- the jScrollPane script -->
        <script type="text/javascript" src="../js/libraries/jquery.mousewheel.js"></script>
        <script type="text/javascript" src="../js/libraries/jquery.contentcarousel.js"></script>

        <script type="text/javascript">
                $('#ca-container').contentcarousel();
        </script>
        <script>
            function limpiarCampos() {
                $('#userId').val('');
                $('#idMovie').val('');
                $('#message').val('');
            }
            function shareThis(id) {
                $('#idMovie').val(id);
                abrirPopUp();
            }
            function share() {
                if (($('#userId').val() != '') && ($('#idMovie').val() != '') && ($('#message').val() != '')) {
                    var recipient = $('#userId').val();
                    var movie = $('#idMovie').val();
                    var message = $('#message').val();
                    $.ajax({
                        async: false,
                        type: "POST",
                        url: "ajax/messageAjax.php",
                        data: {
                            recipient: recipient,
                            movie: movie,
                            message: message,
                            action: 'sendMessage'
                        },
                        beforeSend: function () {
                            mostrarMensaje("Sending...", 2);
                        },
                        success: function (data) {                            
                            if (data == 'ok') {
                                closeForm();
                            } else {
                                alert('Error!');
                            }
                        }
                    });
                } else {
                    alert('Empty message fields');
                }
            }
        </script>
    </body>
</html>	