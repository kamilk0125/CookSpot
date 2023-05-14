<form id="deleteForm" action="/profile" method="POST">
  <input class="css-invisible" name="handler" type="text" value="recipesHandler">
  <input class="css-invisible" name="action" type="text" value="removeRecipe">
  <input class="css-invisible" name="args[recipeId]" type="text" value="<?php echo  $this->recipe->id ?>">
</form>
<form id="recipeForm" action="<?php echo "/profile?view=" . ($this->newRecipe ? 'newRecipe' : "recipe&id={$this->recipe->id}");?>" method="POST" enctype="multipart/form-data">
  <input class="css-invisible" name="handler" type="text" value="recipesHandler">
  <input class="css-invisible" name ="action" type="text" value="<?php echo $this->newRecipe ? 'addNewRecipe' : 'modifyRecipe'; ?>">
  <input class="css-invisible" name="args[recipeInfo][recipeId]" type="text" value="<?php echo  $this->recipe->id ?>">
  <div id="recipeContent" class="css-desktopVersion css-editMode">
    <div id="leftSide" class="css-itemContainer">
      <?php include (__DIR__ . '/SubComponents/RecipeInfo.php'); ?>
      <div id="mobileIngredients"></div>
      <?php include (__DIR__ . '/SubComponents/RecipeInstructions.php'); ?>
    </div>
    <?php include (__DIR__ . '/SubComponents/RecipeIngredients.php'); ?>
  </div>
  <?php 
      $imagePreviewSrc = '/resource?type=img&path=' . $this->recipe->picturePath;
      $fileInputName = 'recipePictureInfo';
      include(__DIR__ . '/../../Common/Components/Templates/ImageUploadPopup.php');
  ?>
</form>
<template id="listTileTemplate"><?php include __DIR__ . '/../../Common/Components/Templates/ListTile.php'; ?></template>
<template id="listTileExpTemplate"><?php include __DIR__ . '/../../Common/Components/Templates/ListTileExp.php'; ?></template>
<script src = "js/autosizeTextAreas.js"></script>
<script src = "js/recipe/recipeForm.js"></script>
<script src = "js/recipe/recipeIngredients.js"></script>
<script src = "js/recipe/recipeInstructions.js"></script>
<script src = "js/imageUpload.js"></script>
<script src = "js/recipe/recipePageSizing.js"></script>