<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
            
            <!-- Bootstrap 3.3.7 -->
            <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
            
            <!-- Font Awesome -->
            <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
            
            <!-- Ionicons -->
            <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
            
            <!-- Theme style -->
            <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
            
            <!-- iCheck -->
            <link rel="stylesheet" href="plugins/iCheck/square/blue.css">        

            <script src="bower_components/jquery/dist/jquery.min.js"></script>   
            <link rel="stylesheet" href="dist/css/app.css">
            <script src="dist/js/sweetalert.min.js"></script>


            <style>
                input[type=number]::-webkit-inner-spin-button,
                input[type=number]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                margin: 0;
                }

                .login-page,
                .register-page {
                background: #fff
                }
            </style>


            <script src="dist/js/angular.min.js"></script>

            <link rel="stylesheet"
                href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

                <div class="login-box-body">
    
                    <form action="" method="post">
                        <div class="form-group has-feedback">
                            <input type="text" name="email" id="email" class="form-control" placeholder="E-Mail" autocomplete="off"
                            required="">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    
                    <div class="form-group has-feedback">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block btn-flat" name="auth-login" value="auth-Login"
                        data-loading-text="<i class="fa fa-spinner fa-spin"></i>Login</button>
                    </div>