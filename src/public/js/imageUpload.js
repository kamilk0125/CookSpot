const maxFileSizeMB = 10;
const fileInput = document.querySelector('div.uploadPopup > div.popupBody > div > div.fileSelector > input[type="file"]');
const fileLabel = document.querySelector('div.fileSelector > p');
const actualImage = document.getElementById('recipePicture');
const imagePreview = document.getElementById('imagePreview');
const uploadDiscardBtn = document.querySelector('div.uploadPopup > div.popupHeader > button');
const uploadBtn = document.querySelector('div.popupBody > div > button');
fileInput.addEventListener('change', imageUpload);

function imageUpload(e) {
    let filePath = e.target.value;
    let allowedExtensionsPattern = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    
    if (e.target.files && e.target.files[0]) {
        let filesizeMB = e.target.files[0].size/1024/1024;
        if(!allowedExtensionsPattern.test(filePath)){
            fileLabel.innerText = 'Invalid File';
            fileLabel.style.color = 'red';
            e.target.value = '';
            uploadBtn.disabled = true;
            uploadBtn.classList.add('disabled');
        }
        else if(filesizeMB>maxFileSizeMB){
            fileLabel.innerText = 'File size is larger than '+maxFileSizeMB+' MB';
            fileLabel.style.color = 'red';
            e.target.value = '';
            uploadBtn.disabled = true;
            uploadBtn.classList.add('disabled');
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
            uploadBtn.classList.remove('disabled');
        }
    }

}