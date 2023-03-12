<div id="ingredients" class="css-itemContainer">
    <div class="css-containerHeader">
        <h2>Ingredients</h2>
    </div>
    <div id="ingredientsList" class="">
        <?php 
            foreach($this->recipe->ingredients as $key=>$ingredient){
                $headerName = 'args[recipeInfo][ingredients]['.$key.']';
                $headerText =  $ingredient;
                include __DIR__ . '/../../../Common/Components/Templates/ListTile.php';
            }
        ?> 
    </div>
    <div><button id="newIngredientBtn" type="button" class="css-squared css-addButton js-editElement <?php echo $this->newRecipe ? '' : 'css-invisible'; ?>">+ New Ingredient...</button></div>
</div>