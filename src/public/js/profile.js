const multiContainers = document.querySelectorAll('div.multiContainer');

multiContainers.forEach(multiContainer => {
    Btns = multiContainer.querySelectorAll('button');

    Btns.forEach(btn => {btn.addEventListener('click', switchContainer)})
})

function switchContainer(e){
    btn = e.target;
    multiContainer = btn.parentNode.parentNode;
    multiContainerBtns = multiContainer.querySelectorAll('button');
    btnName = btn.getAttribute('name');
    targetContainerName = btnName.slice(0,-3);
    containers = multiContainer.querySelectorAll('div.container');
    console.log(multiContainer);
    console.log(containers);
    containers.forEach(container => {
        containerName = container.getAttribute('name');
        if(containerName === targetContainerName){
            container.classList.remove('invisible');
        }
        else{
            container.classList.add('invisible');
        }
    })
    multiContainerBtns.forEach(btn => {
        btn.classList.remove('active');
    })
    btn.classList.add('active');
}