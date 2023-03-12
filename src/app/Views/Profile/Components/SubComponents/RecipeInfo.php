<div>
    <div id="topInfoBar" class="css-flexHorizontal">
        <div id="controlBtns">
                <button id="editRecipeBtn" type="button" class="css-squared <?php echo ($this->newRecipe || $this->readOnly) ? 'css-invisible' : ''; ?>">✎ Edit</button>
                <button id="saveRecipeBtn" type="submit" name = "" class="css-squared css-green  js-editElement <?php echo $this->newRecipe ? '' : 'css-invisible'; ?>">✓ Save</button>
                <button id="discardRecipeBtn" type="button" class="css-squared css-red js-editElement <?php echo $this->newRecipe ? 'js-redirectBtn' : 'css-invisible'; ?>">✗ Cancel</button>
        </div>
        <div id="preparationTime" class="css-flexHorizontal">
            <h2>⌛ Preparation time: </h2>
            <textarea class="js-editElement" name="args[recipeInfo][preparationTime]" cols="10" rows="1" maxlength="10" spellcheck="false" <?php echo $this->newRecipe ? '' : 'disabled'; ?>><?php echo $this->recipe->preparationTime ?></textarea>
            <button form="deleteForm" class="css-squared css-red css-invisible <?php echo $this->newRecipe ? '' : 'js-editElement'; ?>" type="submit" name="">✘ Delete Recipe</button>
        </div>
    </div>
    <label id="errorMsg" class = "css-form css-error"><?php echo $this->errorMsg ?? '';?></label>
    <div id="Info" class="css-itemContainer css-flexHorizontal">
            <div class="css-picture css-editable">
                <img id="recipePicture" class="css-uploadPicture css-roundedPicture css-greenOutline js-uploadPicture" src="<?php echo $this->pictureSrc ?>" alt="Recipe Picture">
                <button type="button" class="css-addPictureBtn js-addPictureBtn css-squared js-editElement <?php echo $this->newRecipe ? '' : 'css-invisible'; ?>">＋</button>
            </div>      
            <div>
                <textarea class=" js-editElement" name="args[recipeInfo][name]" id="recipeName" cols="" rows="1" maxlength="80" spellcheck="false" <?php echo $this->newRecipe ? '' : 'disabled'; ?>><?php echo $this->recipe->name ?></textarea>
                <textarea class="js-editElement" name="args[recipeInfo][description]" id="recipeDescription" cols="" rows="1" maxlength="300" spellcheck="false" <?php echo $this->newRecipe ? '' : 'disabled'; ?>><?php echo $this->recipe->description ?></textarea>
            </div>
    </div>
</div>