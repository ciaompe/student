<?php include 'header.php'; include 'student_settings_nav.php';?>
<br>

<div class="row">

<form action="#" method="post" id="updateStu_form" novalidate>

    <article class="col-sm-6">

      <div class="data-block shadow-block">
          <header>  
            <h2>Student Details</h2>
          </header>

          <section>
              <div class="form-group">
                  <label class="control-label">Student Name:</label>
                  <input type="text" name="name" value="<?php echo $data['students'][0]->studentName;?>" class="form-control" required>
              </div>

              <div class="form-group">
                <label class="control-label">Student NIC:</label>
                <input type="text" name="studentNic" value="<?php echo $data['students'][0]->studentNic;?>" class="form-control" required data-validation-regex-regex="[0-9]{9}[vVxX]">
              </div>

              <div class="form-group">
                <label class="control-label">Student Address:</label>
                <textarea id="inputTextarea" name="address" class="form-control" rows="3" required><?php echo $data['students'][0]->studentAddress;?></textarea>
              </div>

              <?php 

              if ($data['students'][0]->studentGender == "M") {
                  echo '<div class="form-group">
                <div id="radio_buttons" class="radio styled-radio">
                  <label><input type="radio" name="gender" id="optionsRadios1" value="M" data-label="Male" checked required></label>
                  <label><input type="radio" name="gender" id="optionsRadios2" value="F" data-label="Female" required></label>
                </div>
              </div>';
              }  else if ($data['students'][0]->studentGender == "F") {
                  echo '<div class="form-group">
                <div id="radio_buttons" class="radio styled-radio">
                  <label><input type="radio" name="gender" id="optionsRadios1" value="M" data-label="Male" required></label>
                  <label><input type="radio" name="gender" id="optionsRadios2" value="F" data-label="Female" checked required></label>
                </div>
              </div>';
              } 

              ?>

              <div class="form-group">
                <label class="control-label">Student Date of Birth:</label>
                <div class="input-group">
                  <span class="input-group-addon"><i class="elusive icon-calendar"></i></span>
                  <input type="text" name="dob" class="datepicker form-control" value="<?php echo $data['students'][0]->studentDob; ?>" required>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label">Select Batch:</label>
                <select id="mybatches" name="batch" id="select" class="form-control" required>
                  <?php 

                    foreach ($data['batches'] as $batch) {
                      
                      if ($data['students'][0]->batchId == $batch->batch_id) {
                        echo '<option value="'.$batch->batch_id.'" selected>Batch '.$batch->batch_number.'</option>';
                      }else {
                       echo '<option value="'.$batch->batch_id.'">Batch '.$batch->batch_number.'</option>';
                     }
                    }
                   ?>
                </select>
              </div>

          </section>
      </div>
  </article>


<article class="col-sm-6">

        <div class="data-block shadow-block">

          <header>
            <h2>Student Contact Information</h2>
          </header>

          <section>
            <div class="form-group">
                <label class="control-label">Student Email:</label>
                <input type="email" name="email" value="<?php echo $data['students'][0]->studentEmail;?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="control-label">Student Telephone Number:</label>
                <input type="number" name="telephone" value="<?php echo $data['students'][0]->studentTelhome;?>" class="form-control" required maxlength="10" minlength="10">
            </div>

            <div class="form-group">
                <label class="control-label">Student Mobile Number:</label>
                <input type="number" name="mobile" value="<?php echo $data['students'][0]->studentTelmobile;?>" class="form-control" required maxlength="10" minlength="10">
            </div>
          </section>
        </div>

        <div id="errors_bg_for" class="pull-left"></div>
        <button id="submit" type="submit" class="pull-right btn btn-primary" disabled="true">Update</button>

    </article>
  </form>
</div>

 <script type="text/javascript">

  $(function() {

    $("input,select,textarea").change( function() {
      $('#submit').attr('disabled', false);
    });

    $("input,select,textarea").jqBootstrapValidation({

      preventSubmit: true,
      submitSuccess: function($form, e) {
          e.preventDefault();
          $("#errors_bg_for").hide().html("<p>Please wait</p>").fadeIn('fast');
          $.ajax({
              type:"POST",
              url:"student?action=update&stu=<?php echo $data['students'][0]->studentId;?>&user=<?php echo $data['students'][0]->user_id;?>&myemail=<?php echo $data['students'][0]->studentEmail;?>&mynic=<?php echo $data['students'][0]->studentNic;?>",
              data:$("#updateStu_form").serialize(),
              success:function(data){
                $("#errors_bg_for").hide().html(data).fadeIn('fast');
              },
              error: function(XMLHttpRequest, textStatus, errorThrown) {
                jQuery("#errors_bg_for").hide().html("<p>Server Error Occured</p>").fadeIn('fast');
              }
          });
      }

    });

  });
                              
</script>


<?php include 'footer.php'; ?>