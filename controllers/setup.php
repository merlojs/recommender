<?php

if (!isset($_SESSION)) {
    session_start();
}
if (file_exists('../backend/config/configDB.ini')){
    header('location: ../controllers/index.php');
} else {

    $firstname = (isset($_POST['firstname']) ? $_POST['firstname'] : '');
    $lastname = (isset($_POST['lastname']) ? $_POST['lastname'] : '');
    $dbUser = (isset($_POST['dbUser']) ? $_POST['dbUser'] : '');
    $dbPass = (isset($_POST['dbPass']) ? $_POST['dbPass'] : '');
    $dbName = (isset($_POST['dbName']) ? $_POST['dbName'] : '');    

    if ($firstname != '' && $lastname != '' && $dbUser != '' && $dbPass != '' && $dbName != '') {

   
        
        $sql = 'CREATE DATABASE IF NOT EXISTS ' . $dbName . ' CHARACTER SET utf8 COLLATE utf8_spanish_ci; ';

        /*  Append Data to ConfigDB File    */

        $configDBFile = "../backend/config/configDB.ini";
        $fhDB = fopen($configDBFile, 'a') or die("can't open file");

        $stringDataDB = ";DB Config File\n\n";
        $stringDataDB .= "[DBConfig]\n";
        $stringDataDB .= "db_default = \"" . strtoupper($dbName) . "\"\n\n";

        $stringDataDB .= "[" . strtoupper($dbName) . "]\n";
        $stringDataDB .= "db_name = \"" . $dbName . "\"\n";
        $stringDataDB .= "db_string = \"localhost\"\n";
        $stringDataDB .= "db_usr = \"" . $dbUser . "\"\n";
        $stringDataDB .= "db_pass = \"" . $dbPass . "\"\n";
        $stringDataDB .= "db_driver = \"mysql\"\n";

        fwrite($fhDB, $stringDataDB);
        fclose($fhDB);


        /*  Append Data to ConfigDAO File    */

        $configDAOFile = "../backend/config/configDAO.ini";
        $fhDAO = fopen($configDAOFile, 'a') or die("can't open file");

        $stringDataDAO = ";DAO Config File\n\n";
        $stringDataDAO .= "[includes]\n";
        $stringDataDAO .= "poolDb = \"/backend/db/ConnectionPool.php\"\n";
        $stringDataDAO .= "configDb = \"/backend/config/configDB.ini\"\n\n";
        $stringDataDAO .= "country = \"/backend/dao/CountryDAO.php\"\n";
        $stringDataDAO .= "genre = \"/backend/dao/GenreDAO.php\"\n";
        $stringDataDAO .= "movieseries = \"/backend/dao/MovieSeriesDAO.php\"\n";
        $stringDataDAO .= "user = \"/backend/dao/UserDAO.php\"\n";
        $stringDataDAO .= "origin = \"/backend/dao/OriginDAO.php\"\n";
        $stringDataDAO .= "person = \"/backend/dao/PersonDAO.php\"\n";
        $stringDataDAO .= "performer = \"/backend/dao/PerformerDAO.php\"\n";
        $stringDataDAO .= "usermessage = \"/backend/dao/UserMessageDAO.php\"\n";
        $stringDataDAO .= "profile = \"/backend/dao/ProfileDAO.php\"\n";
        $stringDataDAO .= "userprofile = \"/backend/dao/UserProfileDAO.php\"\n";
        $stringDataDAO .= "rating = \"/backend/dao/RatingDAO.php\"\n\n";
        $stringDataDAO .= "[DAOClases]\n";
        $stringDataDAO .= "country = \"CountryDAO\"\n";
        $stringDataDAO .= "genre = \"GenreDAO\"\n";
        $stringDataDAO .= "movieseries = \"MovieSeriesDAO\"\n";
        $stringDataDAO .= "user = \"UserDAO\"\n";
        $stringDataDAO .= "origin = \"OriginDAO\"\n";
        $stringDataDAO .= "person = \"PersonDAO\"\n";
        $stringDataDAO .= "performer = \"PerformerDAO\"\n";
        $stringDataDAO .= "usermessage = \"UserMessageDAO\"\n";
        $stringDataDAO .= "profile = \"ProfileDAO\"\n";
        $stringDataDAO .= "userprofile = \"UserProfileDAO\"\n";
        $stringDataDAO .= "rating = \"RatingDAO\"\n\n";
        $stringDataDAO .= "[DAOConnection]\n";
        $stringDataDAO .= "country = \"" . $dbName . "\"\n";
        $stringDataDAO .= "genre = \"" . $dbName . "\"\n";
        $stringDataDAO .= "movieseries = \"" . $dbName . "\"\n";
        $stringDataDAO .= "user = \"" . $dbName . "\"\n";
        $stringDataDAO .= "origin = \"" . $dbName . "\"\n";
        $stringDataDAO .= "person = \"" . $dbName . "\"\n";
        $stringDataDAO .= "performer = \"" . $dbName . "\"\n";
        $stringDataDAO .= "usermessage = \"" . $dbName . "\"\n";
        $stringDataDAO .= "profile = \"" . $dbName . "\"\n";
        $stringDataDAO .= "userprofile = \"" . $dbName . "\"\n";
        $stringDataDAO .= "rating = \"" . $dbName . "\"\n";


        fwrite($fhDAO, $stringDataDAO);
        fclose($fhDAO);

        $sql .= 'CREATE USER \'' . $dbUser . '\'@\'localhost\' IDENTIFIED BY \'' . $dbPass . '\';';

        $sql .= 'GRANT ALL PRIVILEGES ON ' . $dbName . '.* TO \'' . $dbUser . '\'@\'localhost\' '
                . 'REQUIRE NONE WITH GRANT OPTION MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 '
                . 'MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0; ';

        $sql .= 'USE ' . $dbName . '; ';

        /* Country Table Definition */

        $sql .= 'CREATE TABLE IF NOT EXISTS `country` (
    `country_id` int(5) NOT NULL AUTO_INCREMENT COMMENT "Country Id",
    `country_desc` varchar(150) COLLATE utf8_spanish_ci NOT NULL COMMENT "Country Description",
    PRIMARY KEY (`country_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=198 ; ';

        /* Country Table Data  */

        $sql .= 'INSERT INTO `country` (`country_id`, `country_desc`) VALUES
    (1, "Afghanistan"),
    (2, "Albania"),
    (3, "Algeria"),
    (4, "Andorra"),
    (5, "Angola"),
    (6, "Antigua and Barbuda"),
    (7, "Argentina"),
    (8, "Armenia"),
    (9, "Australia"),
    (10, "Austria"),
    (11, "Azerbaijan"),
    (12, "Bahamas"),
    (13, "Bahrain"),
    (14, "Bangladesh"),
    (15, "Barbados"),
    (16, "Belarus"),
    (17, "Belgium"),
    (18, "Belize"),
    (19, "Benin"),
    (20, "Bhutan"),
    (21, "Bolivia"),
    (22, "Bosnia and Herzegovina"),
    (23, "Botswana"),
    (24, "Brazil"),
    (25, "Brunei"),
    (26, "Bulgaria"),
    (27, "Burkina Faso"),
    (28, "Burundi"),
    (29, "Cabo Verde"),
    (30, "Cambodia"),
    (31, "Cameroon"),
    (32, "Canada"),
    (33, "Central African Republic"),
    (34, "Chad"),
    (35, "Chile"),
    (36, "China"),
    (37, "Colombia"),
    (38, "Comoros"),
    (39, "Congo, Republic of the"),
    (40, "Congo, Democratic Republic of the"),
    (41, "Costa Rica"),
    (42, "Cote d""Ivoire"),
    (43, "Croatia"),
    (44, "Cuba"),
    (45, "Cyprus"),
    (46, "Czech Republic"),
    (47, "Denmark"),
    (48, "Djibouti"),
    (49, "Dominica"),
    (50, "Dominican Republic"),
    (51, "Ecuador"),
    (52, "Egypt"),
    (53, "El Salvador"),
    (54, "Equatorial Guinea"),
    (55, "Eritrea"),
    (56, "Estonia"),
    (57, "Ethiopia"),
    (58, "Fiji"),
    (59, "Finland"),
    (60, "France"),
    (61, "Gabon"),
    (62, "Gambia"),
    (63, "Georgia"),
    (64, "Germany"),
    (65, "Ghana"),
    (66, "Greece"),
    (67, "Grenada"),
    (68, "Guatemala"),
    (69, "Guinea"),
    (70, "Guinea-Bissau"),
    (71, "Guyana"),
    (72, "Haiti"),
    (73, "Honduras"),
    (74, "Hungary"),
    (75, "Iceland"),
    (76, "India"),
    (77, "Indonesia"),
    (78, "Iran"),
    (79, "Iraq"),
    (80, "Ireland"),
    (81, "Israel"),
    (82, "Italy"),
    (83, "Jamaica"),
    (84, "Japan"),
    (85, "Jordan"),
    (86, "Kazakhstan"),
    (87, "Kenya"),
    (88, "Kiribati"),
    (89, "Kosovo"),
    (90, "Kuwait"),
    (91, "Kyrgyzstan"),
    (92, "Laos"),
    (93, "Latvia"),
    (94, "Lebanon"),
    (95, "Lesotho"),
    (96, "Liberia"),
    (97, "Libya"),
    (98, "Liechtenstein"),
    (99, "Lithuania"),
    (100, "Luxembourg"),
    (101, "Macedonia"),
    (102, "Madagascar"),
    (103, "Malawi"),
    (104, "Malaysia"),
    (105, "Maldives"),
    (106, "Mali"),
    (107, "Malta"),
    (108, "Marshall Islands"),
    (109, "Mauritania"),
    (110, "Mauritius"),
    (111, "Mexico"),
    (112, "Micronesia"),
    (113, "Moldova"),
    (114, "Monaco"),
    (115, "Mongolia"),
    (116, "Montenegro"),
    (117, "Morocco"),
    (118, "Mozambique"),
    (119, "Myanmar (Burma)"),
    (120, "Namibia"),
    (121, "Nauru"),
    (122, "Nepal"),
    (123, "Netherlands"),
    (124, "New Zealand"),
    (125, "Nicaragua"),
    (126, "Niger"),
    (127, "Nigeria"),
    (128, "North Korea"),
    (129, "Norway"),
    (130, "Oman"),
    (131, "Pakistan"),
    (132, "Palau"),
    (133, "Palestine"),
    (134, "Panama"),
    (135, "Papua New Guinea"),
    (136, "Paraguay"),
    (137, "Peru"),
    (138, "Philippines"),
    (139, "Poland"),
    (140, "Portugal"),
    (141, "Qatar"),
    (142, "Romania"),
    (143, "Russia"),
    (144, "Rwanda"),
    (145, "St. Kitts and Nevis"),
    (146, "St. Lucia"),
    (147, "St. Vincent and The Grenadines"),
    (148, "Samoa"),
    (149, "San Marino"),
    (150, "Sao Tome and Principe"),
    (151, "Saudi Arabia"),
    (152, "Senegal"),
    (153, "Serbia"),
    (154, "Seychelles"),
    (155, "Sierra Leone"),
    (156, "Singapore"),
    (157, "Slovakia"),
    (158, "Slovenia"),
    (159, "Solomon Islands"),
    (160, "Somalia"),
    (161, "South Africa"),
    (162, "South Korea"),
    (163, "South Sudan"),
    (164, "Spain"),
    (165, "Sri Lanka"),
    (166, "Sudan"),
    (167, "Suriname"),
    (168, "Swaziland"),
    (169, "Sweden"),
    (170, "Switzerland"),
    (171, "Syria"),
    (172, "Taiwan"),
    (173, "Tajikistan"),
    (174, "Tanzania"),
    (175, "Thailand"),
    (176, "Timor-Leste"),
    (177, "Togo"),
    (178, "Tonga"),
    (179, "Trinidad and Tobago"),
    (180, "Tunisia"),
    (181, "Turkey"),
    (182, "Turkmenistan"),
    (183, "Tuvalu"),
    (184, "Uganda"),
    (185, "Ukraine"),
    (186, "United Arab Emirates"),
    (187, "United Kingdom"),
    (188, "United States"),
    (189, "Uruguay"),
    (190, "Uzbekistan"),
    (191, "Vanuatu"),
    (192, "Vatican City (Holy See)"),
    (193, "Venezuela"),
    (194, "Vietnam"),
    (195, "Yemen"),
    (196, "Zambia"),
    (197, "Zimbabwe"); ';

        /* Genre Table Definition */

        $sql .= 'CREATE TABLE IF NOT EXISTS `genre` (
    `genre_id` int(4) NOT NULL AUTO_INCREMENT COMMENT "Genre Id",
    `genre_desc` varchar(100) COLLATE utf8_spanish_ci NOT NULL COMMENT "Genre Description",
    PRIMARY KEY (`genre_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=8 ; ';

        /* Genre Table Data */

        $sql .= 'INSERT INTO `genre` (`genre_id`, `genre_desc`) VALUES
    (1, "Action"),
    (2, "Adventure"),
    (3, "Animation"),
    (4, "Comedy"),
    (5, "Documentary"),
    (6, "Drama"),
    (7, "Thriller"); ';

        /* Movie/Series Table Definition */

        $sql .= 'CREATE TABLE IF NOT EXISTS `movie_series` (
    `movie_series_id` int(15) NOT NULL AUTO_INCREMENT COMMENT "Movie/Series Id",
    `original_title` varchar(250) COLLATE utf8_spanish_ci NOT NULL COMMENT "Original Title",
    `genre_id` int(4) NOT NULL COMMENT "Genre Id",
    `movie_series_flag` varchar(1) COLLATE utf8_spanish_ci NOT NULL COMMENT "Movie/Series Flag (M: Movie/S: Series)",    
    `imdb_link` varchar(1500) COLLATE utf8_spanish_ci NOT NULL COMMENT "IMDB Link",
    `year` int(4) NOT NULL COMMENT "Release Year",
    `image_link` varchar(1500) COLLATE utf8_spanish_ci NOT NULL COMMENT "Link To Poster Image",
    `trailer_link` varchar(1500) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT "Link To Trailer",
    `seasons` int(2) DEFAULT NULL COMMENT "Number of Seasons (Series)",    
    PRIMARY KEY (`movie_series_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=6 ; ';

        /* Movie/Series Table Data */

        $sql .= 'INSERT INTO `movie_series` (`movie_series_id`, `original_title`, `genre_id`, `movie_series_flag`, `imdb_link`, `year`, `image_link`, `trailer_link`, `seasons`) VALUES
    (1, "Seinfeld", 4, "S", "http://www.imdb.com/title/tt0098904/", 1989, "http://www.gstatic.com/tv/thumb/tvbanners/183875/p183875_b_v7_ab.jpg", NULL, 9),
    (2, "The Shawshank Redemption", 6, "M", "http://www.imdb.com/title/tt0111161/", 1995, "http://t0.gstatic.com/images?q=tbn:ANd9GcSkmMH-bEDUS2TmK8amBqgIMgrfzN1_mImChPuMrunA1XjNTSKm", "https://www.google.com.ar/url?sa=t&rct=j&q=&esrc=s&source=web&cd=1&cad=rja&uact=8&sqi=2&ved=0ahUKEwj92LCMws_JAhWDg5AKHWkGC4cQyCkIHDAA&url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3D6hB3S9bIaco&usg=AFQjCNErksbo9BgIB6yGN5Hg86gVbMrSeg&sig2=4Gkjoq3ZtmlLispl006U_w&bvm=bv.109395566,d.Y2I", NULL),
    (3, "Breaking Bad", 6, "S", "http://www.imdb.com/title/tt0903747/", 2008, "http://www.gstatic.com/tv/thumb/tvbanners/185846/p185846_b_v8_ad.jpg", NULL, 5),
    (4, "The Revenant", 2, "M", "http://www.imdb.com/title/tt1663202/", 2015, "http://dl9fvu4r30qs1.cloudfront.net/80/9f/a6afc7384cc1acb88de59c4d2d34/revenant-leo.jpg", "https://www.youtube.com/watch?v=LoebZZ8K5N0", NULL),
    (5, "The Danish Girl", 6, "M", "http://www.imdb.com/title/tt0810819/", 2015, "https://soniaunleashed.files.wordpress.com/2016/02/the-danish-girl-poster.jpg", "https://www.youtube.com/watch?v=d88APYIGkjk", NULL); ';

        /* Origin Table Definition */

        $sql .= 'CREATE TABLE IF NOT EXISTS `origin` (
    `movie_series_id` int(15) NOT NULL,
    `country_id` int(5) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci; ';

        /* Origin Table Data */

        $sql .= 'INSERT INTO `origin` (`movie_series_id`, `country_id`) VALUES
    (2, 188),
    (1, 188),
    (3, 188),
    (4, 188),
    (5, 187); ';

        /* Performer Table Definition */

        $sql .= 'CREATE TABLE IF NOT EXISTS `performer` (
    `performer_id` int(25) NOT NULL AUTO_INCREMENT COMMENT "Performer Id",
    `movie_series_id` int(15) NOT NULL COMMENT "Movie/Series Id",
    `person_id` int(10) NOT NULL COMMENT "Person Id",
    `performer_type` varchar(1) COLLATE utf8_spanish_ci NOT NULL COMMENT "Performer Type (A: Actor/D: Director)",
    PRIMARY KEY (`performer_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci; ';

        /* Performer Table Data */

        $sql .= 'INSERT INTO `performer` (`movie_series_id`, `person_id`, `performer_type`) VALUES
    (2, 7, "A"),
    (1, 8, "A"),
    (1, 9, "A"),
    (1, 10, "A"),
    (1, 11, "A"),
    (2, 12, "A"),
    (3, 13, "A"),
    (3, 14, "A"); ';

        /* Person Table Definition */

        $sql .= 'CREATE TABLE IF NOT EXISTS `person` (
    `person_id` int(10) NOT NULL AUTO_INCREMENT COMMENT "Person Id",
    `lastname` varchar(150) COLLATE utf8_spanish_ci NOT NULL COMMENT "Last Name",
    `firstname` varchar(150) COLLATE utf8_spanish_ci NOT NULL COMMENT "First Name",
    `country_id` int(5) NOT NULL COMMENT "Country Id",
    `birth_date` date NOT NULL COMMENT "Date Of Birth",
    `death_date` date DEFAULT NULL COMMENT "Date Of Death (If Applicable)",
    `image_link` varchar(1500) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT "Link To Image",
    PRIMARY KEY (`person_id`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=15 ; ';

        /* Person Table Data */

        $sql .= 'INSERT INTO `person` (`person_id`, `lastname`, `firstname`, `country_id`, `birth_date`, `death_date`, `image_link`) VALUES
(1, "Depp", "Johnny", 188, "1963-06-09", NULL, "http://ia.media-imdb.com/images/M/MV5BMTM0ODU5Nzk2OV5BMl5BanBnXkFtZTcwMzI2ODgyNQ@@._V1_UY317_CR4,0,214,317_AL_.jpg"),
(2, "Hanks", "Tom", 188, "1956-07-09", NULL, "http://artcreationforever.com/images/tom-hanks/tom-hanks-04.jpg"),
(3, "Clooney", "George", 188, "1961-05-06", NULL, "https://upload.wikimedia.org/wikipedia/commons/9/92/George_Clooney-4_The_Men_Who_Stare_at_Goats_TIFF09_(cropped).jpg"),
(4, "Johansson", "Scarlett", 188, "1984-11-11", NULL, "http://ia.media-imdb.com/images/M/MV5BMTM3OTUwMDYwNl5BMl5BanBnXkFtZTcwNTUyNzc3Nw@@._V1_UY317_CR23,0,214,317_AL_.jpg"),
(5, "Hathaway", "Anne", 188, "1982-11-12", NULL, "http://cdni.condenast.co.uk/592x888/a_c/anne-hathaway-glamour-12jan15-getty_592x888.jpg"),
(6, "Lawrence", "Jennifer", 188, "1990-08-15", NULL, "https://upload.wikimedia.org/wikipedia/commons/0/0b/Jennifer_Lawrence_SDCC_2015_X-Men.jpg"),
(7, "Freeman", "Morgan", 188, "1937-06-01", NULL, "http://cdn.hbowatch.com/wp-content/uploads/2015/06/People_MorganFreeman.jpg"),
(8, "Seinfeld", "Jerry", 188, "1954-04-29", NULL, "https://upload.wikimedia.org/wikipedia/en/f/f6/Jerry_Seinfeld.jpg"),
(9, "Louis-Dreyfus", "Julia", 188, "1961-01-13", NULL, "http://www3.pictures.stylebistro.com/gi/Julia%20Louis%20Dreyfus%20Long%20Hairstyles%20Long%20Curls%20vFqUizT5oAwl.jpg"),
(10, "Alexander", "Jason", 188, "1959-09-23", NULL, "http://www.nndb.com/people/714/000024642/jason-alexander-sized.jpg"),
(11, "Richards", "Michael", 188, "1949-07-24", NULL, "http://img.poptower.com/pic-13069/michael-richards.jpg?d=600"),
(12, "Robbins", "Tim", 188, "1958-10-16", NULL, "http://www.hotstarz.info/wp-content/uploads/2015/08/1_Tim_Robbins.jpg"),
(13, "Cranston", "Bryan", 188, "1956-03-07", NULL, "https://upload.wikimedia.org/wikipedia/commons/a/a0/Bryan_Cranston_by_Gage_Skidmore_2.jpg"),
(14, "Paul", "Aaron", 188, "1979-08-27", NULL, "https://upload.wikimedia.org/wikipedia/commons/4/4d/Aaron_Paul_(8023002250).jpg"); ';        
        
        
        
        /* Profile Table Definition */

        $sql .= 'CREATE TABLE IF NOT EXISTS `profile` (
  `profile_id` int(4) NOT NULL AUTO_INCREMENT COMMENT "Profile Id",
  `profile_desc` varchar(50) COLLATE utf8_spanish_ci NOT NULL COMMENT "Profile Description",
  PRIMARY KEY (`profile_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4 ; ';

        /* Profile Table Data */

        $sql .= 'INSERT INTO `profile` (`profile_id`, `profile_desc`) VALUES
(1, "admin"),
(2, "user");';

        /* User Table Definition */

        $sql .= 'CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT COMMENT "User Id",
  `username` varchar(20) NOT NULL COMMENT "Username",
  `password` char(32) COLLATE utf8_spanish_ci NOT NULL COMMENT "Password",
  `lastname` varchar(150) NOT NULL COMMENT "Last Name",
  `firstname` varchar(150) NOT NULL COMMENT "First Name",
  `creation_date` date NOT NULL COMMENT "User Creation Date",
  `enabled_flag` int(1) NOT NULL COMMENT "Active User Flag (1:Enabled/0: Disabled)",
  `modification_date` date DEFAULT NULL COMMENT "User Modification Date",
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ; ';

        /* User Table Data */

        $sql .= 'INSERT INTO `user` (`username`, `password`, `lastname`, `firstname`, `creation_date`, `enabled_flag`) VALUES
                ("'. $dbUser . '", "' . $dbPass . '", "' . $lastname . '", "' . $firstname . '", NOW(), 1); ';


        /* User Profile Table Definition */

        $sql .= 'CREATE TABLE IF NOT EXISTS `user_profile` (
  `user_id` int(10) NOT NULL COMMENT "User Id",
  `profile_id` bigint(2) NOT NULL COMMENT "Profile Id"
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci; ';

        /* User Profile Table Data, using last insert id (MYSQL Function) from previous insert in User Table */

        $sql .= 'INSERT INTO `user_profile` (`user_id`, `profile_id`) VALUES
                (LAST_INSERT_ID(), 1); ';
        
        /* Once admin user and profile are successfully inserted, continiue with misc users */
        
        $sql .= 'INSERT INTO `user` (`username`, `password`, `lastname`, `firstname`, `creation_date`, `enabled_flag`) VALUES
                ("tito", "123", "Roberto", "Tagarna", NOW(), 1),
                ("julio", "123", "Julio", "Gonzalez", NOW(), 1),
                ("lalo", "123", "Lalo", "Landa", NOW(), 1); ';                
        
        $sql .= 'INSERT INTO `user_profile` (`user_id`, `profile_id`) VALUES
                (2, 2),
                (3, 2),
                (4, 2); ';
        
        /* Continue normal script Execution */        
        
        /* User Rating Table Definition */
        
    $sql .= 'CREATE TABLE IF NOT EXISTS `user_rating` (
    `rating_id` int(10) NOT NULL AUTO_INCREMENT COMMENT "Rating Id",
    `user_id` int(10) NOT NULL COMMENT "User Id",
    `movie_series_id` int(15) NOT NULL COMMENT "Movie/Series Id",
    `rating_score` int(1) NOT NULL COMMENT "Movie/Series Rating",
    PRIMARY KEY (`rating_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4; ';

        /* User Rating Table Data */
    
    $sql .= 'INSERT INTO `user_rating` (`rating_id`, `user_id`, `movie_series_id`, `rating_score`) VALUES
            (1, 1, 3, 5),
            (2, 1, 2, 1),
            (3, 1, 1, 3) ; ';

        /* User Message Table Definition */

        $sql .= 'CREATE TABLE IF NOT EXISTS `user_message` (
  `message_id` int(20) NOT NULL AUTO_INCREMENT COMMENT "Message Id",
  `message_date` date NOT NULL COMMENT "Message Date",
  `sender_id` int(10) NOT NULL COMMENT "Message Sender Id",
  `recipient_id` int(10) NOT NULL COMMENT "Message Recipient Id",
  `msg_text` varchar(3000) COLLATE utf8_spanish_ci NOT NULL COMMENT "Text and/or Links",
  `movie_series_id` int(15) DEFAULT NULL COMMENT "Movie/Series Id",
  `read_flag` int(1) NOT NULL COMMENT "Read Message Flag (0: Unread/1: Read)",
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1; ';

        
        /* EXECUTE SQL STATEMENT TO CREATE DATABASE IN PHPMYADMIN */

        /* log in with root privileges to create new user and grant privileges */

        $servername = "localhost";
        $username = "root";
        $password = "";
        
// Create connection
        $conn = new mysqli($servername, $username, $password);
// Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        //die('<pre>'.print_r($sql, true).'</pre>');
        if ($conn->multi_query($sql)) {
            do {
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());


            echo '<script language="javascript">';
            echo 'alert("DATABASE SCRIPT EXECUTION WAS SUCCESFUL!")';
            echo '</script>';

            header('location: index.php');
        } else {
            echo '<script language="javascript">';
            echo 'alert("THERE WAS AN ERROR CREATING YOUR DATABASE: ' . $conn->error . '")';
            echo '</script>';

            if (!unlink($configDBFile)) {
                echo ("Error deleting $configDBFile");
            } else {
                echo ("Deleted $configDBFile");
            }

            if (!unlink($configDAOFile)) {
                echo ("Error deleting $configDAOFile");
            } else {
                echo ("Deleted $configDAOFile");
            }
        }

        $conn->close();
    }
}
require_once ("../views/setup.php");
?>