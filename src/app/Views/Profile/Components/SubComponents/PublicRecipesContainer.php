<div class="js-multiContainer">
  <div class="css-containerToggle css-topNav css-flexHorizontal">
    <button name="leftContainerBtn" class="css-active">Shared with me</button>
    <button name="rightContainerBtn">Shared by me</button>
  </div>
  <div name="leftContainer" class="js-container css-container css-itemContainer">
    <div class="css-recipeList">
      <?php
        foreach(array_reverse($this->profileData['sharedRecipes'] ?? [], true) as $itemId => $recipe){
          $checkboxClass = 'css-invisible';
          $redirectLink = 'share?view=recipe&id=' . $itemId;
          $tileInfo = '';
          $detailText = '⌛ ' . $recipe['recipeContent']->preparationTime;
          $imageSrc = 'resource?type=shared&id=' . $recipe['shareInfo']['pictureId'];
          $headerText = $recipe['recipeContent']->name;
          $descriptionText = $recipe['recipeContent']->description;
          include(__DIR__ . '/../../../Common/Components/Templates/Tile.php');
        }
      ?>
    </div>
  </div>
  <div name="rightContainer" class="js-container css-container css-itemContainer css-invisible">
    <div class="css-recipeList">
      <?php
        foreach(array_reverse($this->profileData['sharedCurrentUserRecipes'] ?? [], true) as $recipe){
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
</div>