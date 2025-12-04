    
<!-- <footer class="footer mt-auto">
            <div class="copyright bg-white">
              <p>
                &copy; <span id="copy-year">2019</span> Copyright Sleek Dashboard Bootstrap Template by
                <a
                  class="text-primary"
                  href="http://www.iamabdus.com/"
                  target="_blank"
                  >Abdus</a
                >.
              </p>
            </div>
            <script>
                var d = new Date();
                var year = d.getFullYear();
                document.getElementById("copy-year").innerHTML = year;
            </script>
          </footer> -->

      </div>
    </div>




  </body>
</html>


<script src="<?php echo base_url()?>assets/template/dist/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url()?>assets/template/dist/assets/plugins/toaster/toastr.min.js"></script>
<script src="<?php echo base_url()?>assets/template/dist/assets/plugins/slimscrollbar/jquery.slimscroll.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.bundle.js"></script> -->
<script src="<?php echo base_url()?>assets/template/dist/assets/plugins/ladda/spin.min.js"></script>
<script src="<?php echo base_url()?>assets/template/dist/assets/plugins/ladda/ladda.min.js"></script>
<script src="<?php echo base_url()?>assets/template/dist/assets/plugins/jquery-mask-input/jquery.mask.min.js"></script>
<script src="<?php echo base_url()?>assets/template/dist/assets/plugins/select2/js/select2.min.js"></script>
<script src="<?php echo base_url()?>assets/template/dist/assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.js"></script>
<script src="<?php echo base_url()?>assets/template/dist/assets/plugins/jvectormap/jquery-jvectormap-world-mill.js"></script>
<script src="<?php echo base_url()?>assets/template/dist/assets/plugins/daterangepicker/moment.min.js"></script>
<script src="<?php echo base_url()?>assets/template/dist/assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- <script src="<?php echo base_url()?>assets/template/dist/assets/plugins/jekyll-search.min.js"></script> -->
<script src="<?php echo base_url()?>assets/template/dist/assets/js/sleek.js"></script>
<script src="<?php echo base_url()?>assets/template/dist/assets/js/chart.js"></script>
<script src="<?php echo base_url()?>assets/template/dist/assets/js/date-range.js"></script>
<script src="<?php echo base_url()?>assets/template/dist/assets/js/map.js"></script>
<script src="<?php echo base_url()?>assets/template/dist/assets/js/custom.js"></script>
 <!-- Data Table
        ============================================ -->
    <script src="<?php echo base_url()?>assets/datatables/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.min.js')?>"></script>
    <script src="<?php echo base_url()?>assets/datatables/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url()?>assets/datatables/js/jszip.min.js"></script>
    <script src="<?php echo base_url()?>assets/datatables/js/pdfmake.min.js"></script>
    <script src="<?php echo base_url()?>assets/datatables/js/vfs_fonts.js"></script>
    <script src="<?php echo base_url()?>assets/datatables/js/datatables.min.js"></script>
    <script src="<?php echo base_url()?>assets/datatables/js/buttons.colVis.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>
    <!-- Repeater -->
<!-- <script src="<?php echo base_url()?>assets/template/dist/assets/js/repeater.js"></script> -->


<script type="text/javascript">
     $(document).ready(function() {
      $('.money').mask('000.000.000.000.000,00', {reverse: true});
        var menu = document.getElementById("active_menu").value;
        //alert(menu);
        // dynamic link active
        if(menu == 'Question Master'){
            $("#link0").attr("class", " active expand");
        }else if(menu == 'Questioner Karyawan'){
            $("#link1").attr("class", " active expand");
            $("#karyawan").attr("class", "show");
        }else if(menu == 'Questioner Tamu'){
            $("#link2").attr("class", " active expand");
            $("#tamu").attr("class", "show");
        }else if(menu == 'Questioner RUPS'){
            $("#link3").attr("class", " active expand");
            $("#rups").attr("class", "show");
        }else if(menu == 'User Login'){
            $("#link4").attr("class", " active expand");
        }else if(menu == 'User Questioners'){
            $("#link5").attr("class", " active expand");
        }
        else{
            $("#link0").attr("class", " active expand");
        }
     });
</script>

