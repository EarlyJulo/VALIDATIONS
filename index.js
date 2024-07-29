const form = document.getElementById('form');
const fullname = document.getElementById('fullname');
const gender = document.getElementById('gender');
const age = document.getElementById('age');
const password = document.getElementById('password');
const passwordRepeat = document.getElementById('passwordRepeat');

form.addEventListener('submit', e => {
    e.preventDefault();
    validateInputs();
});

const setError = (element, message) => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = message;
    inputControl.classList.add('error');
    inputControl.classList.remove('success');
}

const setSuccess = element => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.querySelector('.error');

    errorDisplay.innerText = '';
    inputControl.classList.add('success');
    inputControl.classList.remove('error');
}

const validateInputs = () => {
    const fullnameValue = fullname.value.trim();
    const genderValue = gender.value.trim();
    const ageValue = age.value.trim();
    const passwordValue = password.value.trim();
    const passwordRepeatValue = passwordRepeat.value.trim();

    if(fullnameValue === ''){
        setError(fullname, 'Fullname is required');
    } else {
        setSuccess(fullname);
    }

    if(genderValue === ''){
        setError(gender, 'Please select gender');
    } else {
        setSuccess(gender);
    }

    if(ageValue === ''){
        setError(age, 'Age is required');
    } else {
        setSuccess(age);
    }

    if(passwordValue === ''){
        setError(password, 'Password is required');
    } else if (passwordValue.length < 8) {
        setError(password, 'Password must be at least 8 characters long');
    } else {
        setSuccess(password);
    }

    if(passwordRepeatValue === ''){
        setError(passwordRepeat, 'Please confirm your password');
    } else if(passwordRepeatValue !== passwordValue){
        setError(passwordRepeat, 'Passwords do not match');
    } else {
        setSuccess(passwordRepeat);
    }
}
