<form id="settingsForm" class="js-loginInfoForm" action="/profile?view=settings" method="POST" enctype="multipart/form-data">
    <input type="text" name="handler" value="profileInfoHandler" class="css-invisible">
    <div id="Settings">
        <div class="css-picture css-editable">
            <img id="profilePicture" class="css-roundedPicture css-uploadPicture js-uploadPicture" src="/resource?type=img&path=<?php echo $this->profile->user->getUserData('picturePath') ?>" alt="Profile Picture">
            <button type="button" class="css-addPictureBtn js-addPictureBtn css-squared">＋</button>
        </div>
        <br>      
        <div id="userInfo">
            <label class = "css-form css-error"><?php echo $this->errorMsg;?></label>
            <br>
            <div class="css-labeledInput css-flexHorizontal">
                <div>
                    <input class = "css-form js-validationInput" type="text" name="args[displayName]" id="displayName" placeholder="display name" spellcheck="false" value="<?php echo $this->formData['args']['displayName'] ?? $this->profile->user->getUserData('displayName');?>">
                    <label class = "css-form css-error" for="displayName" id ="displayNameLabel"></label>
                </div>
            </div>            
            <br>
            <div class="css-labeledInput css-flexHorizontal">
                <div>
                    <input class = "css-form js-validationInput" type="text" name="args[email]" id="email" placeholder="email" spellcheck="false" value="<?php echo $this->formData['args']['email'] ?? $this->profile->user->getUserData('email');?>">
                    <label class = "css-form css-error" for="email" id="emailLabel"></label>
                </div>
            </div>
            <br>
        </div>
        <br>
        <a href="/login?view=changePassword"><button type="button" class="css-squared">Change password...</button></a>
        <br>
        <br>
        <br>
        <br>
        <div id="controlBtns">
            <button id="saveBtn" type="submit" name="action" value="modifySettings" class="css-squared css-green">✓ Save</button>
            <a href="/profile"><button id="discardBtn" type="button" class="css-squared css-red">✗ Cancel</button></a>
        </div>
    </div>
    <?php 
        $imagePreviewSrc = '/resource?type=img&path=' . $this->profile->user->getUserData('picturePath');
        $fileInputName = 'profilePictureInfo';
        include(__DIR__ . '/../../Common/Components/Templates/ImageUploadPopup.php');
    ?>
</form>
<script src = "js/inputValidation.js"></script>
<script src = "js/imageUpload.js"></script>