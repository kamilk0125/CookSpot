const allowedExtensionsPattern = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
const maxFileSizeMB = 10;
const addPictureBtn = document.querySelector('button.js-addPictureBtn');
const backgroundOverlay = document.querySelector('div.js-backgroundOverlay');
const imageUploadPopup = document.querySelector('div.js-imageUploadPopup'); 
const actualImage = document.querySelector('img.js-uploadPicture');
let uploadedImage;

addPictureBtn.addEventListener('click', openImageUploadPopup);

function imageUpload(e) {
    let filePath = e.target.value;
    let fileLabel = imageUploadPopup.querySelector('div.js-fileSelector > p');
    let imagePreview = imageUploadPopup.querySelector('div.js-popupBody > img.js-imagePreview');
    let uploadBtn = imageUploadPopup.querySelector('div.js-popupBody > div > button');
    
    if (e.target.files && e.target.files[0]) {
        let filesizeMB = e.target.files[0].size/1024/1024;
        if(!allowedExtensionsPattern.test(filePath)){
            fileLabel.innerText = 'Invalid File';
            fileLabel.style.color = 'red';
            e.target.value = '';
            uploadBtn.disabled = true;
            uploadBtn.classList.add('css-disabled');
        }
        else if(filesizeMB>maxFileSizeMB){
            fileLabel.innerText = 'File size is larger than '+maxFileSizeMB+' MB';
            fileLabel.style.color = 'red';
            e.target.value = '';
            uploadBtn.disabled = true;
            uploadBtn.classList.add('css-disabled');
        }
        else{
            let reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.setAttribute('src', e.target.result);
            };
            reader.readAsDataURL(e.target.files[0]);
            fileLabel.innerText = e.target.files[0].name;
            fileLabel.style.color = 'black';
            uploadBtn.disabled = false;
            uploadBtn.classList.remove('css-disabled');
        }
    }

}

function openImageUploadPopup() {
    let uploadBtn = imageUploadPopup.querySelector('div.js-popupBody > div > button');

    imageUploadPopup.classList.remove('css-invisible');
    backgroundOverlay.classList.remove('css-invisible');

    let fileInput = imageUploadPopup.querySelector('div.js-popupBody > div > div.js-fileSelector > input[type="file"]');
    let closeBtn = imageUploadPopup.querySelector('div.js-popupHeader > button');

    fileInput.addEventListener('change', imageUpload);
    closeBtn.addEventListener('click', e => {
        imageUploadPopup.classList.add('css-invisible');
        backgroundOverlay.classList.add('css-invisible');
        fileInput.value = '';
        if(uploadedImage){
            let dataTransfer = new DataTransfer();
            dataTransfer.items.add(uploadedImage);
            fileInput.files = dataTransfer.files;
        }
    });
    uploadBtn.addEventListener('click', updateActualImage);
}

function updateActualImage(){
    let imagePreview = imageUploadPopup.querySelector('div.js-popupBody > img.js-imagePreview');
    let fileInput = imageUploadPopup.querySelector('div.js-popupBody > div > div.js-fileSelector > input[type="file"]');
    actualImage.setAttribute('src', imagePreview.getAttribute('src'));
    imageUploadPopup.classList.add('css-invisible');
    backgroundOverlay.classList.add('css-invisible');
    uploadedImage = fileInput.files[0];
}