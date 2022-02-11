	<?php $this->extends('./views/login_default.php')?>
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form>
              <h1>登入</h1>
              <div>
                <input type="text" class="form-control" placeholder="email" required="" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <a class="btn btn-default submit" href="index.html">登入</a>
                <a class="reset_pass" href="#">忘記密碼</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                
                  <a href="#signup" class="to_register"> 註冊帳號 </a>
               
                <div class="clearfix"></div>
                <br />

               
              </div>
            </form>
          </section>
        </div>

        <div id="register" class="animate form registration_form">
          <section class="login_content">
            <form>
              <h1>註冊</h1>
              <div>
                <input type="text" class="form-control" placeholder="Username" required="" />
              </div>
              <div>
                <input type="email" class="form-control" placeholder="Email" required="" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" required="" />
              </div>
              <div>
                <a class="btn btn-default submit" href="index.html">註冊</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">已有帳號?
                  <a href="#signin" class="to_register"> 登入 </a>
                </p>

                <div class="clearfix"></div>
                <br />

                
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>

