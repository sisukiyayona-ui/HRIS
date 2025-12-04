 <body class="login">
   <div>
     <a class="hiddenanchor" id="signup"></a>
     <a class="hiddenanchor" id="signin"></a>

     <div class="login_wrapper">
       <div class="animate form login_form">
         <section class="login_content">
           <form method="post" action="<?php echo base_url() ?>Auth/cekLogin">
             <h1>Login</h1>
             <div>
               <input type="text" class="form-control" name="uname" placeholder="Username" required="" autofocus="autofocus" autocomplete="off" />
             </div>
             <div class="input-group">
               <!-- <input type="password" class="form-control" name="password" placeholder="Password" required="" /> -->
               <input type="password" name="password" placeholder="Password" class="form-control password-input2">
               <span class="input-group-btn">
                 <button class="btn border border-left-0" type="button"><i class="fa-solid fa-eye show-password2"></i></button>
               </span>
             </div>

             <div class="form-group">
               <p><?= $image; ?></p>
               <div class="input-group input-group-alternative ">
                 <input type="text" name="captcha_code" class="form-control" placeholder="Ketikan Captcha" autocomplete="off" required="required">
               </div>
             </div>

             <div>
               <input type="submit" class="btn btn-default" value="Log in">
               <a href="<?php echo base_url() ?>Auth/ganti_password"><button type="button" class="btn btn-default">Lupa Password</button></a>
               <!-- Lupa Password</a> -->
             </div>

             <div class="clearfix"></div>

             <div class="separator">

               <div class="clearfix"></div>
               <br />

               <div>
                 <h1><i class="fa fa-leaf"></i> HRIS </h1>
                 <h4> - Human Resource Information System - </h4>
                 <p>©2018 All Rights Reserved.<!--  PT. Chitose Internasionanl, Tbk. --></p>
               </div>
             </div>
           </form>
         </section>
       </div>


     </div>
   </div>
 </body>

 </html>

 <script>
   $(document).ready(function() {
     let showPasswordBtn2 = document.querySelector('.show-password2');
     let passwordInp2 = document.querySelector('.password-input2');

     showPasswordBtn2.addEventListener('click', () => {
       // toggle icon 
       // font awesome class for eye icon
       showPasswordBtn2.classList.toggle('fa-eye');
       // font awesome class for slashed eye icon
       showPasswordBtn2.classList.toggle('fa-eye-slash');
       // ternary operator a shorthand for if and else to change the type of password input
       passwordInp2.type = passwordInp2.type === 'password' ? 'text' : 'password';
     })

   });
 </script>