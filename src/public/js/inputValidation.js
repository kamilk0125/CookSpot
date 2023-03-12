
const loginInfoForm = document.querySelector('form.js-loginInfoForm')
const InputFields = loginInfoForm.querySelectorAll('input.js-validationInput');
const formSelectBtns = document.querySelectorAll('button.js-formSelector');
const submitBtn = loginInfoForm.querySelector('button[type="submit"]');
const passwordField = loginInfoForm.querySelector('#password');
const confirmPasswordField = loginInfoForm.querySelector('#confirmPassword');

InputFields.forEach(field => {field.addEventListener('input', validateInput)});
formSelectBtns.forEach(button => {button.addEventListener('click', selectForm)});

const patterns = {
    username: /^(?=.*[A-Z])[A-Z\d!#$%?&*]{6,20}$/i,
    displayName:  /^(?!\s)[^<>]{6,40}$/i,
    email: /^(?!\.)((\.)?[A-Z\d!#$%&'*+\-\/=?^_`{|}~]+)+@(?!\.)((\.)?[A-Z\d_]+)+(\.[A-Z\d]{2,3})$/i, 
    password: /^(?=.*[A-Z])(?=.*\d)[A-Z\d!@#$%?&*]{8,20}$/i
};

const errors = {
    username: 'Username length must be from 6 to 20 characters, can contain digits, special characters(!#$%?&*) and must have at least one letter',
    displayName: 'Display name must be from 6 to 40 characters long, cannot start from whitespace and contain characters: <>',
    email: 'Invalid email',
    password: 'Password length must be from 8 to 20 characters, can contain special characters(!#$%?&*) and must have at least one letter and digit',
    confirmPassword: 'Passwords are not the same'
}

function onLoad(){
    InputFields.forEach(field => {if(field.value !='') field.dispatchEvent(new Event('input'));});
}

function validateInput(e){
    let label = loginInfoForm.querySelector('#'+e.target.id+'Label');
    let validInput;
    let showErrorMsg;

    
    if(!loginInfoForm.classList.contains('js-notUsed')){
        if(e.target.id == 'confirmPassword'){
            validInput = (passwordField.value === e.target.value) && passwordField.classList.contains('js-valid');
            showErrorMsg = !(passwordField.value === e.target.value)
        }
        else{
            validInput = patterns[e.target.id].test(e.target.value);
            showErrorMsg = !validInput;
            if(e.target.id === 'password'){
                confirmPasswordField.dispatchEvent(new Event('input'));
            }
        }
        
        if(validInput){
            e.target.classList.remove('css-invalid', 'js-invalid');
            e.target.classList.add('css-valid', 'js-valid');
            submitBtn.disabled = !validateForm(loginInfoForm);
            submitBtn.disabled ? submitBtn.classList.add('css-disabled') : submitBtn.classList.remove('css-disabled');
            
        }
        else{
            e.target.classList.remove('css-valid', 'js-valid');
            e.target.classList.add('css-invalid', 'js-invalid');
            submitBtn.disabled = true;   
            submitBtn.classList.add('css-disabled');
        }

        if(showErrorMsg)
            label.textContent = errors[e.target.id];
        else
            label.textContent='';
    }
}

function validateForm(form){
    let formFields = form.querySelectorAll('input.js-validationInput');
    let validForm = true;
    
    formFields.forEach(field => {
        validForm = field.classList.contains('js-valid') && validForm;   
    })
    
    return validForm;
        
}

function selectForm(e){
    let selectedBtn = document.querySelector('button.js-selectedOption');
    if(selectedBtn.id != e.target.id){
        let newForm = document.getElementById(e.target.id.replace('Btn',''));
        let oldForm = document.getElementById(selectedBtn.id.replace('Btn',''));

        selectedBtn.classList.remove('js-selectedOption', 'css-selectedOption');
        e.target.classList.add('js-selectedOption', 'css-selectedOption');

        oldForm.classList.add('css-invisible', 'js-notUsed');
        newForm.classList.remove('css-invisible', 'js-notUsed');
    }
}

onLoad();