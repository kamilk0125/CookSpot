autosizeTextareas(document.getElementsByTagName("textarea"));

function autosizeTextareas(textareas){
    for (let i = 0; i < textareas.length; i++) {
        textareas[i].setAttribute("style", "height: 0px;");
        textareas[i].setAttribute("style", "height:" + (textareas[i].scrollHeight) + "px;");
        textareas[i].addEventListener("input", OnInput, false);
      }
}

function OnInput() {
    this.style.height = 0;
    this.style.height = (this.scrollHeight) + "px";
}