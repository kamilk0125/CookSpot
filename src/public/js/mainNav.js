const mainNavLinks = document.querySelectorAll('#mainNav > a');
mainNavLinks.forEach(link => {
    let linkRef = link.getAttribute('href');
    if(location.pathname.startsWith(linkRef)){
        link.classList.add('css-active');
    }
})

