<form class="loginInfoForm <?php echo $this->valid ? '' : 'invisible'; ?>" id="passwordForm" action="login?view=<?php echo ($this->requestType === 'reset') ? 'passwordReset&id=' . $this->userId . '&hash=' . $this->verificationHash : 'changePassword'; ?>" method="POST">
    <input type="text" name="handler" value="settingsHandler" class="invisible">
    <input type="text" name="action" value="modifyPassword" class="invisible">
    <input type="text" name="args[requestType]" value="<?php echo $this->requestType; ?>" class="invisible">
    <input type="text" name = "args[userId]" value="<?php echo $this->userId;?>" class = "invisible" >
    <input type="text" name = "args[verificationHash]" value="<?php echo $this->verificationHash;?>" class = "invisible" >
    <div class="centered" id="passwordFormDiv"> 
        <h2>Change Password</h2>
        <div id="userInfo">
            <label class = "form error"><?php echo $this->errorMsg;?></label>
            <br><br>
            <input class = "form <?php echo ($this->requestType === 'reset') ? 'invisible' : ''; ?>" type="password" name = "args[currentPassword]" id = "currentPassword" placeholder="current password" spellcheck="false" value="<?php echo $this->formData['args']['currentPassword'] ?? '';?>">
            <br>
            <label class = "form error" for = "currentPassword" id = "currentPasswordLabel"></label>
            <br>
            <input class = "form validationInput" type="password" name = "args[password]" id = "password" placeholder="password" spellcheck="false" value="<?php echo $this->formData['args']['password'] ?? '';?>">
            <br>
            <label class = "form error" for = "password" id = "passwordLabel"></label>
            <br>
            <input class = "form validationInput" type="password" name = "args[confirmPassword]" id = "confirmPassword" placeholder="confirm password" spellcheck="false" value="<?php echo $this->formData['args']['confirmPassword'] ?? '';?>">
            <br>
            <label class = "form error" for = "confirmPassword" id = "confirmPasswordLabel"></label>
            <br>
        </div>
        <br>
        <br>
        <br>
        <div id="controlBtns">
            <button id="saveBtn" type="submit" name = "" class="squared green editElement disabled" disabled>✓ Save</button>
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