const mainNavLinks = document.querySelectorAll('#mainNav > a');
mainNavLinks.forEach(link => {
    linkRef = link.getAttribute('href');
    if(location.pathname.startsWith(linkRef)){
        link.classList.add('active');
    }
})