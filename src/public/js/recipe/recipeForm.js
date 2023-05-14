const editRecipeBtn = document.querySelector('#editRecipeBtn');
const saveBtn = document.querySelector('#saveRecipeBtn');
const discardBtn = document.querySelector('#discardRecipeBtn');
const recipeName = document.querySelector('#recipeName');
const recipeDescription = document.querySelector('#recipeDescription');

editRecipeBtn.addEventListener('click', e => {toggleEditMode(document, true);});
discardBtn.addEventListener('click', discardChanges);
recipeName.addEventListener('input', validateRecipe);
recipeDescription.addEventListener('input', validateRecipe);
validateRecipe();

function validateRecipe(){
    let recipeNameValue = recipeName.value;
    let recipeDescriptionValue = recipeDescription.value;
    let instructions = document.querySelectorAll('#instructionsList > div.js-listTileExp');
    let ingredients = document.querySelectorAll('#ingredientsList > div.js-listTile');
    let instructionsCount = instructions.length;
    let ingredientsCount = ingredients.length;

    let validInstructions = true;
    instructions.forEach(instruction => {
        let headerText = instruction.querySelector('div.js-listTileHeader > textarea').value;
        if(headerText.length === 0)
            validInstructions = false;
    });
    let validIngredients = true;
    ingredients.forEach(ingredient => {
        let headerText = ingredient.querySelector('div.js-listTile > textarea').value;
        if(headerText.length === 0)
            validIngredients = false;
    });

    if(
        validInstructions && validIngredients &&
        recipeNameValue.length > 0 && recipeNameValue.length < 80 &&
        recipeDescriptionValue.length < 300 &&
        instructionsCount > 0 && ingredientsCount > 0
    ){
        saveBtn.classList.remove('css-disabled');
        saveBtn.disabled = false;
    }
    else{
        saveBtn.classList.add('css-disabled');
        saveBtn.disabled = true;
    }

}

function toggleEditMode(element, editEnable){  
    let editElements = element.querySelectorAll('button.js-editElement');
    let editFields = element.querySelectorAll('textarea.js-editElement');
    let listTiles = element.querySelectorAll('div.css-listTile');
    if(editEnable){
        editElements.forEach(element => {element.classList.remove('css-invisible')});
        editFields.forEach(element => {element.disabled=false; element.dispatchEvent(new Event('input'));});
        listTiles.forEach(element => {element.classList.add('css-editMode')});
        editRecipeBtn.classList.add('css-invisible');
    }
    else{
        editElements.forEach(element => {element.classList.add('css-invisible')});
        editFields.forEach(element => {element.disabled=true});
        listTiles.forEach(element => {element.classList.remove('css-editMode')});
        editRecipeBtn.classList.remove('css-invisible');
    }

}

function renumberFormElements(elementsList, elementSelector, nameTemplate){
    let elements = elementsList.querySelectorAll(elementSelector);
    let elementNr = 0;
    elements.forEach(elem => {elem.setAttribute('name', nameTemplate.replace('$elementNr', elementNr)); elementNr++;});   
}

function discardChanges(e){
    if(e.target.classList.contains("js-redirectBtn")){
        location.href='profile';
    }
    else{
        location.reload();
    }
}
