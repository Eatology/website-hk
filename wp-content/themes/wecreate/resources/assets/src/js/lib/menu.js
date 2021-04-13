const mainMenu = () => {
    const hamburger = document.querySelector('.icon-burger-menu')    
    const nav = document.querySelector('#header-mobile-nav')
    const mainContent = document.querySelector('#main-content')
    hamburger.addEventListener("click", showMenu)

    function showMenu() {
        if(nav.classList.contains("nav-animation")) {
            nav.classList.remove("nav-animation")
            nav.classList.add("nav-animation-close")
            mainContent.classList.remove('body-overflow')
            hamburger.classList.remove('close-menu')    
        } else {
            nav.classList.remove("nav-animation-close")
            nav.classList.add("nav-animation")
            mainContent.classList.add('body-overflow')    
            hamburger.classList.add('close-menu')        
        } 
    }
    
    
}
export default mainMenu