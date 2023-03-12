<div id="profileInfo" class = "css-flexHorizontal">
    <div class="css-picture css-editable">
      <img id="profilePicture" class="css-roundedPicture" src="resource?type=img&path=<?php echo $this->profileData['profileInfo']['picturePath'] ?>" alt="Profile Picture">
      <a href="profile?view=settings" class="<?php echo $this->profileData['publicProfile'] ? 'css-invisible' : '';?>">
        <button class="css-addPictureBtn css-squared css-editElement js-addPictureBtn">⚙</button> 
      </a>
    </div>
    <div>
      <h1 id="profileDisplayName"><?php echo $this->profileData['profileInfo']['displayName'] ?></h1>
    </div>
    <?php if($this->profileData['publicProfile']) include(__DIR__ . '/SubComponents/FriendsForm.php'); ?>
</div>
<?php
    if($this->profileData['publicProfile'])
      include (__DIR__ . '/SubComponents/PublicRecipesContainer.php');
    else
      include (__DIR__ . '/SubComponents/PrivateRecipesContainer.php');
?>
<script src="js/multiContainer.js"></script>

