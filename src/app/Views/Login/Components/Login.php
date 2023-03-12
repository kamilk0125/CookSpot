      <div id="login" class="">
            <div class = "css-toggleSwitch">
                  <button class="css-rounded js-formSelector <?php echo ($this->activeForm==='login') ? 'css-selectedOption js-selectedOption' : ''; ?>" type="button" id="loginFormBtn">Log In</button>
                  <button class="css-rounded js-formSelector <?php echo ($this->activeForm==='register') ? 'css-selectedOption js-selectedOption' : ''; ?>" type="button" id="registerFormBtn">Register</button>
            </div>
            <br>
            <form class = "<?php echo ($this->activeForm==='login') ? '' : 'css-invisible'; ?>" action="login" id = "loginForm" method="POST">
                  <input type="text" name="handler" value="loginHandler" class="css-invisible">
                  <input type="text" name="action" value="logIn" class="css-invisible">
                  <label class = "css-form css-error"><?php echo ($this->activeForm==='login') ? $this->errorMsg : '';?></label>
                  <br>
                  <input class = "css-form" type="text" name = "args[id]" placeholder="username or email" spellcheck="false" value="<?php echo $this->formData['args']['id'] ?? '';?>">
                  <br>
                  <label class = "css-form" for = "id"></label>
                  <br>
                  <input class = "css-form" type="password" name = "args[password]" placeholder="password" spellcheck="false">
                  <br>
                  <label class = "css-form" for = "password"></label>
                  <br>
                  <button type = "submit" name = "" class = "css-rounded">Log In</button>
                  <br><br>
                  <a href="login?view=passwordReset">Forgot your password?</a>
            </form>
            <form class = "js-loginInfoForm <?php echo ($this->activeForm==='register') ? '' : 'css-invisible js-notUsed'; ?>" action="login" id = "registerForm" method="POST">
                  <input type="text" name="handler" value="accountHandler" class="css-invisible">
                  <input type="text" name="action" value="registerAccount" class="css-invisible">
                  <label class = "css-form css-error"><?php echo ($this->activeForm==='register') ? $this->errorMsg : '';?></label>
                  <br>
                  <input class = "css-form js-validationInput" type="text" name = "args[username]" id = "username" placeholder="username" spellcheck="false" value="<?php echo $this->formData['args']['username'] ?? '';?>">
                  <br>
                  <label class = "css-form css-error" for = "username" id = "usernameLabel"></label>
                  <br>
                  <input class = "css-form js-validationInput" type="text" name = "args[email]" id = "email" placeholder="email" spellcheck="false" value="<?php echo $this->formData['args']['email'] ?? '';?>">
                  <br>
                  <label class = "css-form css-error" for = "email" id = "emailLabel"></label>
                  <br>
                  <input class = "css-form js-validationInput" type="text" name = "args[displayName]" id = "displayName" placeholder="display name" spellcheck="false" value="<?php echo $this->formData['args']['displayName'] ?? '';?>">
                  <br>
                  <label class = "css-form css-error" for = "displayName" id = "displayNameLabel"></label>
                  <br>
                  <input class = "css-form js-validationInput" type="password" name = "args[password]" id = "password" placeholder="password" spellcheck="false">
                  <br>
                  <label class = "css-form css-error" for = "password" id = "passwordLabel"></label>
                  <br>
                  <input class = "css-form js-validationInput" type="password" name = "args[confirmPassword]" id = "confirmPassword" placeholder="confirm password" spellcheck="false">
                  <br>
                  <label class = "css-form css-error" for = "confirmPassword" id = "confirmPasswordLabel"></label>
                  <br>
                  <button type = "submit" name = "" class = "css-rounded css-disabled" disabled>Register</button>
            </form>
      </div>
      <script src = "js/inputValidation.js"></script>