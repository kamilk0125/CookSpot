<form class="js-loginInfoForm" id="passwordResetForm" action="/login?view=passwordReset" method="POST">
    <input type="text" name="handler" value="accountHandler" class="css-invisible">
    <input type="text" name="action" value="resetPassword" class="css-invisible">
    <div class="css-centered" id="passwordResetFormDiv"> 
        <h2>Reset Password</h2>
        <div id="userInfo">
            <label class = "css-form css-error"><?php echo $this->errorMsg;?></label>
            <br><br>
            <input class = "css-form js-validationInput" type="text" name = "args[email]" id = "email" placeholder="email" spellcheck="false" value="<?php echo $this->formData['args']['email'] ?? '';?>">
            <br>
            <label class = "css-form css-error" for = "email" id = "emailLabel"></label>
        </div>
        <br>
        <div id="controlBtns">
            <button id="submitBtn" type="submit" name = "" class="css-squared css-editElement css-disabled" disabled>Reset password</button>
        </div>
    </div>
</form>
<script src="js/inputValidation.js"></script>