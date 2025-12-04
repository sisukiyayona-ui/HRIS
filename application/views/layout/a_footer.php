 <footer>
   <div class="pull-right">
     Gentelella - Bootstrap Admin Template by
   </div>
   <div class="clearfix"></div>
 </footer>
 <!-- /footer content -->
 </div>
 </div>



 <!-- Bootstrap -->
 <script src="<?php echo base_url() ?>assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
 <!-- FastClick -->
 <script src="<?php echo base_url() ?>assets/vendors/fastclick/lib/fastclick.js"></script>
 <!-- NProgress -->
 <script src="<?php echo base_url() ?>assets/vendors/nprogress/nprogress.js"></script>
 <!-- Datatables -->
 <script src="<?php echo base_url() ?>assets/vendors/datatables.net-bs/js/10/jquery.dataTables.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/jszip/dist/jszip.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/datatables.net-buttons/js/buttons.colVis.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/pdfmake/build/vfs_fonts.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/pdfmake/build/pdfmake.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/datatables.net-bs/js/10/dataTables.fixedColumns.min.js"></script>
 <!-- <script src="<?php echo base_url() ?>assets/vendors/datatables-rowgroup-master/dataTables.rowsGroup.js"></script> -->
 <!-- validator -->
 <script src="<?php echo base_url() ?>assets/vendors/validator/validator.js"></script>
 <!-- bootstrap-datepicker -->
 <script src="<?php echo base_url() ?>assets/vendors/moment/min/moment.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
 <!-- bootstrap-datetimepicker -->
 <script src="<?php echo base_url() ?>assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
 <!-- bootstrap-select -->
 <script src="<?php echo base_url() ?>assets/vendors/bootstrap-select/js/bootstrap-select.js"></script>
 <!-- iCheck -->
 <script src="<?php echo base_url() ?>assets/vendors/iCheck/icheck.min.js"></script>
 <!-- Repeater -->
 <script src="<?php echo base_url() ?>assets/vendors/repeater/repeater.js"></script>
 <!-- jQuery Smart Wizard -->
 <script src="<?php echo base_url() ?>assets/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
 <!-- ECharts -->
 <script src="<?php echo base_url() ?>assets/vendors/echarts/dist/echarts.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/echarts/map/js/world.js"></script>
 <!-- Multi Select -->
 <script src="<?php echo base_url() ?>assets/vendors/multi-select/js/jquery.multi-select.js" type="text/javascript"></script>
 <script src="<?php echo base_url() ?>assets/vendors/quicksearch-master/jquery.quicksearch.js" type="text/javascript"></script>
 <!-- Custom Theme Scripts -->
 <script src="<?php echo base_url() ?>assets/build/js/custom.min.js"></script>
 <script src="<?php echo base_url() ?>assets/vendors/jquery_mask/jquery.mask.min.js"></script>

 <!-- <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script> -->
 <!-- <script type="text/javascript" src="<?php echo base_url() ?>assets/vendors/date-time/date_time.js"></script> -->
 </body>

 </html>



 <!-- ################################################### DATATABLE ############################################################# -->
 <script>
   $(document).ready(function() {
     $('#tr_hr').dataTable({
       // "responsive":true,
       "ordering": false,
       "paging": false,
       "scrollX": true,
       dom: 'Bfrtip',
       scrollY: "600px",
       scrollX: true,
       scrollCollapse: true,
       paging: false,
       buttons: [
         'copy', 'csv', 'excel', 'pdf', 'print'
       ]
     });

     $('#tr_hr2').dataTable({
       // "responsive":true,
       "ordering": false,
       "paging": false,
       "scrollX": true,
       dom: 'Bfrtip',
       scrollY: "600px",
       scrollX: true,
       scrollCollapse: true,
       paging: false,
       buttons: [
         'copy', 'csv', 'excel', 'pdf', 'print'
       ],
       'rowsGroup': [0]
     });

     $('#t_absen').dataTable({
       "responsive": false,
       "ordering": false,
       // "order": [[ 2, "desc" ]],
       "paging": true,
       "pageLength": 30,
       dom: 'Bfrtip',
       buttons: [
         'copy', 'csv', 'excel', 'pdf', 'print'
       ]
     });


     $('#t_kar').dataTable({
       "responsive": false,
       "paging": true,
       "bSort": false,
       "pageLength": 30,
       dom: 'Bfrtip',
       buttons: [
         'copy', 'csv', 'excel', 'pdf', 'print'
       ]
     });

     $('#t_kar2').dataTable({
       "responsive": true,
     });

     $('#t_rec').dataTable({
       "responsive": true,
       "order": [
         [5, "desc"]
       ],
       "paging": true,
       dom: 'Bfrtip',
       buttons: [
         'copy', 'csv', 'excel', 'pdf', 'print'
       ]
     });

     $('#t_default').dataTable({
       "responsive": true,
       "paging": true,
       "ordering": false,
       dom: 'Bfrtip',
       buttons: [
         'copy', 'csv', 'excel', 'pdf', 'print'
       ]
     });

     $('#t_default2').dataTable({
       "responsive": true,
       "paging": true,
       "ordering": true,
       dom: 'Bfrtip',
       buttons: [
         'copy', 'csv', 'excel', 'pdf', 'print'
       ]
     });

     $('#t_tahun').dataTable({
       "responsive": true,
       "paging": true,
       "pageLength": 12,
       "ordering": false,
       dom: 'Bfrtip',
       buttons: [
         'copy', 'csv', 'excel', 'pdf', 'print'
       ]
     });

     $('#t_budget').dataTable({
       "responsive": true,
       "paging": true,
       "pageLength": 12,
       "order": [
         [1, "desc"]
       ],
       dom: 'Bfrtip',
       buttons: [
         'copy', 'csv', 'excel', 'pdf', 'print'
       ]
     });

     $('#t_desc').dataTable({
       "responsive": true,
       "paging": true,
       order: false,
       // "pageLength": true,
       dom: 'Bfrtip',
       buttons: [
         'copy', 'csv', 'excel', 'pdf', 'print'
       ]
     });

     $('#t_dinamis').dataTable({
       responsive: false,
       "paging": true,
       "bSort": false,
       "pageLength": 30,
       dom: 'Bfrtip',
       buttons: [{
           extend: 'copyHtml5',
           exportOptions: {
             columns: ':visible'
           }
         },
         {
           extend: 'excelHtml5',
           exportOptions: {
             columns: ':visible'
           }
         },
         'colvis'
       ]
     });


     // Setup - add a text input to each footer cell
     $('#t_karyawan thead tr').clone(true).appendTo('#t_karyawan thead');
     $('#t_karyawan thead tr:eq(1) th').each(function(i) {
       var title = $(this).text();
       $(this).html('<input type="text" class="form-control" placeholder="Cari ' + title + '" />');

       $('input', this).on('keyup change', function() {
         if (table.column(i).search() !== this.value) {
           table
             .column(i)
             .search(this.value)
             .draw();
         }
       });
     });

     var table = $('#t_karyawan').DataTable({
       responsive: true,
       orderCellsTop: true,
       fixedHeader: true,
       searching: false,
       dom: 'Bfrtip',
       buttons: [
         'copy', 'csv', 'excel', 'pdf', 'print'
       ]
     });

   });

   function goBack() {
     window.history.back();
   }
 </script>
 <!-- ################################################### BAGIAN ################################################################ -->
 <script>
   $(document).ready(function() {
     // Untuk sunting
     $('#edit_bagian').on('show.bs.modal', function(event) {
       var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
       var modal = $(this)

       // Isi nilai pada field
       modal.find('#erecid_bag').attr("value", div.data('recid_bag'));
       modal.find('#ekode_bag').attr("value", div.data('kode_bag'));
       modal.find('#enama_bag').attr("value", div.data('nama_bag'));
       $("#edepartment").val(div.data('department'));
       $("#edept_group").val(div.data('dept_group'));
       $("#eshift").val(div.data('shift'));
       $("#epay").val(div.data('pay'));
       $("#enote").val(div.data('note'));
     });

     $('#edit_struktur').on('show.bs.modal', function(event) {
       var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
       var modal = $(this)

       // Isi nilai pada field
       modal.find('#erecid_str').attr("value", div.data('recid_struktur'));
       modal.find('#enama_str').attr("value", div.data('nama'));
       $("#epic_struktur").val(div.data('department'));
     });
   });
 </script>

 <!-- ################################################### JABATAN ################################################################ -->
 <script>
   $(document).ready(function() {
     // Untuk sunting
     $('#edit_jabatan').on('show.bs.modal', function(event) {
       var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
       var modal = $(this)
       var a = div.data('top');
       // Isi nilai pada field
       modal.find('#erecid_jbtn').attr("value", div.data('recid_jbtn'));
       modal.find('#eindeks_jabatan').attr("value", div.data('indeks_jabatan'));
       modal.find('#enama_jbtn').attr("value", div.data('nama_jbtn'));
       modal.find('#etingkatan').attr("value", div.data('tingkatan'));
       $("#etop").val(div.data('top'));
       $("#ests_jbtn").val(div.data('sts_jabatan'));
       $("#enote").val(div.data('note'));
     });
   });
 </script>

 <!-- ################################################### ROLE ################################################################### -->
 <script>
   $(document).ready(function() {
     // Untuk sunting
     $('#edit_role').on('show.bs.modal', function(event) {
       var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
       var modal = $(this)

       // Isi nilai pada field
       modal.find('#erecid_role').attr("value", div.data('recid_role'));
       modal.find('#enama_role').attr("value", div.data('nama_role'));
       $("#enote").val(div.data('note'));
     });
   });
 </script>

 <!-- ################################################### SELEKSI ################################################################### -->
 <script>
   $(document).ready(function() {
     // Untuk sunting
     $('#edit_seleksi').on('show.bs.modal', function(event) {
       var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
       var modal = $(this)

       // Isi nilai pada field
       modal.find('#erecid_seleksi').attr("value", div.data('recid_seleksi'));
       modal.find('#erecid_recruitment').attr("value", div.data('recid_recruitment'));
       $("#ehasil").val(div.data('hasil'));
       $("#eother").val(div.data('other_berkas'));
       modal.find('#estatus').attr("value", div.data('status'));
       $("#enote").val(div.data('note'));
     });
   });
 </script>

 <!-- ################################################### USER ################################################################### -->
 <script>
   $(document).ready(function() {
     //chain combo box register user login
     $("#bagian").change(function() {
       var bagian = document.getElementById('bagian').value;
       $.ajax({
         type: "POST", // Method pengiriman data bisa dengan GET atau POST
         url: "<?php echo base_url(); ?>Karyawan/karyawan_bagian", // Isi dengan url/path file php yang dituju
         data: {
           recid_bag: bagian
         }, // data yang akan dikirim ke file yang dituju
         dataType: "json",
         beforeSend: function(e) {
           if (e && e.overrideMimeType) {
             e.overrideMimeType("application/json;charset=UTF-8");
           }
         },
         success: function(response, data) { // Ketika proses pengiriman berhasil
           // set isi dari combobox kota
           // lalu munculkan kembali combobox kotanya
           $("#karyawan").html(response.list_kota).show();
         },
         error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
           alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
         }
       });
     });

     //retrieve data for edit user
     $('#edit_user').on('show.bs.modal', function(event) {
       var div = $(event.relatedTarget) // Tombol dimana modal di tampilkan
       var modal = $(this)

       // Isi nilai pada field
       modal.find('#erecid_login').attr("value", div.data('recid_login'));
       modal.find('#erecid_karyawan').attr("value", div.data('recid_karyawan'));
       modal.find('#enama').attr("value", div.data('nama'));
       modal.find('#eusername').attr("value", div.data('username'));
       modal.find('#epassword2').attr("value", div.data('password'));
       modal.find('#erole').val(div.data('role'));
       $("#enote").val(div.data('note'));
     });
   });
 </script>


 <!-- ################################################### KARYAWAN ############################################################## -->
 <script>
   $(document).ready(function() {
     //tgl_lahir
     $('#myDatepicker2').datetimepicker({
       format: 'YYYY-MM-DD'
     });
     //tgl_m_kerja
     $('#myDatepicker3').datetimepicker({
       format: 'YYYY-MM-DD'
     });
     //tgl_a_kerja
     $('#myDatepicker4').datetimepicker({
       format: 'YYYY-MM-DD'
     });
     $('#myDatepicker5').datetimepicker({
       format: 'YYYY-MM-DD'
     });
     $('#myTime1').datetimepicker({
       format: 'HH : mm '
     });
     $('#myTime2').datetimepicker({
       format: 'HH : mm '
     });
     $('#myTime3').datetimepicker({
       format: 'HH : mm '
     });
     $('#myTime4').datetimepicker({
       format: 'HH : mm : ss'
     });
     $("#datepicker_tahun").datetimepicker({
       format: "YYYY",
     });


     // VALIDASI FORM PROFIL KARYAWAN
     //   jQuery("#testForm").submit(function (evt) {
     //   if ((jQuery("input[Name='tmp_lahir']").val().length < 1) ||
     //     (jQuery("input[Name='nama_karyawan']").val().length < 1) ||
     //     (jQuery("input[Name='tgl_lahir']").val().length < 1) ||
     //     (jQuery("input[Name='no_ktp']").val().length < 1) ||
     //     (jQuery("input[Name='no_npwp']").val().length < 1) ||
     //     (jQuery("input[Name='pendidikan']").val().length < 1) ||
     //     (jQuery("input[Name='no_jamsos']").val().length < 1) ||
     //     (jQuery("input[Name='tgl_m_kerja']").val().length < 1) ||
     //     (jQuery("input[Name='sts_jbtn']").val().length < 1) ||
     //     (jQuery("input[Name='alamat_ktp']").val().length < 1) ||
     //     (jQuery("input[Name='alamat_skrg']").val().length < 1) ||
     //     (jQuery("input[Name='pendidikan']").val().length < 1) ||
     //     (jQuery("input[Name='no_bpjs_kes']").val().length < 1) ||
     //     (jQuery("input[Name='no_bpjs_tk']").val().length < 1) ||
     //     (jQuery("input[Name='telp1']").val().length < 1 )
     //     )  
     //   {
     //     alert("Semua data harus terisi");
     //     evt.preventDefault();
     //     return;
     //   }
     //   else{
     //     window.location.href = "<?php echo base_url(); ?>Karyawan/karyawan_pupdatebeta";
     //   }
     // });

     // Chain Karyawan By Divisi
     /*   $("#divisi").change(function(){ // Ketika user mengganti atau memilih data provinsi
        $("#karyawan").hide(); // Sembunyikan dulu combobox kota nya
        var divisi =  $("#divisi").val();
        $.ajax({
          type: "POST", // Method pengiriman data bisa dengan GET atau POST
          url: "<?php echo base_url(); ?>Karyawan/listKaryawan", // Isi dengan url/path file php yang dituju
          data: {recid_bag :divisi}, // data yang akan dikirim ke file yang dituju
          dataType: "json",
          beforeSend: function(e) {
            if(e && e.overrideMimeType) {
              e.overrideMimeType("application/json;charset=UTF-8");
            }
          },
          success: function(response, data){ // Ketika proses pengiriman berhasil
            // set isi dari combobox kota
            // lalu munculkan kembali combobox kotanya
            $("#karyawan").html(response.list_karyawan).show();
          },
          error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
          }
        });
      });*/
   });

   function cek_nik() {
     var nik = $("#karyawan").val();
     $("#nik").val(nik);
   }

   function hanyaAngka(evt) {
     var charCode = (evt.which) ? evt.which : event.keyCode
     if (charCode > 31 && (charCode < 45 || charCode > 57 || charCode == 47))

       return false;
     return true;
   }
 </script>

 <!-- ################################################### KARIR ############################################################### -->
 <script>
   $(document).ready(function() {
     $('#myDatepicker6').datetimepicker({
         format: 'YYYY-MM-DD'
       })
       .on('dp.change', function(e) {
         document.getElementById("total_lembur").value = '';
       })

     $('#bag').change(function() {
       document.getElementById("total_lembur").value = '';
     });

     $('input:radio[name=kategori]').change(function() {
       if (this.value == 'Awal') {
         $("#genik").show();
         $('#myDatepicker5').datetimepicker({
             format: 'YYYY-MM-DD'
           })
           .on('dp.change', function(e) {
             if (e.date) {
               var tgl = e.date.format();
               console.log(tgl);
               var thn = tgl.substring(0, 4);
               var bln = tgl.substring(7, 5);
               var da = tgl.substring(10, 8);
               var nik = thn + bln + da;
               $("#nik1").val(nik);
             }
           })
       } // punya if
       else {
         $("#genik").hide();
       }
     });

     // STATUS KARYAWAN ON CHANGE
     $('#jenis').change(function() {
       var test = $("#jenis").val();
       if (test == 'Lanjutan') {
         $("#lanjutan").show();
       } else {
         $("#lanjutan").hide();
         $("#genik").hide();
       }
     });

     /*$('#emp12').change(function() {
        var test = $("#jenis").val();
         var id = $("#emp12").val();
         $.ajax({  //---------------------------------------- nik --------------------------------------------------------
         type: "POST", // 
         url: "<?php echo base_url(); ?>Karyawan/cek_spm", 
         data: {id : id}, 
         dataType: "json",
         beforeSend: function(e) {
           if(e && e.overrideMimeType) {
            e.overrideMimeType("application/json;charset=UTF-8");
          }
        },
         success: function(response, data){ // Ketika proses pengiriman berhasil           
           document.getElementById('nik2').value = response[0][1];
           document.getElementById('spm').value = response[0][0];
         },
         error: function (xhr, ajaxOptions, thrownError) { // Ketika ada error
               alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
             }
           });


         if(test == 'Awal'){
           var form = document.getElementById("karir");
           var elements = form.elements;
           for (var i = 0, len = elements.length; i < len; ++i) {
             elements[i].readOnly = false;
           }

           $("#lanjutan").hide();
           $("#genik").show();
           document.getElementById("nik1").readOnly = true;
           document.getElementById("nik2").readOnly = true;
           $('#myDatepicker5').datetimepicker({
             format: 'YYYY-MM-DD'
           })
           .on('dp.change', function(e){
             if(e.date){
               var tgl =  e.date.format();
               console.log(tgl );
               var thn = tgl.substring(0,4);
               var bln = tgl.substring(7,5);
               var da = tgl.substring(10, 8);
               spm = document.getElementById('spm').value;
               if(spm == 'Ya')
               {
                 var iden = '7';
                 var nik = iden+thn+bln+da;
               }else{
                  var nik = thn+bln+da;
               }
               $("#nik1").val(nik);
             }
           })
           $('input:radio[name="sts_aktif"][value="Aktif"]').prop('checked', true);
         } else if(test == 'Lanjutan'){
            var form = document.getElementById("karir");
            var elements = form.elements;
            for (var i = 0, len = elements.length; i < len; ++i) {
             elements[i].readOnly = false;
           }

             $("#genik").hide();
             $("#lanjutan").show();
             $('input:radio[name="sts_aktif"][value="Aktif"]').prop('checked', true);
         } else if(test == 'Akhir'){
            var form = document.getElementById("karir");
            var elements = form.elements;
            for (var i = 0, len = elements.length; i < len; ++i) {
             elements[i].readOnly = false;
           }
           $(".akhir").hide();
           $("#lanjutan").hide();
           $('input:radio[name="sts_aktif"][value="Tidak Aktif"]').prop('checked', true);
           $("#aktif").show();
         } else{
           var form = document.getElementById("karir");
           var elements = form.elements;
           for (var i = 0, len = elements.length; i < len; ++i) {
             elements[i].readOnly = true;
           }
         }
     });*/

     /*$('input:radio[name=sts_aktif]').change(function() {
        if (this.value == 'Tidak Aktif') {
         $("#aktif").show();
        }
       else{
         $("#aktif").hide();
       }
     });*/


     $('input:radio[name=spm]').change(function() {
       // alert("change!");
       if (this.value == 'Ya') {
         $("#tmp_toko").show();
         $("#tmp_kota").show();
       } else {
         $("#tmp_toko").hide();
         $("#tmp_kota").hide();
       }
     });

     //SEARCH MULTI SELECT
     $('.searchable').multiSelect({
       selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='try \"nama\"'>",
       selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='try \"nama\"'>",
       afterInit: function(ms) {
         var that = this,
           $selectableSearch = that.$selectableUl.prev(),
           $selectionSearch = that.$selectionUl.prev(),
           selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
           selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

         that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
           .on('keydown', function(e) {
             if (e.which === 40) {
               that.$selectableUl.focus();
               return false;
             }
           });

         that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
           .on('keydown', function(e) {
             if (e.which == 40) {
               that.$selectionUl.focus();
               return false;
             }
           });
       },
       afterSelect: function() {
         this.qs1.cache();
         this.qs2.cache();
       },
       afterDeselect: function() {
         this.qs1.cache();
         this.qs2.cache();
       }
     });

     $('#callbacks').multiSelect({
       afterSelect: function(values) {
         // alert("Select value: "+values);
       },
       afterDeselect: function(values) {
         // alert("Deselect value: "+values);
       }
     });
   }); // punya doc ready

   function def_jam() {
     document.getElementById("time1").value = "07 : 30";
     document.getElementById("time2").value = "16 : 30";
   }
 </script>


 <!-- ################################################### ABSEN ################################################################ -->
 <script>
   $(document).ready(function() {
     $('#r_allabsensi').DataTable({
       "ordering": false,
       dom: 'Bfrtip',
       // "iDisplayLength": 10,
       deferRender: true,
       scrollY: "800px",
       scrollX: true,
       scrollCollapse: true,
       fixedColumns: {
         leftColumns: 4,
       },
       buttons: [
         'excelHtml5',
         'csvHtml5'
       ]
     });
   });

   function filter() {
     var filter1 = $("#filter1").val();
     if (filter1 != "Semua") {
       $("#filter").show(); // Sembunyikan dulu combobox kota nya
       if (filter1 == "Department") {
         $.ajax({
           type: "POST", // Method pengiriman data bisa dengan GET atau POST
           url: "<?php echo base_url(); ?>Karyawan/filter_dept", // Isi dengan url/path file php yang dituju
           // data: {department : department}, // data yang akan dikirim ke file yang dituju
           dataType: "json",
           beforeSend: function(e) {
             if (e && e.overrideMimeType) {
               e.overrideMimeType("application/json;charset=UTF-8");
             }
           },
           success: function(response, data) { // Ketika proses pengiriman berhasil
             // set isi dari combobox kota
             // lalu munculkan kembali combobox kotanya
             $("#filter2").html(response.list_dept).show();
           },
           error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
             alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
           }
         });
       } else if (filter1 == "Struktur") {
         // alert('list bagian');
         $.ajax({
           type: "POST", // Method pengiriman data bisa dengan GET atau POST
           url: "<?php echo base_url(); ?>Karyawan/filter_struktur", // Isi dengan url/path file php yang dituju
           // data: {department : department}, // data yang akan dikirim ke file yang dituju
           dataType: "json",
           beforeSend: function(e) {
             if (e && e.overrideMimeType) {
               e.overrideMimeType("application/json;charset=UTF-8");
             }
           },
           success: function(response, data) { // Ketika proses pengiriman berhasil
             // set isi dari combobox kota
             // lalu munculkan kembali combobox kotanya
             $("#filter2").html(response.list_str).show();
           },
           error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
             alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
           }
         });
       } else {
         // alert('list bagian');
         $.ajax({
           type: "POST", // Method pengiriman data bisa dengan GET atau POST
           url: "<?php echo base_url(); ?>Karyawan/filter_bagian", // Isi dengan url/path file php yang dituju
           // data: {department : department}, // data yang akan dikirim ke file yang dituju
           dataType: "json",
           beforeSend: function(e) {
             if (e && e.overrideMimeType) {
               e.overrideMimeType("application/json;charset=UTF-8");
             }
           },
           success: function(response, data) { // Ketika proses pengiriman berhasil
             // set isi dari combobox kota
             // lalu munculkan kembali combobox kotanya
             $("#filter2").html(response.list_bag).show();
           },
           error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
             alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
           }
         });
       }
     } else {
       $("#filter2").hide();
     }
   }
 </script>

 <!-- ################################################### LEGAL ################################################################# -->
 <script>
   $(document).ready(function() {

   });

   function notif() {
     var awal = $("#awal").val();
     var akhir = $("#akhir").val();
     var jenis = $("#jenis").val();
     // alert(awal + akhir + jenis);
     var table = $('#t_notif').DataTable();
     table.destroy();
     var table = $('#t_notif').DataTable({
       "responsive": true,
       "bScrollCollapse": true,
       "bLengthChange": true,
       "searching": true,
       "dom": 'Bfrtip',
       buttons: [
         'excel', 'print'
       ],
       "ajax": {
         type: "POST",
         url: "<?php echo base_url(); ?>Karyawan/r_legals",
         dataType: 'JSON',
         data: {
           awal: awal,
           akhir: akhir,
           jenis: jenis
         },
       },
     });
     $("#t_notif").show();
   }
 </script>

 <!-- ############################################## TUNJANGAN ################################################################ -->
 <script type="text/javascript">
   $(document).ready(function() {

     $('input:radio[name=sts_tunjangan]').change(function() {
       if (this.value == 'Yes') {
         var kat = $('input:radio[name=hub_keluarga]:checked').val();
         var e = document.getElementById("nik");
         var recid_karyawan = e.options[e.selectedIndex].value;
         $.ajax({
           type: "POST", // Method pengiriman data bisa dengan GET atau POST
           url: "<?php echo base_url(); ?>Karyawan/cek_tanggungan", // Isi dengan url/path file php yang dituju
           data: {
             hub_keluarga: kat,
             recid_karyawan: recid_karyawan
           }, // data yang akan dikirim ke file yang dituju
           success: function(response, data) {
             if (response != 'Valid') {
               alert(response);
             }
           },
           error: function(xhr, ajaxOptions, thrownError) { // Ketika ada error
             console.log("textStatus: " + textStatus);
             console.log("errorThrown: " + errorThrown);
             alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError); // Munculkan alert error
           }
         });
       } // punya if
     });
   });




   /*function upl_npwp()
   {
    $.ajax({
      url:'<?php echo base_url(); ?>Karyawan/upload_npwp',
      type:"post",
                  data:new FormData(this), //this is formData
                  processData:false,
                  contentType:false,
                  cache:false,
                  async:false,
                  success: function(data, response){
                   // alert("Upload Image Successful.");
                   document.getElementById("notif_npwp").innerHTML += data;
                 }
               });
   }*/
 </script>