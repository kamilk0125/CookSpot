<div class="js-multiContainer">
  <div class="css-containerToggle css-topNav css-flexHorizontal">
    <button name="leftContainerBtn" class="css-active">My Recipes</button>
    <button name="rightContainerBtn">Shared with me</button>
  </div>
  <div name="leftContainer" class="js-container css-container css-itemContainer">
    <a href="profile?view=newRecipe"><button class="css-squared">+ New Recipe...</button></a>
    <div class="css-recipeList">
      <?php
        foreach(array_reverse($this->profile->userRecipes, true) as $recipe){
          $checkboxClass = 'css-invisible';
          $redirectLink = 'profile?view=recipe&id=' . $recipe->id;
          $tileInfo = '';
          $detailText = '⌛ ' . $recipe->preparationTime;
          $imageSrc = 'resource?type=img&path=' . $recipe->picturePath;
          $headerText = $recipe->name;
          $descriptionText = $recipe->description;
          include(__DIR__ . '/../../../Common/Components/Templates/Tile.php');
        }
      ?>
    </div>
  </div>
  <div name="rightContainer" class="js-container css-container css-itemContainer css-invisible">
    <div class="css-recipeList">
      <?php
        foreach(array_reverse($this->profile->sharedRecipes, true) as $sharedItem){
          $checkboxClass = 'css-invisible';
          $redirectLink = 'share?view=recipe&id=' . $sharedItem->id;
          $tileInfo = $sharedItem->ownerName;
          $detailText = '⌛ ' . $sharedItem->content['recipe']->preparationTime;
          $imageSrc = 'resource?type=shared&id=' . $sharedItem->content['pictureId'];
          $headerText = $sharedItem->content['recipe']->name;
          $descriptionText = $sharedItem->content['recipe']->description;
          include(__DIR__ . '/../../../Common/Components/Templates/Tile.php');
        }
      ?>
    </div>
  </div>
</div>