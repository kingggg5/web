<?php

unset($_SESSION['email']);
unset($_SESSION['CustomerID']);
unset($_SESSION['TimePlayed']);
unset($_SESSION['GamePoints']);
unset($_SESSION['GameDollars']);
unset($_SESSION['lastgamedate']);
unset($_SESSION['IsDeveloper']);
unset($_SESSION['case_num']);
rdr("index.php");
