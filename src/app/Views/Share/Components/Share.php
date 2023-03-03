<form action="share" method="POST">
<div id="layout">
    <input type="text" name="handler" value="shareHandler" class="invisible">
    <div id="leftSide" class="itemContainer">
        <div id="ownedRecipes">
            <div id="controlBtns">
                <button id="shareBtn" type="submit" name="action" value="shareRecipes" class="squared">★ Share</button>
            </div>
            <div id="Info"><label class = "form <?php echo $this->recipesShared ? '' : 'error';?>"><?php echo $this->infoText;?></label></div>
            <div class="ContainerHeader"><h2>Select recipes to share</h2></div>
            <div id="options">
                <input id="allRecipesBtn" type="checkbox">
                <label>All</label>
            </div>
            <div id="ownedRecipesList" class="itemContainer">
                <?php
                    foreach(array_reverse($this->ownedRecipes) as $recipe){
                        $checkboxName = "args[recipesId][]";
                        $checkboxValue = $recipe->id;
                        $redirectLink = 'javascript:void(0)';
                        $detailText = '⌛ ' . $recipe->preparationTime;
                        $imageSrc = 'resource?type=img&path=' . $recipe->picturePath;
                        $headerText = $recipe->name;
                        $descriptionText = $recipe->description;
                        include(__DIR__ . '/../../Common/Components/Templates/Tile.php');
                    }
                ?>
            </div>
        </div>
    </div>
    <div id="friends" class = "itemContainer">
        <div class="ContainerHeader"><h2>Share with</h2></div>
        <div id="options">
                <input id="allFriendsBtn" type="checkbox">
                <label>All</label>
            </div>
        <div id="friendsList" class = "listContainer">
                <?php
                foreach($this->friendsList as $friend){
                    $checkboxName = "args[usersId][]";
                    $checkboxValue = $friend['id'];
                    $redirectLink = "javascript:void(0)";
                    $imageSrc = 'resource?type=img&path=' . $friend['picturePath'];
                    $headerText = $friend['displayName'];
                    include(__DIR__ . '/../../Common/Components/Templates/SelectionListTile.php');
                }
                ?>
        </div>    
    </div>
</div>
</form>
<script src="js/share.js"></script>
