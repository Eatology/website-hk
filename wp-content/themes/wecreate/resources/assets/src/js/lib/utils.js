export default (els) => {
    for (var i = 0; i < els.length; i++) {
        if(els[i].classList.contains("active")) {
            els[i].classList.remove('active')
        }          
    }
}    