<form class="loginInfoForm <?php echo $this->valid ? '' : 'invisible'; ?>" id="passwordForm" action="login?view=<?php echo $this->passwordReset ? 'passwordReset&id=' . $this->userId . '&hash=' . $this->verificationHash : 'changePassword'; ?>" method="POST">
    <div class="centered" id="passwordFormDiv"> 
        <h2>Change Password</h2>
        <div id="userInfo">
            <label class = "form error"><?php echo $this->errorMsg ?? '';?></label>
            <br><br>
            <input class = "form <?php echo ($this->passwordReset) ? 'invisible' : ''; ?>" type="password" name = "passwordForm[currentPassword]" id = "currentPassword" placeholder="current password" value="<?php echo $this->formData['passwordForm']['currentPassword'] ?? '';?>">
            <br>
            <label class = "form error" for = "currentPassword" id = "currentPasswordLabel"></label>
            <br>
            <input class = "form validationInput" type="password" name = "passwordForm[password]" id = "password" placeholder="password" value="<?php echo $this->formData['passwordForm']['password'] ?? '';?>">
            <br>
            <label class = "form error" for = "password" id = "passwordLabel"></label>
            <br>
            <input class = "form validationInput" type="password" name = "passwordForm[confirmPassword]" id = "confirmPassword" placeholder="confirm password" value="<?php echo $this->formData['passwordForm']['confirmPassword'] ?? '';?>">
            <br>
            <label class = "form error" for = "confirmPassword" id = "confirmPasswordLabel"></label>
            <br>
            <input class = "invisible" type="text" name = "passwordForm[userId]" value="<?php echo $this->userId ?? '';?>">
            <input class = "invisible" type="text" name = "passwordForm[verificationHash]" value="<?php echo $this->verificationHash ?? '';?>">
        </div>
        <br>
        <br>
        <br>
        <div id="controlBtns">
            <button id="saveBtn" type="submit" name = "passwordForm[submit]" class="squared green editElement">✓ Save</button>
            <a href="/"><button id="discardBtn" type="button" class="squared red editElement">✗ Cancel</button></a>
        </div>
    </div>
</form>
<div class="centered <?php echo $this->valid ? 'invisible' : ''; ?>">
   <h1>Password reset request failed</h1>
   <p>Password reset link is invalid or has already expired</p>
   <a href="/">
      <button class="rounded">Main Page</button>
   </a>
</div>
<script src = "js/login.js"></script>