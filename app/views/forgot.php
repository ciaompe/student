<?php include 'header-one.php' ?>

<body class="lost-password">
    
    <!-- Main page container -->
    <section class="container" role="main">

      <div class="row">
        <div class="col-sm-offset-3 col-sm-6">
          <!-- Header -->
          <h1>Forgot your password ?</h1>
          <!-- /Header -->
          <!-- Form -->
          <form method="post" action="#" id="forgot_form" novalidate>
            <p>Please enter your Email address to Reset the password</p>
            <div class="form-group">
              <input class="form-control" name="email" type="email" placeholder="Enter your email" required>
            </div>
            <button id="submit" class="btn btn-primary" type="submit">Submit</button>

             <div id="errors_bg"></div>
          </form>
          <!-- /Form -->
        </div>
      </div>
      
    </section>
    <!-- /Main page container -->

<script type="text/javascript">

    $(function() {

    $("input,select,textarea").jqBootstrapValidation({

      preventSubmit: true,
      submitSuccess: function($form, e) {
          e.preventDefault();
          $("#errors_bg").hide().html("<p>Please wait</p>").fadeIn('fast');

            $.ajax({
                type:"post",
                    url:$("#forgot_form").attr("action"),
                    data:$("#forgot_form").serialize(),
                    success:function(data){
                      $("#errors_bg").hide().html(data).fadeIn('fast');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                      jQuery("#errors_bg").hide().html("<p>Server Error Occured</p>").fadeIn('fast');
                }
            });
      }

    });

  });
               
</script>

<?php include 'footer-one.php'; ?>