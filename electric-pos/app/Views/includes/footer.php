<footer class="main-footer" id="myFooter">
  <strong style="color: #5b6168;">Copyright &copy; <?php echo date('Y'); ?> <a href="<?php echo base_url(); ?>" style="color: #005dc3;"><?php echo $companyAddress; ?></a>.</strong> <span class="translateMe" style="color: #5b6168;">All rights reserved</span>.
  <div class="float-right d-none d-sm-inline-block">
    <b style="color: #5b6168;"><?php echo $companyProject; ?></b> | <b style="color: #5b6168;">Version</b> <span style="color: #5b6168;">3.1.0-rc | Powered By:</span> <span><b style="color: #5b6168;"><a href="https://www.usp.org" target="_blank" style="color: #005dc3;">USP (usp.org)</a></b><span>
  </div>
</footer>

</div>
<!-- ./wrapper -->

<script type="text/javascript">
  $(function () {
      //Initialize Select2 Elements
      $('.select2').select2()
      //Datemask dd/mm/yyyy
      $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
      //Datemask2 mm/dd/yyyy
      $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
      //Money Euro
      $('[data-mask]').inputmask()

      //Date range picker
      $('#reservation').daterangepicker()
      //Date range picker with time picker
      $('#reservationtime').daterangepicker({ timePicker: true, timePickerIncrement: 30, locale: { format: 'MM/DD/YYYY hh:mm A' }})
      //Custom Date range picker
      $('#dateRange').daterangepicker({ timePicker: false, timePickerIncrement: 30, locale: { format: 'DD-MMM-YY' }})
      //Date range as a button
      $('#daterange-btn').daterangepicker(
        {
          ranges   : {
            'Today'       : [moment(), moment()],
            'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month'  : [moment().startOf('month'), moment().endOf('month')],
            'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate  : moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
        }
      )

      //Date picker
      $('.datepicker').datepicker({
        autoclose: true
      })

      //Colorpicker
      $('.my-colorpicker1').colorpicker()
      //color picker with addon
      $('.my-colorpicker2').colorpicker()

      //Timepicker
      $('.timepicker').timepicker({
        showInputs: false
      })

      //iCheckbox
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
      });
      
      //iCheck for checkbox and radio inputs
      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass   : 'iradio_minimal-blue'
      })
      //Red color scheme for iCheck
      $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass   : 'iradio_minimal-red'
      })
      //Flat red color scheme for iCheck
      $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass   : 'iradio_flat-green'
      })

      /* BOOTSTRAP SLIDER */
      $('.slider').slider()
  })

  var windowURL = window.location.href;
  pageURL = windowURL.substring(0, windowURL.lastIndexOf('/'));
  var x= $('a[href="'+pageURL+'"]');
      x.addClass('active');
      x.parent().addClass('active');
  var y= $('a[href="'+windowURL+'"]');
      y.addClass('active');
      y.parent().addClass('active');
</script>
<!-- Notification -->
<!-- <script src="<?php echo base_url(); ?>assets/dist/vendors/notification/notification.js"></script> -->
<script>
  // $(function () {
  //   //Add text editor
  //   $('#compose-textarea').summernote()
  // })
</script>
<script>
  // $(function () {
  //   $("#example1").DataTable({
  //     "responsive": true, "lengthChange": false, "autoWidth": false,
  //     "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
  //   }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  //   $('#example2').DataTable({
  //     "paging": true,
  //     "lengthChange": false,
  //     "searching": false,
  //     "ordering": true,
  //     "info": true,
  //     "autoWidth": false,
  //     "responsive": true,
  //   });
  // });
</script>
<script>
  // $(function () {
  //   $("#jsGrid1").jsGrid({
  //       height: "100%",
  //       width: "100%",

  //       sorting: true,
  //       paging: true,

  //       data: db.clients,

  //       fields: [
  //           { name: "Name", type: "text", width: 150 },
  //           { name: "Age", type: "number", width: 50 },
  //           { name: "Address", type: "text", width: 200 },
  //           { name: "Country", type: "select", items: db.countries, valueField: "Id", textField: "Name" },
  //           { name: "Married", type: "checkbox", title: "Is Married" }
  //       ]
  //   });
  // });
</script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script type="text/javascript">
  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
    //$('[title]').tooltip()
  })
</script>
<script type="text/javascript">
  $('.required').each(function(i, obj) {
      //$(this).parent().find('label').prepend('<i class="fa fa-asterisk text-danger"></i> ');
      $(this).parent().parent().find('label').prepend('<i class="fa fa-asterisk text-danger"></i> ');
  });
</script>
<script type="text/javascript">
  $(document).ready(function(){
      $('#firstElem1').focus();
    $('input[type=file]').each(function(index, field){
      filenames = $(this).attr('name');
      if(filenames.substr(0, 11) == 'tabledetail' || filenames == 'files'){
        return;
      }
      $('#filenames').append(filenames+'~');
    });
    $('#myForm :input').each(function(index, field){
      fieldnames = $(this).attr('name');
      if(typeof fieldnames === 'undefined' || fieldnames.substr(-1, 1) == ']' || fieldnames == 'files'){
        return;
      }
      $('#fieldnames').append(fieldnames+'~');
    });
    $('table').each(function(index, field){
      tablenames = $(this).attr('id');
      if(tablenames.substr(0, 11) !== 'tabledetail'){
        return;
      }
      $('#tablenames').append(tablenames+'~');
    });
  });
</script>
<script>
  document.addEventListener('keydown', function (event) {
    if (event.key === 'ArrowRight' && !isTextInput(event.target)) {
      event.preventDefault();
      focusNextElement();
    }
  });

  function focusNextElement() {
    const focusableElements = Array.from(document.querySelectorAll('a, button, input, select, textarea, [tabindex]')).filter(element => !element.matches('ul, li'));
    const currentIndex = focusableElements.indexOf(document.activeElement);
    const nextIndex = (currentIndex + 1) % focusableElements.length;
    focusableElements[nextIndex].focus();
  }

  function isTextInput(element) {
    return ['INPUT', 'TEXTAREA', 'SELECT'].includes(element.tagName);
  }
</script>

<script>
  document.addEventListener('keydown', function (event) {
// Check if the left arrow key is pressed and not in an input, textarea, or select field
    if (event.key === 'ArrowLeft' && !isTextInput(event.target)) {
      // Prevent the default left arrow key behavior
      event.preventDefault();

      // Find the previous focusable element
      var focusableElements = document.querySelectorAll('a, button, input, select, textarea, [tabindex]');
      var currentIndex = Array.from(focusableElements).indexOf(document.activeElement);
      var previousIndex = (currentIndex - 1 + focusableElements.length) % focusableElements.length;
      focusableElements[previousIndex].focus();
    }
  });

  // Helper function to check if the element is an input, textarea, or select
  function isTextInput(element) {
    return ['INPUT', 'TEXTAREA', 'SELECT'].includes(element.tagName);
}
</script>
<script type="text/javascript">
  function multilingual(language, searchText){
    var translatedData;
    $.ajax({
      url: '<?php echo base_url(); ?>uploads/company/RBI/templates/languages/'+language+'.html',
      dataType:   'JSON',
      async: false,
      success: function(data){
        $.each(data, function(key, value){
          if(key == searchText.trim()){
            translatedData = value;
            return;
          }
        });
      }
    });
    if(translatedData){
      return translatedData;
    }
    else{
      return '#####';
    }
  }

</script>
<?php $myLanguage = $_SESSION['myLanguage'] ?? 'english'; ?>
<script type="text/javascript">
  $(document).ready(function() {
    if('<?php echo $myLanguage; ?>' !== 'english'){
      $(".translateMe").each(function() {
        var text = $(this).text();
        text = text.replace(text, multilingual('<?php echo $myLanguage; ?>', text));
        $(this).text(text);
      });
    }
  });
</script>

</body>
</html>

