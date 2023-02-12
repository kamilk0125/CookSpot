
const loginInfoForm = document.querySelector('form.loginInfoForm')
const InputFields = loginInfoForm.querySelectorAll('input.validationInput');
const formSelectBtns = document.querySelectorAll('button.formSelector');

InputFields.forEach(field => {field.addEventListener('input', validateInput)});
formSelectBtns.forEach(button => {button.addEventListener('click', selectForm)});

const patterns = {
    username: /^(?=.*[A-Z])[A-Z\d!#$%?&*]{6,20}$/i,
    displayName:  /^(?!\s)[^<>]{6,20}$/i,
    email: /^(?!\.)((\.)?[A-Z\d!#$%&'*+\-\/=?^_`{|}~]+)+@(?!\.)((\.)?[A-Z\d_]+)+(\.[A-Z\d]{2,3})$/i, 
    password: /^(?=.*[A-Z])(?=.*\d)[A-Z\d!@#$%?&*]{8,20}$/i
};

const errors = {
    username: 'Username length must be from 6 to 20 characters, can contain digits, special characters(!#$%?&*) and must have at least one letter',
    displayName: 'Display name must be from 6 to 20 characters long, cannot start from whitespace and contain characters: <>',
    email: 'Invalid email',
    password: 'Password length must be from 8 to 20 characters, can contain special characters(!#$%?&*) and must have at least one letter and digit',
    confirmPassword: 'Passwords are not the same'
}

function onLoad(){
    InputFields.forEach(field => {if(field.value !='') field.dispatchEvent(new Event('input'));});
}

function validateInput(e){
    let validInput;
    const submitBtn = loginInfoForm.querySelector('button[type="submit"]');
    const label = loginInfoForm.querySelector('#'+e.target.id+'Label');
    const passwordField = loginInfoForm.querySelector('#password');
    const confirmPasswordField = loginInfoForm.querySelector('#confirmPassword');
    
        if(!loginInfoForm.classList.contains('invisible')){
            if(e.target.id == 'confirmPassword'){
                validInput = (passwordField.value == e.target.value) && !passwordField.classList.contains('invalid');
            }
            else{
                validInput = patterns[e.target.id].test(e.target.value);
                if(e.target.id == 'password'){
                    if(validInput)
                        confirmPasswordField.dispatchEvent(new Event('input'));
                    else
                        confirmPasswordField.classList.add('invalid')
                }
            }
            
            
            if(validInput){
                e.target.classList.remove('invalid');
                e.target.classList.add('valid');
                label.textContent='';
                submitBtn.disabled = !validateForm(loginInfoForm);
                submitBtn.disabled ? submitBtn.classList.add('disabled') : submitBtn.classList.remove('disabled');
                
            }
            else{
                e.target.classList.remove('valid');
                if(e.target.value != ''){
                    e.target.classList.add('invalid');
                    label.textContent = errors[e.target.id];
                }
                submitBtn.disabled = true;   
                submitBtn.classList.add('disabled');
            }
    }
}

function validateForm(form){
    let formFields = form.querySelectorAll('input.validationInput');
    let validForm = true;
    
    formFields.forEach(field => {
        validForm = field.classList.contains('valid') && validForm;   
    })
    
    return validForm;
        
}

function selectForm(e){
    let selectedBtn = document.querySelector('button.selectedOption');
    if(selectedBtn.id != e.target.id){
        let newForm = document.getElementById(e.target.id.replace('Btn',''));
        let oldForm = document.getElementById(selectedBtn.id.replace('Btn',''));

        selectedBtn.classList.remove('selectedOption');
        e.target.classList.add('selectedOption');

        oldForm.classList.add('invisible');
        newForm.classList.remove('invisible');
    }
}

onLoad();