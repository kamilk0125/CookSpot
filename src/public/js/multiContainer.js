const multiContainers = document.querySelectorAll('div.js-multiContainer');

multiContainers.forEach(multiContainer => {
    let Btns = multiContainer.querySelectorAll('button');
    Btns.forEach(btn => {btn.addEventListener('click', switchContainer)})
})

function switchContainer(e){
    let btn = e.target;
    let multiContainer = btn.parentNode.parentNode;
    let multiContainerBtns = multiContainer.querySelectorAll('button');
    let btnName = btn.getAttribute('name');
    let targetContainerName = btnName.slice(0,-3);
    let containers = multiContainer.querySelectorAll('div.js-container');
    containers.forEach(container => {
        let containerName = container.getAttribute('name');
        if(containerName === targetContainerName)
            container.classList.remove('css-invisible');
        else
            container.classList.add('css-invisible');
    })
    multiContainerBtns.forEach(btn => {
        btn.classList.remove('css-active');
    })
    btn.classList.add('css-active');
}