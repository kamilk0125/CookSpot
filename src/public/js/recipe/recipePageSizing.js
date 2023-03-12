function switchPageVersion(){
    let recipeContent = document.querySelector('#recipeContent');
    let mobileIngredientsNode = recipeContent.querySelector('#mobileIngredients');
    let ingredientsNode = recipeContent.querySelector('#ingredients');
    let ingredientsParentNode = ingredientsNode.parentNode;

    if(window.innerWidth < 1000){
        recipeContent.classList.remove('css-desktopVersion');
        if(ingredientsParentNode === recipeContent){
            
            mobileIngredientsNode.appendChild(ingredientsNode);
        }
    }
    else{
        recipeContent.classList.add('css-desktopVersion');
        if(ingredientsParentNode === mobileIngredientsNode){
            recipeContent.appendChild(ingredientsNode);
        }
    }
    document.querySelectorAll('textarea').forEach(textarea => textarea.dispatchEvent(new Event('input')));
}