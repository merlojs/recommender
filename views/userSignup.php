<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="shortcut icon" href="../images/favicon.ico" />
        <link href="../styles/template.css" rel="stylesheet" type="text/css" />
        <link href="../styles/demo.css" rel="stylesheet" type="text/css" />
        <link href="../styles/style.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="../js/libraries/jquery.validate.js"></script>
        <script src="../js/general.js"></script>
        <script src="../js/app/userSignup.js"></script>
        <title>Pelis</title>
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

            <p class="subtitulo" id="form_titulo">User Signup</p>
            <section class="filtros">
                <form  id="formulario" action="#" method="POST" >
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder width_90">Last Name</div>
                            <input type="text" class="width_300 required" value="" name="UserLastname" id="UserLastname" />
                        </div>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder width_90">First Name</div>
                            <input type="text" class="width_300 required" value="" name="UserFirstname" id="UserFirstname" />
                        </div>
                    </div>                  
                    <div class="div_row" style="width: 460px; ">
                        <div class="sombra">
                            <div class="placeholder width_90">Username</div>
                            <input type="text" class="width_300 required" value="" name="Username" id="Username" />
                        </div>
                        <div id="username_availability_result">
                            <img src="../images/v.png" class="hidden checkUser" id="checkOk"/>
                            <img src="../images/x.png" class="hidden checkUser" id="checkKo"/>
                            <input type="hidden" id="checkUser" name="checkUser" value="" class="required">
                        </div>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder width_90">Password</div>
                            <input type="password" class="width_300 required" value="" name="Password" id="Password" />
                        </div>
                    </div>
                    <div class="div_row">
                        <div class="sombra">
                            <div class="placeholder width_90">Confirm Password</div>
                            <input type="password" class="width_300 required" value="" name="PasswordConfirm" id="PasswordConfirm" />
                        </div>
                    </div>
                    <?php
                    if ($_SESSION['profile'] == 'admin') {
                        ?>
                        <div class="div_row">
                            <div class="sombra">
                                <select name="profileOptions">
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>                                   
                                </select>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <input type="hidden" id="id" name="id" value="0">
                    <input type="hidden" id="profileCode" name="profileCode" value="">                   
                </form>
            </section>
            <footer class="pieDetalle">
                <div class="btnRojo width_100" title="checkAvailableUser" onclick="checkAvailableUser()">
                    <span class="btnspan">Check Availability</span>
                </div>    
                <div class="btnGris width_70"  title="Save" onclick="volver()">
                    <span class="btnspan" >Cancel</span>
                </div>
                <div class="btnRojo width_70" id="btnSubmit" title="Save" onclick="save()">
                    <span class="btnspan" >Submit</span>
                </div>
                <div class="clear"></div>
            </footer>
        </section>
    </body>
</html>