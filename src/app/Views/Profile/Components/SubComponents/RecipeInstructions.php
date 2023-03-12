<div id="instructions" class = "css-itemContainer">
    <div class="css-containerHeader">
        <h2>Instructions</h2>
    </div>
    <div id="instructionsList" class = "">
        <?php 
            foreach($this->recipe->instructions as $key=>$instruction){
                $headerName = 'args[recipeInfo][instructions]['.$key.'][header]';
                $headerText =  $instruction['header'];
                $descriptionName = 'args[recipeInfo][instructions]['.$key.'][description]';
                $descriptionText = $instruction['description'];
                include __DIR__ . '/../../../Common/Components/Templates/ListTileExp.php';
            }
        ?>
    </div>
    <div><button id="newInstructionBtn" type="button" class="css-squared css-addButton js-editElement <?php echo $this->newRecipe ? '' : 'css-invisible'; ?>">+ New Step...</button></div>
</div>