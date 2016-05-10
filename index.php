<?php 
    session_start();
    $user = $_SESSION['user'];
    if(!$user !="")
    {
        header ("Location: login.html");
    }
 ?>
<!DOCTYPE html>
<html ng-app="app">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Untitled</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="fonts/font-awesome.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/Pretty-Header.css">
    <link rel="stylesheet" href="css/Pretty-Footer.css">
    <link rel="stylesheet" href="css/Hero-Technology.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/toaster.min.css">
    <script src="js/angular.min.js"></script>
    <script src="js/ladda.min.js"></script>
    <script src="js/angular-ladda.js"></script>
    <script src="js/spin.js"></script>
    <script src="js/angular-spinner.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/angular-animate.js"></script>
    <script src="js/toaster.min.js"></script>
    <script src="js/angular-ui-router.js"></script>
    <script src="js/angular-animate.js"></script>
    <script src="js/toaster.min.js"></script>
    <script src="js/jcs-auto-validate.js"></script>
    <script src="js/main.js"></script>

</head>

<body>
    <nav class="navbar navbar-default custom-header" ng-controller="ViewController">
        <div class="container-fluid">
            <div class="navbar-header"><a class="navbar-brand navbar-link" href="#">Vera Borcuta</a>
                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav links">
                    <li role="presentation"><a ui-sref="users">Users</a></li>
                    <li role="presentation"><a ui-sref="beautiful">Why am i the most beautiful? </a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle pointer" data-toggle="dropdown" aria-expanded="false" > <span class="caret"></span><img ng-src="{{userImg}}" class="dropdown-image"></a>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li role="presentation"><a ui-sref="amazing">Why you're Amazing </a></li>
                            <li role="presentation"><a ui-sref="beautiful">Why you're Most Beautiful </a></li>
                            <li role="presentation" class="active"><a href="logout.php">Logout </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <toaster-container></toaster-container>
            <div ui-view></div>
        </div>
    </div>
   
    <footer>
        <div class="row">
            <div class="col-md-4 col-sm-6 footer-navigation">
                <h3><a href="#"><span>Vera Borcuta</span></a></h3>
                <p class="links"><a href="#">Home</a><strong> · </strong><a ui-sref="amazing">I am Amazing</a><strong> · </strong><a ui-sref="beautiful" >I am Beautiful </a><strong> · </strong></p>
                <p class="company-name">Vera Borcuta © 2016 </p>
            </div>
            <div class="col-md-4 col-sm-6 footer-contacts">
                <div><span class="fa fa-map-marker footer-contacts-icon"> </span>
                    <p><span class="new-line-span"></span> Chisinau, Moldova</p>
                </div>
                <div><i class="fa fa-envelope footer-contacts-icon"></i>
                    <p> <a href="#" target="_blank">verina@mail.ru</a></p>
                </div>
            </div>
            <div class="clearfix visible-sm-block"></div>
            <div class="col-md-4 footer-about">
                <h4>About the most amazing girl ever</h4>
                <p> Loves the classic 80's and 90's movies, animals, want to be a movie director and well
                    she's sexier than sarah connor!.
                </p>
                <div class="social-links social-icons"><a href="https://www.facebook.com/profile.php?id=1315176419"><i class="fa fa-facebook"></i></a><a href="#"><i class="fa fa-twitter"></i></a><a href="#"><i class="fa fa-linkedin"></i></a></div>
            </div>
        </div>
    </footer>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>