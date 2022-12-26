      <div class="login">
            <div class = "toggleSwitch">
                  <button class="form formSelector <?php echo isset($_POST['register']) ? '' : 'selectedOption' ?>" type="button" id="loginFormBtn">Log In</button>
                  <button class="form formSelector <?php echo isset($_POST['register']) ? 'selectedOption' : '' ?>" type="button" id="registerFormBtn">Register</button>
            </div>
            <br>
            <form class = "<?php echo isset($_POST['register']) ? 'invisible' : '' ?>" action="login" id = "loginForm" method="POST">
                  <label class = "form error" for = "id"><?php echo $_POST['loginForm']['errorLabel'] ?? '';?></label>
                  <br>
                  <input class = "form" type="text" name = "id" placeholder="username or email" value="<?php echo $_POST['id'] ?? '';?>">
                  <br>
                  <label class = "form" for = "id"></label>
                  <br>
                  <input class = "form" type="password" name = "password" placeholder="password">
                  <br>
                  <label class = "form" for = "password"></label>
                  <br>
                  <button type = "submit" name = 'login' class = "form">Log In</button>
            </form>
            <form class = "<?php echo isset($_POST['register']) ? '' : 'invisible' ?>" action="login" id = "registerForm" method="POST">
                  <label class = "form error" for = "id"><?php echo $_POST['registerForm']['errorLabel'] ?? '';?></label>
                  <br>
                  <input class = "form" type="text" name = "username" id = "username" placeholder="username" value="<?php echo $_POST['username'] ?? '';?>">
                  <br>
                  <label class = "form error" for = "username" id = "usernameLabel"></label>
                  <br>
                  <input class = "form " type="text" name = "email" id = "email" placeholder="email" value="<?php echo $_POST['email'] ?? '';?>">
                  <br>
                  <label class = "form error" for = "email" id = "emailLabel"></label>
                  <br>
                  <input class = "form" type="text" name = "displayName" id = "displayName" placeholder="display name" value="<?php echo $_POST['displayName'] ?? '';?>">
                  <br>
                  <label class = "form error" for = "displayName" id = "displayNameLabel"></label>
                  <br>
                  <input class = "form" type="password" name = "password" id = "password" placeholder="password">
                  <br>
                  <label class = "form error" for = "password" id = "passwordLabel"></label>
                  <br>
                  <input class = "form" type="password" name = "confirmPassword" id = "confirmPassword" placeholder="confirm password">
                  <br>
                  <label class = "form error" for = "confirmPassword" id = "confirmPasswordLabel"></label>
                  <br>
                  <button type = "submit" name = 'register' class = "form disabled" disabled>Register</button>
            </form>
      </div>
      <script src = "js/login.js"></script>