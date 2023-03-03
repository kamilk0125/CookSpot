<form class="loginInfoForm" id="passwordResetForm" action="login?view=passwordReset" method="POST">
    <input type="text" name="handler" value="accountHandler" class="invisible">
    <input type="text" name="action" value="resetPassword" class="invisible">
    <div class="centered" id="passwordResetFormDiv"> 
        <h2>Reset Password</h2>
        <div id="userInfo">
            <label class = "form error"><?php echo $this->errorMsg;?></label>
            <br><br>
            <input class = "form validationInput" type="text" name = "args[email]" id = "email" placeholder="email" spellcheck="false" value="<?php echo $this->formData['args']['email'] ?? '';?>">
            <br>
            <label class = "form error" for = "email" id = "emailLabel"></label>
        </div>
        <br>
        <div id="controlBtns">
            <button id="submitBtn" type="submit" name = "" class="squared editElement disabled" disabled>Reset password</button>
        </div>
    </div>
</form>
<script src="js/login.js"></script>