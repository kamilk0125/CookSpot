<form id="deleteForm" action="profile" method="POST">
  <input class="invisible" name="handler" type="text" value="recipesHandler">
  <input class="invisible" name="args[recipeId]" type="text" value="<?php echo  $this->recipe->id ?>">
</form>
<form id="recipeForm" action="<?php echo "profile?view=" . ($this->newRecipe ? 'newRecipe' : "recipe&id={$this->recipe->id}");?>" method="POST" enctype="multipart/form-data">
  <input class="invisible" name="handler" type="text" value="recipesHandler">
  <input class="invisible" name="args[recipeInfo][recipeId]" type="text" value="<?php echo  $this->recipe->id ?>">
  <div id="recipeContent" class="editMode">
    <div id="leftSide" class="itemContainer">
      <div>
        <div id="topInfoBar" class="flexHorizontal">
          <button form="deleteForm" class="squared red invisible <?php echo $this->newRecipe ? '' : 'editElement'; ?>" type="submit" name="action" value="removeRecipe">✘ Delete Recipe</button>
          <div id="preparationTime" class="flexHorizontal">
            <h2>⌛ Preparation time: </h2>
            <textarea class="editElement" name="args[recipeInfo][preparationTime]" cols="10" rows="1" maxlength="10" spellcheck="false" <?php echo $this->newRecipe ? '' : 'disabled'; ?>><?php echo $this->recipe->preparationTime ?></textarea>
          </div>
        </div>
        <label id="errorMsg" class = "form error"><?php echo $this->errorMsg ?? '';?></label>
        <div id="Info" class="itemContainer">
            <div class="picture editable">
              <img id="recipePicture" class="uploadPicture roundedPicture greenOutline" src="<?php echo $this->pictureSrc ?>" alt="Recipe Picture">
              <button type="button" class="addPictureBtn squared editElement <?php echo $this->newRecipe ? '' : 'invisible'; ?>">+</button>
            </div>      
            <div>
              <textarea class="editElement" name="args[recipeInfo][name]" id="recipeName" cols="20" rows="1" maxlength="80" <?php echo $this->newRecipe ? '' : 'disabled'; ?>><?php echo $this->recipe->name ?></textarea>
              <textarea class="editElement" name="args[recipeInfo][description]" id="recipeDescription" cols="35" rows="1" maxlength="300" <?php echo $this->newRecipe ? '' : 'disabled'; ?>><?php echo $this->recipe->description ?></textarea>
            </div>
            <div id="controlBtns">
              <button id="editRecipeBtn" type="button" class="squared <?php echo ($this->newRecipe || $this->readOnly) ? 'invisible' : ''; ?>">✎ Edit</button>
              <button id="saveRecipeBtn" type="submit" name = "action" class="squared green editElement <?php echo $this->newRecipe ? '' : 'invisible'; ?>" value="<?php echo $this->newRecipe ? 'addNewRecipe' : 'modifyRecipe'; ?>">✓ Save</button>
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
                    $headerName = 'args[recipeInfo][instructions]['.$key.'][header]';
                    $headerText =  $instruction['header'];
                    $descriptionName = 'args[recipeInfo][instructions]['.$key.'][description]';
                    $descriptionText = $instruction['description'];
                    include __DIR__ . '/../../Common/Components/Templates/ListTileExp.php';
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
                    $headerName = 'args[recipeInfo][ingredients]['.$key.']';
                    $headerText =  $ingredient;
                    include __DIR__ . '/../../Common/Components/Templates/ListTile.php';
                }
            ?> 
          </div>
          <div><button id="newIngredientBtn" type="button" class="squared addButton editElement <?php echo $this->newRecipe ? '' : 'invisible'; ?>">+ New Ingredient...</button></div>
    </div>
  </div>
  <?php 
      $imagePreviewSrc = 'resource?type=img&path=' . $this->recipe->picturePath;
      $fileInputName = 'recipePictureInfo';
      include(__DIR__ . '/../../Common/Components/Templates/ImageUploadPopup.php');
  ?>

</form>
<template id="listTileTemplate"><?php include __DIR__ . '/../../Common/Components/Templates/ListTile.php'; ?></template>
<template id="listTileExpTemplate"><?php include __DIR__ . '/../../Common/Components/Templates/ListTileExp.php'; ?></template>
<script src = "js/recipe.js"></script>
<script src = "js/imageUpload.js"></script>