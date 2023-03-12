const recipesTiles = document.querySelectorAll('#ownedRecipesList > a.js-tile');
const friendsTiles = document.querySelectorAll('#friendsList > div.js-selectionTile');
const allRecipesCheckbox = document.querySelector('#allRecipesBtn');
const allFriendsCheckbox = document.querySelector('#allFriendsBtn');
const shareBtn = document.querySelector('#shareBtn');


recipesTiles.forEach(tile => {tile.addEventListener('click', e => {checkTile(e.target, allRecipesCheckbox)})});
friendsTiles.forEach(tile => {tile.addEventListener('click', e => {checkTile(e.target, allFriendsCheckbox)})});
allRecipesCheckbox.addEventListener('click', e => {recipesTiles.forEach(tile => {checkTile(tile, allRecipesCheckbox, allRecipesCheckbox.checked)})});
allFriendsCheckbox.addEventListener('click', e => {friendsTiles.forEach(tile => {checkTile(tile, allFriendsCheckbox, allFriendsCheckbox.checked)})});
validateForm();

function checkTile(tile, allTilesCheckbox, state){
    let checkboxSelector = 'input[type="checkbox"]';
    let checkbox = tile.querySelector(checkboxSelector);
    checkbox.checked = state ?? !checkbox.checked;
    if(checkbox.checked){
        tile.classList.add('js-checked');
    }
    else{
        tile.classList.remove('js-checked');
        allTilesCheckbox.checked = false;
    }
    validateForm();
}

function validateForm(){
    let checkedRecipesTiles = document.querySelectorAll('#ownedRecipesList > a.js-tile.js-checked');
    let checkedFriendsTiles = document.querySelectorAll('#friendsList > div.js-selectionTile.js-checked');
    
    if(checkedRecipesTiles.length>0 && checkedFriendsTiles.length>0){
        shareBtn.classList.remove('css-disabled');
        shareBtn.disabled = false;
    }
    else{
        shareBtn.classList.add('css-disabled');
        shareBtn.disabled = true;
    }
}



