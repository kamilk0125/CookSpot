<label class = "form error <?php echo ($this->errorMsg === '') ? 'invisible' : '' ?>"><?php echo $this->errorMsg ?></label>
<div id="profileInfo" class = "flexHorizontal">
    <div class="picture editable">
      <img id="profilePicture" class="roundedPicture" src="resource?type=img&path=<?php echo $this->userInfo['picturePath'] ?>" alt="Profile Picture">
      <a href="profile?view=settings"><button class="addPictureBtn squared editElement">⚙</button></a>
    </div>
    <div>
      <h1 id="profileDisplayName"><?php echo $this->userInfo['displayName'] ?></h1>
    </div>
    <a id="friendsBtn" href="friends"><button class="squared">Friends</button></a>
</div>
<div class="topNav">
  <button class="active">My Recipes</button>
  <button>Shared with me</button>
</div>
<div id="myRecipesContainer" class="itemContainer">
  <a href="profile?view=newRecipe"><button class="squared">+ New Recipe...</button></a>
  <a href="profile?view=newRecipe"><button class="squared">★ Share...</button></a>
  <div id="recipeList">
    <?php
      foreach($this->profileManager->recipes as $recipe){
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

