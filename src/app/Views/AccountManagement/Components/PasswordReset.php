<form class="loginInfoForm" id="passwordResetForm" action="login?view=passwordReset" method="POST">
    <div class="centered" id="passwordResetFormDiv"> 
        <h2>Reset Password</h2>
        <div id="userInfo">
            <label class = "form error"><?php echo $this->errorMsg ?? '';?></label>
            <br><br>
            <input class = "form" type="text" name = "passwordResetForm[email]" id = "email" placeholder="email" value="<?php echo $this->formData['passwordResetForm']['email'] ?? '';?>">
        </div>
        <br>
        <br>
        <br>
        <div id="controlBtns">
            <button id="submitBtn" type="submit" name = "passwordResetForm[submit]" class="squared editElement">Reset password</button>
        </div>
    </div>
</form>