<?php include 'header-one.php' ?>

<body class="lost-password">
    
    <!-- Main page container -->
    <section class="container" role="main">

      <div class="row">
        <div class="col-sm-offset-3 col-sm-6">
          <!-- Header -->
          <h1>Reset your password</h1>
          <!-- /Header -->
          <!-- Form -->
          <form method="post" action="#" novalidate id="reset_form">
            <div class="form-group">
              <label class="control-label">Password</label>
              <input class="form-control" type="password" name="password" required>
            </div>
            <div class="form-group">
              <label class="control-label">Confirm Password</label>
              <input class="form-control" type="password" name="re-password" data-validation-match-match="password" required>
              <p class="help-block"></p>
            </div>

            <button id="submit" class="btn btn-primary" type="submit">Submit</button>

            <div id="errors_bg"></div>
          </form>
          <!-- /Form -->
        </div>
      </div>
      
    </section>
    <!-- /Main page container -->

<?php include 'footer-one.php' ?>


<script type="text/javascript">


  $(function() {

    $("input,select,textarea").jqBootstrapValidation({

      preventSubmit: true,
      submitSuccess: function($form, e) {
          e.preventDefault();
          $("#errors_bg").hide().html("<p>Please wait</p>").fadeIn('fast');

            $.ajax({
                type:"post",
                    url:$("#reset_form").attr("action"),
                    data:$("#reset_form").serialize(),
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