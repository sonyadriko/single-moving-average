<?php 
    include 'proses-login.php';
    // session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

<!-- Template style -->
<link rel="stylesheet" href="dist/css/style.css">
<link rel="stylesheet" href="pages/et-line-font/et-line-font.css">
<link rel="stylesheet" href="pages/font-awesome/css/font-awesome.min.css">
<link type="text/css" rel="stylesheet" href="dist/weather/weather-icons.min.css">
<link type="text/css" rel="stylesheet" href="dist/weather/weather-icons-wind.min.css">
</head>

<body class="body-bg-color">
<div class="wrapper">
        <div class="form-body">
            <form action="login.php" class="col-form" method="post" onsubmit="return validasi()">
                <header>Login Form</header>
                <fieldset>
                    <section>
                        <div class="form-group has-feedback">
                            <label class="control-label">E-mail</label>
                            <input class="form-control" placeholder="E-mail" id="username" name="username" type="text">
                            <span class="fa fa-envelope form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </section>
                    <section>
                        <div class="form-group has-feedback">
                            <label class="control-label">Password</label>
                            <input class="form-control" placeholder="Password" id="password" name="password"
                                type="password">
                            <span class="fa fa-lock form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </section>
                </fieldset>
                <footer class="text-right">
                    <button type="submit" class="btn btn-info pull-right" name="login">Login</button>
                </footer>
            </form>
        </div>
    </div>
<!-- wrapper --> 

<script type="text/javascript">
	function validasi() {
		var username = document.getElementById("username").value;
		var password = document.getElementById("password").value;		
		if (username != "" && password!="") {
			return true;
		}else{
			alert('Username dan Password harus di isi !');
			return false;
		}
	}
</script>
<!-- jQuery --> 
<script src="dist/js/jquery.min.js"></script> 
<script src="bootstrap/js/bootstrap.min.js"></script> 
<script src="dist/js/ovio.js"></script>
</body>
 
</html>