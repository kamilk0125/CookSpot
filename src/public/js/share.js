const recipesTiles = document.querySelectorAll('#ownedRecipesList > a.Tile');
const friendsTiles = document.querySelectorAll('#friendsList > div.listTile');
const allRecipesCheckbox = document.querySelector('#allRecipesBtn');
const allFriendsCheckbox = document.querySelector('#allFriendsBtn');
const shareBtn = document.querySelector('#shareBtn');


recipesTiles.forEach(tile => {tile.addEventListener('click', e => {checkTile(e.target, allRecipesCheckbox)})});
friendsTiles.forEach(tile => {tile.addEventListener('click', e => {checkTile(e.target, allFriendsCheckbox)})});
allRecipesCheckbox.addEventListener('click', e => {recipesTiles.forEach(tile => {checkTile(tile, allRecipesCheckbox, allRecipesCheckbox.checked)})});
allFriendsCheckbox.addEventListener('click', e => {friendsTiles.forEach(tile => {checkTile(tile, allFriendsCheckbox, allFriendsCheckbox.checked)})});
validateForm();

function checkTile(tile, allTilesCheckbox, state){
    checkboxSelector = 'input[type="checkbox"]';
    checkbox = tile.querySelector(checkboxSelector);
    checkbox.checked = state ?? !checkbox.checked;
    if(checkbox.checked){
        tile.classList.add('checked');
    }
    else{
        tile.classList.remove('checked');
        allTilesCheckbox.checked = false;
    }
    validateForm();
}

function validateForm(){
    checkedRecipesTiles = document.querySelectorAll('#ownedRecipesList > a.Tile.checked');
    checkedFriendsTiles = document.querySelectorAll('#friendsList > div.listTile.checked');
    
    if(checkedRecipesTiles.length>0 && checkedFriendsTiles.length>0){
        shareBtn.classList.remove('disabled');
        shareBtn.disabled = false;
    }
    else{
        shareBtn.classList.add('disabled');
        shareBtn.disabled = true;
    }
}



