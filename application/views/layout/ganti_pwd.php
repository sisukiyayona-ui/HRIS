<style>
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* body{
        background: #4967d3;
        justify-content: center;
        align-items: center;
        font-family: 'Poppins', sans-serif;
    } */

    /* .password-input-box{
        position: relative;
        width: 300px;
        height: 40px;
    }

    .password-input{
        width: 100%;
        height: 100%;
        background: #fff;
        border: none;
        padding: 5px 15px;
        outline: none;
        border-radius: 5px;
        color: #d34970;
        padding-right: 45px;
    }

    .password-input::placeholder{
        color: #d34970;
    } */

    .password-input:focus{
        box-shadow: 0 0 0 3px #4967d3,
                    /* 0 0 0 6px #4fe222; */
    }

    .password-input2:focus{
        box-shadow: 0 0 0 3px #4967d3,
                    /* 0 0 0 6px #4fe222; */
    }

    .show-password{
        /* position: absolute;
        right: 15px;
        top: 50%; 
        transform: translateY(-50%);
        cursor: pointer;
        color: #92203f;*/
    }

    .show-password2{
        /* position: absolute;
        right: 15px;
        top: 50%; 
        transform: translateY(-50%);
        cursor: pointer;
        color: #92203f;*/
    }

     .password-checklist{
        position: absolute;
        left: calc(100% + 10px);
        width: 50%;
        padding: 20px 30px;
        background: #808fd5;
        border-radius: 5px;
        opacity: 0;
        pointer-events: none;
        transform: translateY(20px);
        transition: .5s ease;
    }

    .password-checklist2{
        position: absolute;
        left: calc(100% + 10px);
        width: 100%;
        padding: 20px 30px;
        background: #808fd5;
        border-radius: 5px;
        opacity: 0;
        pointer-events: none;
        transform: translateY(20px);
        transition: .5s ease;
    }

    .password-input:focus ~ .password-checklist{
        opacity: 1;
        transform: translateY(0);
    }

    .password-input2:focus ~ .password-checklist2{
        opacity: 1;
        transform: translateY(0);
    }

    .checklist-title{
        font-size: 15px;
        color: #922037;
        margin-bottom: 10px;
    }

    .checklist-title2{
        font-size: 20px;
        color: #FFF;
        margin-bottom: 10px;
    }

    .checklist{
        list-style: none;
    }

    .checklist2{
        list-style: none;
    }

    .list-item{
        padding-left: 30px;
        color: #fff;
        font-size: 14px;
    }

    .list-item2{
        padding-left: 30px;
        color: #fff;
        font-size: 14px;
    }

    .list-item::before{
        content: '\f00d';
        font-family: FontAwesome;
        display: inline-block;
        margin: 8px 0;
        margin-left: -30px;
        width: 20px;
        font-size: 12px;
    }

    .list-item2::before{
        content: '\f00d';
        font-family: FontAwesome;
        display: inline-block;
        margin: 8px 0;
        margin-left: -30px;
        width: 20px;
        font-size: 12px;
    }

    .list-item.checked{
        opacity: 0.5;
    }

    .list-item2.checked{
        opacity: 0.5;
    }

    .list-item.checked::before{
        content: '\f00c';
        color: #922037;
    }

    .list-item2.checked::before{
        content: '\f00c';
        color: #922037;
    }

  .table thead th {
  background-color: #4f5793; 
  color:#FFF
}
</style>
<body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form method="post" action="<?php echo base_url()?>Auth/user_change">
              <h1>Ganti Password</h1>
              <div>
                <input type="text" class="form-control" name="username" placeholder="Masukan Username yang Terdaftar" required="" autofocus="autofocus" autocomplete="off" />
              </div>
              <div class="input-group">
                    <input type="password" name="password" id="epassword" placeholder="Password" class="form-control password-input2">
                    <span class="input-group-btn">
                      <button class="btn border border-left-0" type="button"><i class="fa-solid fa-eye show-password2"></i></button>
                    </span>
                    <div class="password-checklist2">
                          <h3 class="checklist-title2">Password should be</h3>
                          <ul class="checklist2">
                              <li class="list-item2">At least 6 character long</li>
                              <li class="list-item2">At least 1 number</li>
                              <li class="list-item2">At least 1 lowercase letter</li>
                              <li class="list-item2">At least 1 uppercase letter</li>
                              <li class="list-item2">At least 1 special character</li>
                          </ul>
                        </div>
                  </div>

              <div class="form-group">
                  <p><?=$image;?></p>
                  <div class="input-group input-group-alternative ">
                      <input type="text" name="captcha_code" class="form-control" placeholder="Ketikan Captcha" autocomplete="off" required="required">
                  </div>
              </div>

              <div>
                <input type="submit" class="btn btn-default btn-simpan" value="Update">
              </div>

              <div class="clearfix"></div>

              <div class="separator">

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-leaf"></i> HRIS </h1>
                  <h4> - Human Resource Information System - </h4>
                  <p>©2018 All Rights Reserved. PT. Chitose Internasionanl, Tbk.</p>
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
        /* ----------------- EDIT ------------------------------- */

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

        // string password validation

        let passwordChecklist2 = document.querySelectorAll('.list-item2');
        let validationRegex2 = [
            { regex: /.{6,}/ }, // min 6 letters,
            { regex: /[0-9]/ }, // numbers from 0 - 9
            { regex: /[a-z]/ }, // letters from a - z (lowercase)
            { regex: /[A-Z]/}, // letters from A-Z (uppercase),
            { regex: /[^A-Za-z0-9]/} // special characters
        ]

        passwordInp2.addEventListener('keyup', () => {
          var total2 = 0;
            validationRegex2.forEach((item, i) => {

                let isValid2 = item.regex.test(passwordInp2.value);

                if(isValid2) {
                    passwordChecklist2[i].classList.add('checked');
                } else{
                    passwordChecklist2[i].classList.remove('checked');
                }
            })
          total2 = $('.checked').length
              if(total2 ==  5)
              {
                $('.btn-simpan'). prop('disabled', false);
              }else{
                $('.btn-simpan'). prop('disabled', true);
              }
        });
      });
    </script>