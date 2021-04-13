import Flickity from 'flickity'

//home page slider
const customersSlider = () => {
    if(document.querySelector('body.customers-slider')) {
      return;
    }
    var elem = document.querySelector('.customers-slider');
    if (elem) {
      var flkty = new Flickity( elem, {
        wrapAround: true,
        autoPlay: 5000,
        prevNextButtons: false,
        draggable: true,
        dragThreshold: 50,
        pauseAutoPlayOnHover: false
      });
    }
}
export default customersSlider