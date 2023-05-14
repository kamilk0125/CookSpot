<form class="js-loginInfoForm <?php echo $this->valid ? '' : 'css-invisible js-notUsed'; ?>" id="passwordForm" action="/login?view=<?php echo ($this->requestType === 'reset') ? 'passwordReset&id=' . $this->userId . '&hash=' . $this->verificationHash : 'changePassword'; ?>" method="POST">
    <input type="text" name="handler" value="settingsHandler" class="css-invisible">
    <input type="text" name="action" value="modifyPassword" class="css-invisible">
    <input type="text" name="args[requestType]" value="<?php echo $this->requestType; ?>" class="css-invisible">
    <input type="text" name = "args[userId]" value="<?php echo $this->userId;?>" class = "css-invisible" >
    <input type="text" name = "args[verificationHash]" value="<?php echo $this->verificationHash;?>" class = "css-invisible" >
    <div class="css-centered" id="passwordFormDiv"> 
        <h2>Change Password</h2>
        <div id="userInfo">
            <label class = "css-form css-error"><?php echo $this->errorMsg;?></label>
            <br><br>
            <input class = "css-form <?php echo ($this->requestType === 'reset') ? 'css-invisible' : ''; ?>" type="password" name = "args[currentPassword]" id = "currentPassword" placeholder="current password" spellcheck="false" value="<?php echo $this->formData['args']['currentPassword'] ?? '';?>">
            <br>
            <label class = "css-form css-error" for = "currentPassword" id = "currentPasswordLabel"></label>
            <br>
            <input class = "css-form js-validationInput" type="password" name = "args[password]" id = "password" placeholder="password" spellcheck="false" value="<?php echo $this->formData['args']['password'] ?? '';?>">
            <br>
            <label class = "css-form css-error" for = "password" id = "passwordLabel"></label>
            <br>
            <input class = "css-form js-validationInput" type="password" name = "args[confirmPassword]" id = "confirmPassword" placeholder="confirm password" spellcheck="false" value="<?php echo $this->formData['args']['confirmPassword'] ?? '';?>">
            <br>
            <label class = "css-form css-error" for = "confirmPassword" id = "confirmPasswordLabel"></label>
            <br>
        </div>
        <br>
        <br>
        <br>
        <div id="controlBtns">
            <button id="saveBtn" type="submit" name = "" class="css-squared css-green css-editElement css-disabled" disabled>✓ Save</button>
            <a href="/"><button id="discardBtn" type="button" class="css-squared css-red css-editElement">✗ Cancel</button></a>
        </div>
    </div>
</form>
<div class="css-centered <?php echo $this->valid ? 'css-invisible' : ''; ?>">
   <h1>Password reset request failed</h1>
   <p>Password reset link is invalid or has already expired</p>
   <a href="/">
      <button class="css-rounded">Main Page</button>
   </a>
</div>
<script src = "js/inputValidation.js"></script>