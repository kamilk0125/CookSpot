<div id="profileInfo" class = "flexHorizontal">
    <div class="picture editable">
      <img id="profilePicture" class="roundedPicture" src="resource?type=img&path=<?php echo $this->profileData['profileInfo']['picturePath'] ?>" alt="Profile Picture">
      <a href="profile?view=settings" class="<?php echo $this->profileData['publicProfile'] ? 'invisible' : '';?>"><button class="addPictureBtn squared editElement">⚙</button></a>
    </div>
    <div>
      <h1 id="profileDisplayName"><?php echo $this->profileData['profileInfo']['displayName'] ?></h1>
    </div>
    <button 
      id="friendsBtn"
      form="friendsForm" 
      name="<?php echo $this->friendsForm['btnName'] ?? '';?>" 
      type="submit" 
      value="<?php echo $this->friendsForm['btnValue'] ?? '';?>" 
      class="rounded <?php echo $this->friendsForm['btnClass'] ?? '';?>" 
      <?php echo $this->friendsForm['btnClass'] ?? '';?>>
      <?php echo $this->friendsForm['btnText'];?>
    </button>    
</div>
<div class="<?php echo $this->profileData['publicProfile'] ? 'invisible' : '';?>">
  <div class="topNav">
    <button class="active">My Recipes</button>
    <button>Shared with me</button>
  </div>
  <div id="myRecipesContainer" class="itemContainer">
    <a href="profile?view=newRecipe"><button class="squared">+ New Recipe...</button></a>
    <a href="profile?view=newRecipe"><button class="squared">★ Share...</button></a>
    <div id="recipeList">
      <?php
        foreach(array_reverse($this->profileData['userRecipes'] ?? []) as $recipe){
          $redirectLink = 'profile?view=recipe&id=' . $recipe->id;
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
<div class="<?php echo $this->profileData['publicProfile'] ? '' : 'invisible';?>">
  <div class="topNav">
    <button class="active">Shared with me</button>
    <button class="">Shared by me</button>
  </div>
  <div id="myRecipesContainer" class="itemContainer">
    <div id="recipeList">
      <?php

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


