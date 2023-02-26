<form id="settingsForm" class="loginInfoForm" action="profile?view=settings" method="POST" enctype="multipart/form-data">
    <input type="text" name="handler" value="profileInfoHandler" class="invisible">
    <div id="Settings">
        <div class="picture editable">
            <img id="profilePicture" class="roundedPicture uploadPicture" src="resource?type=img&path=<?php echo $this->profileData['profileInfo']['picturePath'] ?>" alt="Profile Picture">
            <button type="button" class="addPictureBtn squared editElement">+</button>
        </div>
        <br>      
        <div id="userInfo">
            <label class = "form error"><?php echo $this->errorMsg ?? '';?></label>
            <br>
            <div class="LabeledInput">
            <label class="form">Display Name:&emsp;</label>
            <input class = "form validationInput" type="text" name = "args[displayName]" id = "displayName" placeholder="display name" value="<?php echo $this->formData['args']['displayName'] ?? $this->profileData['profileInfo']['displayName'];?>">
            </div>
            
            <label class = "form error" for = "displayName" id = "displayNameLabel"></label>
            <br>
            <div class="LabeledInput">
            <label class="form">Email:&emsp;</label>
            <input class = "form validationInput" type="text" name = "args[email]" id = "email" placeholder="email" value="<?php echo $this->formData['args']['email'] ?? $this->profileData['profileInfo']['email'];?>">
            </div>

            <label class = "form error" for = "email" id = "emailLabel"></label>
            <br>
        </div>
        <br>
            <a href="login?view=changePassword"><button type="button" class="squared">Change password...</button></a>
        <br>
        <br>
        <br>
        <br>
        <div id="controlBtns">
            <button id="saveBtn" type="submit" name = "action" value="modifySettings" class="squared green editElement">✓ Save</button>
            <a href="/profile"><button id="discardBtn" type="button" class="squared red editElement">✗ Cancel</button></a>
        </div>
    </div>
    <?php 
        $imagePreviewSrc = 'resource?type=img&path=' . $this->profileData['profileInfo']['picturePath'];
        $fileInputName = 'profilePictureInfo';
        include(__DIR__ . '/../../Common/Components/Templates/ImageUploadPopup.php');
    ?>
</form>
<script src = "js/login.js"></script>
<script src = "js/settings.js"></script>
<script src = "js/imageUpload.js"></script>