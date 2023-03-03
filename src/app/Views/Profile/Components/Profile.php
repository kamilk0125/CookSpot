<div id="profileInfo" class = "flexHorizontal">
    <div class="picture editable">
      <img id="profilePicture" class="roundedPicture" src="resource?type=img&path=<?php echo $this->profileData['profileInfo']['picturePath'] ?>" alt="Profile Picture">
      <a href="profile?view=settings" class="<?php echo $this->profileData['publicProfile'] ? 'invisible' : '';?>"><button class="addPictureBtn squared editElement">⚙</button></a>
    </div>
    <div>
      <h1 id="profileDisplayName"><?php echo $this->profileData['profileInfo']['displayName'] ?></h1>
    </div>
    <div>
      <button 
        id="friendsBtn"
        form="friendsForm" 
        name="<?php echo $this->friendsForm['btnName'] ?? '';?>" 
        type="submit" 
        value="<?php echo $this->friendsForm['btnValue'] ?? '';?>" 
        class="rounded <?php echo $this->friendsForm['btnClass'] ?? '';?>" 
        <?php echo $this->friendsForm['btnClass'] ?? '';?>
        ><?php echo $this->friendsForm['btnText'];?>
      </button>
      <button id="deleteFriendBtn" 
        form="deleteFriendForm" 
        name="args[friendId]" 
        type="submit" 
        value="<?php echo $this->profileData['profileInfo']['id'] ?>" 
        class="rounded red <?php echo ($this->relationStatus === 'friend') ? '' : 'invisible';?>" 
        >Delete from friends list
      </button>    
    </div>
</div>
<div class="multiContainer <?php echo $this->profileData['publicProfile'] ? 'invisible' : '';?>">
  <div class="containerToggle topNav">
    <button name="leftContainerBtn" class=" active">My Recipes</button>
    <button name="rightContainerBtn">Shared with me</button>
  </div>
  <div name="leftContainer" class="container itemContainer">
    <a href="profile?view=newRecipe"><button class="squared">+ New Recipe...</button></a>
    <div class="recipeList">
      <?php
        foreach(array_reverse($this->profileData['userRecipes'] ?? [], true) as $recipe){
          $checkboxClass = 'invisible';
          $redirectLink = 'profile?view=recipe&id=' . $recipe->id;
          $ownerInfo = '';
          $detailText = '⌛ ' . $recipe->preparationTime;
          $imageSrc = 'resource?type=img&path=' . $recipe->picturePath;
          $headerText = $recipe->name;
          $descriptionText = $recipe->description;
          include(__DIR__ . '/../../Common/Components/Templates/Tile.php');
        }
      ?>
    </div>
  </div>
  <div name="rightContainer" class="container itemContainer invisible">
    <div class="recipeList">
      <?php
        foreach(array_reverse($this->profileData['sharedRecipes'] ?? [], true) as $itemId => $recipe){
          $checkboxClass = 'invisible';
          $redirectLink = 'share?view=recipe&id=' . $itemId;
          $ownerInfo = $recipe['shareInfo']['ownerName'];
          $detailText = '⌛ ' . $recipe['recipeContent']->preparationTime;
          $imageSrc = 'resource?type=shared&id=' . $recipe['shareInfo']['pictureId'];
          $headerText = $recipe['recipeContent']->name;
          $descriptionText = $recipe['recipeContent']->description;
          include(__DIR__ . '/../../Common/Components/Templates/Tile.php');
        }
      ?>
    </div>
  </div>
</div>
<div class="multiContainer <?php echo $this->profileData['publicProfile'] ? '' : 'invisible';?>">
  <div class="containerToggle topNav">
    <button name="leftContainerBtn" class=" active">Shared with me</button>
    <button name="rightContainerBtn">Shared by me</button>
  </div>
  <div name="leftContainer" class="container itemContainer">
    <div class="recipeList">
      <?php
        foreach(array_reverse($this->profileData['sharedRecipes'] ?? [], true) as $itemId => $recipe){
          $checkboxClass = 'invisible';
          $redirectLink = 'share?view=recipe&id=' . $itemId;
          $ownerInfo = '';
          $detailText = '⌛ ' . $recipe['recipeContent']->preparationTime;
          $imageSrc = 'resource?type=shared&id=' . $recipe['shareInfo']['pictureId'];
          $headerText = $recipe['recipeContent']->name;
          $descriptionText = $recipe['recipeContent']->description;
          include(__DIR__ . '/../../Common/Components/Templates/Tile.php');
        }
      ?>
    </div>
  </div>
  <div name="rightContainer" class="container itemContainer invisible">
    <div class="recipeList">
      <?php
        foreach(array_reverse($this->profileData['sharedCurrentUserRecipes'] ?? [], true) as $recipe){
          $checkboxClass = 'invisible';
          $redirectLink = 'profile?view=recipe&id=' . $recipe->id;
          $ownerInfo = '';
          $detailText = '⌛ ' . $recipe->preparationTime;
          $imageSrc = 'resource?type=img&path=' . $recipe->picturePath;
          $headerText = $recipe->name;
          $descriptionText = $recipe->description;
          include(__DIR__ . '/../../Common/Components/Templates/Tile.php');
        }
      ?>
    </div>
  </div>
</div>
<form id="friendsForm" action="<?php echo $this->profileData['publicProfile'] ? '' : 'friends';?>" method="POST">
    <input class="invisible" type="text" name="<?php echo $this->profileData['publicProfile'] ? 'handler' : '';?>" value="friendsHandler">
    <input class="invisible" type="text" name="<?php echo $this->profileData['publicProfile'] ? 'action' : '';?>" value="<?php echo $this->friendsForm['action'] ?? '';?>">
    <?php 
        foreach(($this->friendsForm['args'] ?? []) as $argName => $value){
            echo "<input class='invisible' type='text' name='{$argName}' value='{$value}'>";
        }
    ?>
</form>
<form id="deleteFriendForm" action="" method="POST">
    <input class="invisible" type="text" name="handler" value="friendsHandler">
    <input class="invisible" type="text" name="action" value="deleteFriend">
</form>
<script src="js/profile.js"></script>

