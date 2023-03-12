const listTileTemplate = document.querySelector('#listTileTemplate');
const ingredientsList = document.querySelector('#ingredientsList');
const addIngredientBtn = document.querySelector('#newIngredientBtn');
const deleteIngredientBtns = ingredientsList.querySelectorAll('button.js-deleteBtn');

addIngredientBtn.addEventListener('click', newIngredient);
deleteIngredientBtns.forEach(button => button.addEventListener('click', deleteIngredient));

function newIngredient(){
    let listTile = listTileTemplate.content.cloneNode(true);
    let elementNr = ingredientsList.querySelectorAll('div.js-listTile').length;
    let deleteBtn = listTile.querySelector('button.js-deleteBtn');
    let tileHeader = listTile.querySelector('div.js-listTile > textarea');
    tileHeader.setAttribute('name', 'args[recipeInfo][ingredients]['+elementNr+']');
    tileHeader.addEventListener('input', validateRecipe);
    tileHeader.innerText = 'Ingredient '+(elementNr+1);
    ingredientsList.appendChild(listTile);
    deleteBtn.addEventListener('click', deleteIngredient);
    let appendedListTile = ingredientsList.querySelectorAll('div.js-listTile')[elementNr];

    autosizeTextareas(appendedListTile.querySelectorAll('textarea'));
    toggleEditMode(appendedListTile, true);
    validateRecipe();
}

function deleteIngredient(e){
    let ingredientTile = e.target.parentNode.parentNode;
    ingredientTile.parentNode.removeChild(ingredientTile);
    renumberFormElements(ingredientsList, 'div.js-listTile > textarea', 'args[recipeInfo][ingredients][$elementNr]');
    validateRecipe();
}