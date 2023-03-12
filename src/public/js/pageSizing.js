const header = document.querySelector('#header');
const footer = document.querySelector('#footer');
const mainNav = document.querySelector('#mainNav');

updateWindowSize();
if(typeof switchPageVersion === 'function') switchPageVersion();
window.addEventListener('resize', () => {updateWindowSize(); if(typeof switchPageVersion === 'function') switchPageVersion()})

function updateWindowSize(){
    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
    
    let pageContentMinHeight = window.innerHeight - header.offsetHeight - footer.offsetHeight;
    document.documentElement.style.setProperty('--pageContentMinHeight', `${pageContentMinHeight}px`);
    let innerPageContentMinHeight = pageContentMinHeight - mainNav.offsetHeight;
    document.documentElement.style.setProperty('--innerPageContentMinHeight', `${innerPageContentMinHeight}px`);
}
