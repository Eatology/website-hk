const myAccount = () => {

    var elem = document.querySelector('.page-template-my-account-php');
    if (!elem) {
        return
    }
    
    const signUpButton = document.getElementById('sign-up-button');
    const signInButton = document.getElementById('sign-in-button');
    const signInSection = document.getElementById('sign-in-section');
    const signUpSection = document.getElementById('sign-up-section');

    if (!signUpButton || !signInButton) {
        return
    }    

    if(window.location.hash && window.location.hash == '#register') {
        signInSection.classList.remove("visible")
        signInSection.classList.remove("active")
        signUpSection.classList.add("active")
        signUpSection.classList.add("visible")
    }
  
  
    signUpButton.addEventListener("click", function (e) {
        e.preventDefault()
        window.location.hash = '#register'
        signInSection.classList.remove("visible")
        signInSection.classList.remove("active")
        signUpSection.classList.add("active")
        signUpSection.classList.add("visible")
        return
    })

    signInButton.addEventListener("click", function (e) {
        e.preventDefault()
        window.location.hash = ''
        signUpSection.classList.remove("visible")
        signUpSection.classList.remove("active")
        signInSection.classList.add("active")
        signInSection.classList.add("visible")
        return
    })    

    const registerButton = document.getElementById('my-account__register-button');

    if (!registerButton) {
        return
    }       

    registerButton.addEventListener("click", function (e) {
        const tcError = document.querySelector('.check_tc-error')

        if(document.getElementById('check_tc').checked === false) {
            e.preventDefault()
            tcError.classList.add("visible")
            return false
        }  else {
            tcError.classList.remove("visible")
        }
        return
    })     






}
export default myAccount


