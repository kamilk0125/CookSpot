const listTileTemplate = document.querySelector('#listTileTemplate');
const listTileExpTemplate = document.querySelector('#listTileExpTemplate');
const instructionsList = document.querySelector('#instructionsList');
const ingredientsList = document.querySelector('#ingredientsList');
const addButtons = document.querySelectorAll('.addButton');
const listTileExpBtns = document.querySelectorAll('.listTile.expandButton');
const deleteTileBtns = ingredientsList.querySelectorAll('div.deleteButton > button');
const deleteTileExpBtns = instructionsList.querySelectorAll('div.deleteButton > button');
const editRecipeBtn = document.querySelector('#editRecipeBtn');
const discardBtn = document.querySelector('#discardRecipeBtn');
const addPictureBtn = document.querySelector('button.addPictureBtn');
const recipePicture = document.querySelector('#recipePicture');
let uploadedFile;

addButtons.forEach(button => {button.addEventListener('click', addTile)});
listTileExpBtns.forEach(button => {button.addEventListener('click', expListTile)});
deleteTileBtns.forEach(button => button.addEventListener('click', deleteTile));
deleteTileExpBtns.forEach(button => button.addEventListener('click', deleteTileExp));
editRecipeBtn.addEventListener('click', e => {toggleEditMode(document, true);});
discardBtn.addEventListener('click', discardChanges);
addPictureBtn.addEventListener('click', openUploadPopup);
autosizeTextareas(document.getElementsByTagName("textarea"));


function addTile(e){
    let deleteBtn;
    let header;
    let appendedListTile;
    let elementNr;

    switch(e.target.id){
        case 'newIngredientBtn':
            let listTile = listTileTemplate.content.cloneNode(true);
            elementNr = document.querySelectorAll('#ingredientsList > div.listTile').length;
            deleteBtn = listTile.querySelector('div.deleteButton > button');
            header = listTile.querySelector('div.listTile > textarea');
            header.setAttribute('name', 'recipeForm[ingredients]['+elementNr+']');
            header.innerText = 'Ingredient '+(elementNr+1);
            ingredientsList.appendChild(listTile);
            appendedListTile = ingredientsList.querySelectorAll('div.listTile')[elementNr];
            deleteBtn.addEventListener('click', deleteTile);
            break;
        case 'newInstructionBtn':
            let listTileExp = listTileExpTemplate.content.cloneNode(true);
            elementNr = document.querySelectorAll('#instructionsList > div.listTileExp').length;
            deleteBtn = listTileExp.querySelector('div.deleteButton > button');
            header = listTileExp.querySelector('div.listTile.listTileHeader > textarea');
            let expBtn = listTileExp.querySelector('div.listTile > div > button.expandButton');
            let description = listTileExp.querySelector('div.listTile.listTileDetails > textarea');
            expBtn.addEventListener('click', expListTile);
            header.setAttribute('name', 'recipeForm[instructions]['+elementNr+'][header]');
            header.innerText = 'Step '+(elementNr+1);
            description.setAttribute('name', 'recipeForm[instructions]['+elementNr+'][description]');
            description.innerText = '';
            instructionsList.appendChild(listTileExp);
            appendedListTile = instructionsList.querySelectorAll('div.listTileExp')[elementNr];
            deleteBtn.addEventListener('click', deleteTileExp);
            break;
    }
    autosizeTextareas(appendedListTile.querySelectorAll('textarea'));
    toggleEditMode(appendedListTile, true);
    
}

function expListTile(e) {
    let listTileHeader =e.target.parentNode.parentNode
    let listTile = listTileHeader.parentNode;
    
    description = listTile.querySelector('.listTileDetails');
    if(listTileHeader.classList.contains('expanded')){
        e.target.innerText = '﹀';
        listTileHeader.classList.remove('expanded');
        description.classList.add('invisible');
    }
    else{
        e.target.innerText = '︿';
        listTileHeader.classList.add('expanded');
        description.classList.remove('invisible');
    }


}

function openUploadPopup(e) {
    let uploadPopup = document.querySelector('#uploadPopup');
    let backgroundOverlay = document.querySelector('#backgroundOverlay');

    uploadPopup.classList.remove('invisible');
    backgroundOverlay.classList.remove('invisible');

    let closeBtn = uploadPopup.querySelector('div.popupHeader > button');
    let fileInput = uploadPopup.querySelector('div.fileSelector > input');
    closeBtn.addEventListener('click', e => {
        uploadPopup.classList.add('invisible');
        backgroundOverlay.classList.add('invisible');
        fileInput.value = '';
        if(uploadedFile){
            let dataTransfer = new DataTransfer();
            dataTransfer.items.add(uploadedFile);
            fileInput.files = dataTransfer.files;
        }
    });

    let uploadBtn = uploadPopup.querySelector('div.popupBody > div > button');
    let preselectedImage = uploadPopup.querySelector('div.popupBody > img');

    uploadBtn.addEventListener('click', e => {
        recipePicture.setAttribute('src', preselectedImage.getAttribute('src'));
        uploadPopup.classList.add('invisible');
        backgroundOverlay.classList.add('invisible');
        uploadedFile = fileInput.files[0];
    });
}


function autosizeTextareas(textareas){
    for (let i = 0; i < textareas.length; i++) {
        textareas[i].setAttribute("style", "height:" + (textareas[i].scrollHeight) + "px;");
        textareas[i].addEventListener("input", OnInput, false);
      }
}

function OnInput() {
    this.style.height = 0;
    this.style.height = (this.scrollHeight) + "px";
}

function toggleEditMode(element, editEnable){  
    let editElements = element.querySelectorAll('div.editElement, button.editElement');
    let editFields = element.querySelectorAll('textarea.editElement');
    let listTiles = element.querySelectorAll('div.listTile');
    if(editEnable){
        editElements.forEach(element => {element.classList.remove('invisible')});
        editFields.forEach(element => {element.disabled=false});
        listTiles.forEach(element => {element.classList.add('editMode')});
        if(typeof element.classList !== 'undefined')
            if(element.classList.contains('listTile')){
                element.classList.add('editMode');
            }
        editRecipeBtn.classList.add('invisible');
    }
    else{
        editElements.forEach(element => {element.classList.add('invisible')});
        editFields.forEach(element => {element.disabled=true});
        listTiles.forEach(element => {element.classList.remove('editMode')});
        if(typeof element.classList !== 'undefined')
            if(element.classList.contains('listTile')){
                element.classList.remove('editMode');
            }
        editRecipeBtn.classList.remove('invisible');
    }

}

function removeElement(element){
    element.parentNode.removeChild(element);
}

function renumberFormElements(elementsList, elementSelector, nameTemplate){
    elements = elementsList.querySelectorAll(elementSelector);
    elementNr = 0;
    elements.forEach(elem => {elem.setAttribute('name', nameTemplate.replace('$elementNr', elementNr)); elementNr++;});   
}

function discardChanges(e){
    if(e.target.classList.contains("redirectBtn")){
        location.href='profile';
    }
    else{
        location.reload();
    }
}

function deleteTile(e){
    tile = e.target.parentNode.parentNode;
    removeElement(tile); 
    renumberFormElements(ingredientsList, 'div.listTile > textarea', 'recipeForm[ingredients][$elementNr]');
}

function deleteTileExp(e){
    tile = e.target.parentNode.parentNode.parentNode;
    removeElement(tile); 
    renumberFormElements(instructionsList, 'div.listTile.listTileHeader > textarea', 'recipeForm[instructions][$elementNr][header]');
    renumberFormElements(instructionsList, 'div.listTile.listTileDetails > textarea', 'recipeForm[instructions][$elementNr][description]');
}


