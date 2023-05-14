<form action="/share" method="POST">
    <div id="layout" class="css-flexVertical">
        <div id="controlBtns" class="css-flexHorizontal css-itemContainer"> 
            <div id="Info"><label class = "css-form <?php echo $this->recipesShared ? '' : 'css-error';?>"><?php echo $this->infoText;?></label></div>
            <button id="shareBtn" type="submit" name="" class="css-squared">★ Share</button>   
        </div>
        <div id="mainContent" class="css-flexHorizontal">
            <input type="text" name="handler" value="shareHandler" class="css-invisible">
            <input type="text" name="action" value="shareRecipes" class="css-invisible">
            <div id="leftSide" class="css-itemContainer">
                <div id="ownedRecipes">
                    <div class="css-containerHeader">
                        <h2>Select recipes to share</h2>
                        <div class="options">
                            <input id="allRecipesBtn" type="checkbox">
                            <label>All</label>
                        </div>
                    </div>
                    <div id="ownedRecipesList" class="css-itemContainer">
                        <?php
                            foreach(array_reverse($this->ownedRecipes) as $recipe){
                                $checkboxName = "args[recipesId][]";
                                $checkboxValue = $recipe->id;
                                $redirectLink = null;
                                $detailText = '⌛ ' . $recipe->preparationTime;
                                $imageSrc = '/resource?type=img&path=' . $recipe->picturePath;
                                $headerText = $recipe->name;
                                $descriptionText = $recipe->description;
                                include(__DIR__ . '/../../Common/Components/Templates/Tile.php');
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div id="friends" class = "css-itemContainer">
                <div class="css-containerHeader"><h2>Share with</h2></div>
                    <div class="options">
                        <input id="allFriendsBtn" type="checkbox">
                        <label>All</label>
                    </div>
                <div id="friendsList" class = "css-listContainer">
                        <?php
                        foreach($this->friendsList as $friend){
                            $checkboxName = "args[usersId][]";
                            $checkboxValue = $friend['id'];
                            $redirectLink = "javascript:void(0)";
                            $imageSrc = '/resource?type=img&path=' . $friend['picturePath'];
                            $headerText = $friend['displayName'];
                            include(__DIR__ . '/../../Common/Components/Templates/SelectionListTile.php');
                        }
                        ?>
                </div>    
            </div>
        </div>
    </div>
</form>
<script src="js/shareRecipes.js"></script>
