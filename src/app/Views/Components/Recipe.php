<form id="recipeForm" action="profile" method="POST" enctype="multipart/form-data">
  <div id="recipeContent" class="editMode">
    <div id="leftSide" class="itemContainer">
      <div class="itemContainer">
        <div id="topInfoBar" class="flexHorizontal">
          <a href="profile"><button type="button" class="squared">« Profile</button></a>
          <button class="squared red invisible <?php echo $this->newRecipe ? '' : 'editElement'; ?>" type="submit" name="recipeForm[submit]" value="delete">✘ Delete Recipe</button>
          <div id="preparationTime" class="flexHorizontal">
            <h2>⌛ Preparation time: </h2>
            <textarea class="editElement" name="recipeForm[preparationTime]" cols="10" rows="1" maxlength="10" spellcheck="false" <?php echo $this->newRecipe ? '' : 'disabled'; ?>><?php echo $this->recipe->preparationTime ?></textarea>
          </div>
          <input class="invisible" name="recipeForm[id]" type="text" value="<?php echo  $this->recipe->id ?>">
        </div>
        <div id="Info">
            <div class="picture editable">
              <img id="recipePicture" class="roundedPicture greenOutline" src="resource?type=img&path=<?php echo $this->recipe->picturePath ?>" alt="Recipe Picture">
              <button type="button" class="addPictureBtn squared editElement <?php echo $this->newRecipe ? '' : 'invisible'; ?>">+</button>
            </div>      
            <div>
              <textarea class="editElement" name="recipeForm[name]" id="recipeName" cols="20" rows="1" maxlength="80" <?php echo $this->newRecipe ? '' : 'disabled'; ?>><?php echo $this->recipe->name ?></textarea>
              <textarea class="editElement" name="recipeForm[description]" id="recipeDescription" cols="35" rows="1" maxlength="300" <?php echo $this->newRecipe ? '' : 'disabled'; ?>><?php echo $this->recipe->description ?></textarea>
            </div>
            <div id="controlBtns">
              <button id="editRecipeBtn" type="button" class="squared <?php echo ($this->newRecipe || $this->readOnly) ? 'invisible' : ''; ?>">✎ Edit</button>
              <button id="saveRecipeBtn" type="submit" name = "recipeForm[submit]" class="squared green editElement <?php echo $this->newRecipe ? '' : 'invisible'; ?>" value="<?php echo $this->newRecipe ? 'newRecipe' : $this->recipe->id; ?>">✓ Save</button>
              <button id="discardRecipeBtn" type="button" class="squared red editElement <?php echo $this->newRecipe ? 'redirectBtn' : 'invisible'; ?>">✗ Cancel</button>
            </div>
        </div>
      </div>

      <div id="instructions" class = "itemContainer">
          <div class="ContainerHeader">
            <h2>Instructions</h2>
          </div>
          <div id="instructionsList" class = "listContainer">
            <?php 
                foreach($this->recipe->instructions as $key=>$instruction){
                    $headerName = 'recipeForm[instructions]['.$key.'][header]';
                    $headerText =  $instruction['header'];
                    $descriptionName = 'recipeForm[instructions]['.$key.'][description]';
                    $descriptionText = $instruction['description'];
                    include 'Templates/ListTileExp.php';
                }
            ?>
          </div>
          <div><button id="newInstructionBtn" type="button" class="squared addButton editElement <?php echo $this->newRecipe ? '' : 'invisible'; ?>">+ New Step...</button></div>
      </div>    
    </div>
    <div id="ingredients" class="itemContainer">
          <div class="ContainerHeader">
            <h2>Ingredients</h2>
          </div>
          <div id="ingredientsList" class="listContainer">
            <?php 
                foreach($this->recipe->ingredients as $key=>$ingredient){
                    $headerName = 'recipeForm[ingredients]['.$key.']';
                    $headerText =  $ingredient;
                    include 'Templates/ListTile.php';
                }
            ?> 
          </div>
          <div><button id="newIngredientBtn" type="button" class="squared addButton editElement <?php echo $this->newRecipe ? '' : 'invisible'; ?>">+ New Ingredient...</button></div>
    </div>
  </div>
  <?php 
      $imagePreviewSrc = 'resource?type=img&path=' . $this->recipe->picturePath;
      $fileInputName = 'recipePicture';
      include('Templates/ImageUploadPopup.php');
  ?>

</form>
<template id="listTileTemplate"><?php include 'Templates/ListTile.php'; ?></template>
<template id="listTileExpTemplate"><?php include 'Templates/ListTileExp.php'; ?></template>
<script src = "js/recipe.js"></script>
<script src = "js/imageUpload.js"></script>