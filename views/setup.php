<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="shortcut icon" href="/images/favicon.ico" />
        <link href="../styles/template.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="../js/libraries/jquery.validate.js"></script>        
        <script src="../js/app/setup.js"></script>
        <title>Site Installer</title>
    </head>
    <body>
        <form method="post" action="setup.php">
            <div class="div_row">
                <div class="sombra">
                    <div class="placeholder"><strong><b>First Name:</b> </strong></div>
                    <input type="text" size="15" name="firstname" id="firstname" class="width_300 required"/>
                </div>
            </div>
            <div class="div_row">
                <div class="sombra">
                    <div class="placeholder"><strong><b>Last Name:</b> </strong></div>
                    <input type="text" size="15" name="lastname" id="lastname" class="width_300 required"/>
                </div>
            </div>
            <div class="div_row">
                <div class="sombra">
                    <div class="placeholder"><strong><b>Database/Site Username:</b> </strong></div>
                    <input type="text" size="15" name="dbUser" id="dbUser" class="width_300 required"/>
                </div>
            </div>
            <div class="div_row">
                <div class="sombra">
                    <div class="placeholder"><strong><b>Password:</b> </strong></div>
                    <input type="text" size="15"  name="dbPass" id="dbPass" class="width_300 required"/>
                                    </div>
            </div>
            <div class="div_row">
                <div class="sombra">
                    <div class="placeholder"><strong><b>Database Name:</b> </strong></div>
                    <input type="text" size="15"  name="dbName" id="dbName" class="width_300 required"/>
                </div>
            </div>
            <div>
                <input type="submit" value="Execute" />
            </div>
        </form>
    </body>
</html>