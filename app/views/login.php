<?php include 'header-one.php' ?>

  <body class="login">
    
    <!-- Main page container -->
    <section class="container" role="main">

      <div class="login-logo">
        <img src="assets/img/login_logo.png" alt="mysara-login-logo">
      </div>

      <!-- Login form -->
      <form method="post" action="#" id="login_form" novalidate>
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-addon"><span class="elusive icon-user"></span></span>
            <input class="form-control" type="text" placeholder="Username or Email" name="username" id="username" required value="kalum2013">
          </div>
        </div>
        <div class="form-group">
          <div class="input-group">
            <span class="input-group-addon"><span class="elusive icon-key"></span></span>
            <input class="form-control" type="password" placeholder="Password" name="password" id="password" required value="test123">
          </div>
        </div>

        <button id="submit" class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
        <a class="lost-password" href="forgot">Lost your password?</a>

        <div id="errors_bg"></div>
      </form>
      <!-- /Login form -->
           
  </section>

  <!-- /Main page container -->

  <script type="text/javascript">
    $(function() { //jquery anonymous function
      //jquery bootstrap validation library is used for form validations
      $("input,select,textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitSuccess: function($form, e) {
            e.preventDefault();
            //before sending the ajax request, display "please wait" message to the user
            $("#errors_bg").hide().html("<p>Please wait</p>").fadeIn('fast');
              //sending an ajax request to the server
              $.ajax({
                  type:"post",
                      url:$("#login_form").attr("action"),
                      data:$("#login_form").serialize(),
                      success:function(data){
                      $("#errors_bg").hide().html(data).fadeIn('fast');
                      },
                      error: function(XMLHttpRequest, textStatus, errorThrown) {
                      jQuery("#errors_bg")
                      .hide()
                      .html("<p>Server Error Occured</p>")
                      .fadeIn('fast');
                  }
              });
        }
      });
    });                 
  </script>


<?php include 'footer-one.php' ?>