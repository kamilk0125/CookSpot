const listTileExpTemplate = document.querySelector('#listTileExpTemplate');
const instructionsList = document.querySelector('#instructionsList');
const addInstructionBtn = document.querySelector('#newInstructionBtn');
const listTileExpBtns = document.querySelectorAll('button.js-expandButton');
const deleteInstructionBtns = instructionsList.querySelectorAll('button.js-deleteBtn');

addInstructionBtn.addEventListener('click', newInstruction);
listTileExpBtns.forEach(button => {button.addEventListener('click', expListTile)});
deleteInstructionBtns.forEach(button => button.addEventListener('click', deleteInstruction));

function newInstruction(){
    let listTileExp = listTileExpTemplate.content.cloneNode(true);
    let elementNr = instructionsList.querySelectorAll('div.js-listTileExp').length;
    let deleteBtn = listTileExp.querySelector('button.js-deleteBtn');
    let tileHeader = listTileExp.querySelector('div.js-listTileHeader > textarea');
    tileHeader.addEventListener('input', validateRecipe);
    let expBtn = listTileExp.querySelector('button.js-expandButton');
    let description = listTileExp.querySelector('div.js-listTileDetails > textarea');
    expBtn.addEventListener('click', expListTile);
    tileHeader.setAttribute('name', 'args[recipeInfo][instructions]['+elementNr+'][header]');
    tileHeader.innerText = 'Step '+(elementNr+1);
    description.setAttribute('name', 'args[recipeInfo][instructions]['+elementNr+'][description]');
    description.innerText = '';
    instructionsList.appendChild(listTileExp);
    let appendedListTile = instructionsList.querySelectorAll('div.js-listTileExp')[elementNr];
    deleteBtn.addEventListener('click', deleteInstruction);

    autosizeTextareas(appendedListTile.querySelectorAll('textarea'));
    toggleEditMode(appendedListTile, true);
    validateRecipe();
    
}

function expListTile(e) {
    let listTileHeader = e.target.parentNode.parentNode;
    let listTile = listTileHeader.parentNode;
    
    let description = listTile.querySelector('div.js-listTileDetails');
    if(listTileHeader.classList.contains('js-expanded', 'css-expanded')){
        e.target.innerText = '﹀';
        listTileHeader.classList.remove('js-expanded', 'css-expanded');
        description.classList.add('css-invisible');
    }
    else{
        e.target.innerText = '︿';
        listTileHeader.classList.add('js-expanded', 'css-expanded');
        description.classList.remove('css-invisible');
    }


}

function deleteInstruction(e){
    let instructionTile = e.target.parentNode.parentNode.parentNode;
    instructionTile.parentNode.removeChild(instructionTile);
    renumberFormElements(instructionsList, 'div.js-listTileHeader > textarea', 'args[recipeInfo][instructions][$elementNr][header]');
    renumberFormElements(instructionsList, 'div.js-listTileDetails > textarea', 'args[recipeInfo][instructions][$elementNr][description]');
    validateRecipe();
}