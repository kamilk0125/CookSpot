      <div class="login">
            <div class = "toggleSwitch">
                  <button class="rounded formSelector <?php echo ($this->activeForm==='login') ? 'selectedOption' : ''; ?>" type="button" id="loginFormBtn">Log In</button>
                  <button class="rounded formSelector <?php echo ($this->activeForm==='register') ? 'selectedOption' : ''; ?>" type="button" id="registerFormBtn">Register</button>
            </div>
            <br>
            <form class = "<?php echo ($this->activeForm==='login') ? '' : 'invisible'; ?>" action="login" id = "loginForm" method="POST">
                  <label class = "form error" for = "id"><?php echo $this->errorMsg ?? '';?></label>
                  <br>
                  <input class = "form" type="text" name = "loginForm[id]" placeholder="username or email" value="<?php echo $this->formData['loginForm']['id'] ?? '';?>">
                  <br>
                  <label class = "form" for = "id"></label>
                  <br>
                  <input class = "form" type="password" name = "loginForm[password]" placeholder="password">
                  <br>
                  <label class = "form" for = "password"></label>
                  <br>
                  <button type = "submit" name = "loginForm[submit]" class = "rounded">Log In</button>
                  <br><br>
                  <a href="login?view=passwordReset">Forgot your password?</a>
            </form>
            <form class = "loginInfoForm <?php echo ($this->activeForm==='register') ? '' : 'invisible'; ?>" action="login" id = "registerForm" method="POST">
                  <label class = "form error" for = "id"><?php echo $this->errorMsg ?? '';?></label>
                  <br>
                  <input class = "form validationInput" type="text" name = "registerForm[username]" id = "username" placeholder="username" value="<?php echo $this->formData['registerForm']['username'] ?? '';?>">
                  <br>
                  <label class = "form error" for = "username" id = "usernameLabel"></label>
                  <br>
                  <input class = "form validationInput" type="text" name = "registerForm[email]" id = "email" placeholder="email" value="<?php echo $this->formData['registerForm']['email'] ?? '';?>">
                  <br>
                  <label class = "form error" for = "email" id = "emailLabel"></label>
                  <br>
                  <input class = "form validationInput" type="text" name = "registerForm[displayName]" id = "displayName" placeholder="display name" value="<?php echo $this->formData['registerForm']['displayName'] ?? '';?>">
                  <br>
                  <label class = "form error" for = "displayName" id = "displayNameLabel"></label>
                  <br>
                  <input class = "form validationInput" type="password" name = "registerForm[password]" id = "password" placeholder="password">
                  <br>
                  <label class = "form error" for = "password" id = "passwordLabel"></label>
                  <br>
                  <input class = "form validationInput" type="password" name = "registerForm[confirmPassword]" id = "confirmPassword" placeholder="confirm password">
                  <br>
                  <label class = "form error" for = "confirmPassword" id = "confirmPasswordLabel"></label>
                  <br>
                  <button type = "submit" name = "registerForm[submit]" class = "rounded disabled" disabled>Register</button>
            </form>
      </div>
      <script src = "js/login.js"></script>