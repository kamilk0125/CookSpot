<div id="profileInfo" class = "flexHorizontal">
    <img id="profilePicture" class="roundedPicture" src="resource?type=img&path=<?php echo $this->profile->getProfilePicturePath() ?>" alt="Profile Picture">
    <div>
      <h1 id="profileDisplayName"><?php echo $this->profile->displayName ?></h1>
      <p id="profileDescription"><?php echo $this->profile->description ?></p>
    </div>
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
      foreach($this->profile->myRecipes as $recipe){
        $redirectLink = 'profile?view=recipe&id=' . $recipe->id;
        $detailText = '⌛ ' . $recipe->preparationTime;
        $imageSrc = 'resource?type=img&path=' . $recipe->picturePath;
        $headerText = $recipe->name;
        $descriptionText = $recipe->description;
        include('Templates/Tile.php');
      }
    ?>
  </div>
</div>

