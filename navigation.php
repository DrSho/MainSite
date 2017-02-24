
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">
                <img id="profile-photo" src="images/clinical.png" alt="Dr. Show" height="60"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="<?php
                if ($curLoc == "index")
                    echo 'active';
                ?>"><a href="index.php">Home</a></li>

                <li class="<?php if ($curLoc == "about")
                    echo 'active';
                ?>"><a href="about.php">About</a></li>
                <li class="<?php
                if ($curLoc == "contact")
                    echo 'active';
                ?>"><a href="contact.php">Contact</a></li>
                <?php

                if ((isset($_SESSION['user']))) { ?>
                    <li class="<?php
                    if ($curLoc == "dashboard")
                        echo 'active';
                    ?>"><a href="dashboard.php">Dashboard</a></li>
                <?php } else { ?>
                    <li class="<?php
                    if ($curLoc == "register")
                        echo 'active';

                    ?>"><a href="register.php">Register</a></li>
                <?php }  ?>

            </ul>
            <ul class="nav navbar-nav navbar-right">

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">
                        <span class="glyphicon glyphicon-user"></span>&nbsp;Hi <?php echo $userName; ?>&nbsp;<span
                            class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php
                        if ((isset($_SESSION['user']))) {
                            ?>
                            <li><a href="profile.php"><span class="glyphicon glyphicon-edit"></span> My Account</a></li>

                            <li><a href="logout.php?logout=1"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign
                                    Out</a></li>
                        <?php } else { ?>

                            <li><a href="login.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign In</a>
                            </li>

                        <?php } ?>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
