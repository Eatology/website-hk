import removeClasses from './utils'

const faqAccordion = () => {

    Array.from(document.querySelectorAll(".question-group__answer > a")).forEach(function(tab) {
      tab.addEventListener("click", function (e) {
        e.preventDefault()
        if(tab.classList.contains("active")) {
          tab.classList.remove("active")
          tab.nextSibling.classList.remove("active")
        }
        else {
          tab.classList.add("active")
          tab.nextSibling.classList.add("active")

          return
        }
      })

    })


    Array.from(document.querySelectorAll(".faq-nav > a")).forEach(function(tabHeading) {
      tabHeading.addEventListener("click", function (e) {
        e.preventDefault()
        smoothScroll(tabHeading)
        return
      })
    })

    function smoothScroll(tabHeading) {
      var element = document.querySelector(tabHeading.getAttribute("href"));
      var headerOffset = 160;
      var elementPosition = element.getBoundingClientRect().top;
      var offsetPosition = elementPosition - headerOffset;

      window.scrollTo({
          top: offsetPosition,
          behavior: "smooth"
      });
    }



}
export default faqAccordion


