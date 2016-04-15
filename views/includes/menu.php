<div class="header">
        <a href="../controllers/index.php"><span>HOME</span></a>        
<?php
    if ($_SESSION['profile'] == 'admin') {
?>
        <a href="../controllers/movieSeries.php"><span>Manage Movies/Series</span></a>
        <!--<a href="/controllers/user.php"><span>Manage Users</span></a>-->
        <a href="../controllers/person.php"><span>Add Actors/Directors To Database</span></a>                    
<?php
    } else if ($_SESSION['profile'] == 'guest') {
?>
        <span class="login" onclick="abrirPopUpLogin()">Already a user? Log In</span>
        <a href="../controllers/userSignup.php"><span>Sign up!</span></a>
<?php
    } /*else if ($_SESSION['profile'] == 'user') {
        header("location:".ROOT_PATH."/controllers/index.php");
        header('location: ../controllers/index.php');
        if(!(($_SERVER['PHP_SELF'] == ROOT_PATH.'/controllers/index.php') || ($_SERVER['PHP_SELF'] == ROOT_PATH.'/controllers/inbox.php'))){
            header('location: ../controllers/index.php');
        }
    }
     */
?>
    <span class="right_ab">
        <a href="#">
            &nbsp; Hello: 
            <strong>
<?php
    if(isset($_SESSION['userId'])){
        echo($_SESSION['username']); 
    } else {
        echo($_SESSION['profile']); 
    }
?>
            &nbsp;&nbsp;
            </strong>
        </a>
<?php
    if(isset($_SESSION['userId'])){
?>
        <a href="../controllers/index.php"> 
            <strong>Home&nbsp;&nbsp;</strong>
        </a>
        <!-- <a //onmouseover="" style="cursor: pointer;" onclick="abrirPopUpInbox()">  -->
        <a href="../controllers/inbox.php"> 
            <strong>Inbox(<?php echo($unreadMessageCount); ?>)&nbsp;&nbsp;</strong>
        </a>
        <a href="../controllers/logout.php"> 
            <strong>Logout&nbsp;&nbsp;</strong>
<?php
    }
?>
        </a>
    </span>
</div>
<div class="popupLogin hidden" id="popUpLoginForm">
    <p class="subtitulo" id="form_titulo">Login</p>
        <div class="div_row">
            <div class="sombra">
                <div class="placeholder">Username</div>
                <input type="text" class="width_100 required" value="" name="loginUsername" id="loginUsername" />
            </div>
        </div>
        <div class="div_row">
            <div class="sombra">
                <div class="placeholder">Password</div>
                <input type="password" class="width_100 required" value="" name="loginPassword" id="loginPassword" />
            </div>
        </div>
        <footer class="pieDetalle" style="margin-top: 5px;">
            <div class="btnGris width_70 floatR"  title="Cancel" onclick="cerrarPopUpLogin()">
                <span class="btnspan" >Cancel</span>
            </div>
            <div class="btnRojo width_70 floatR"  title="Login" onclick="authenticate()">
                <span class="btnspan" >Login</span>
            </div>
            <div class="clear"></div>
        </footer>
</div>
<div class="popupInbox hidden" id="popUpInbox">
    <p class="subtitulo" id="inbox_form_titulo">My Inbox</p>
    <!-- RESULTS TABLE -->
    <section class="tabla">
        <table border="0">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Recommended</th>
                    <th>Message Text</th>                    
                </tr>
            </thead>
            <tbody id="resultadosInbox">

            </tbody>
        </table>
    </section>
    <footer class="pieDetalle" style="margin-top: 5px;">
        <div class="btnGris width_70 floatR"  title="Close" onclick="cerrarPopUpInbox()">
            <span class="btnspan" >Close</span>
        </div>
        <div class="btnRojo width_70 floatR"  title="ViewMore" onclick="">
            <span class="btnspan" >View More</span>
        </div>
        <div class="clear"></div>
    </footer>
</div>