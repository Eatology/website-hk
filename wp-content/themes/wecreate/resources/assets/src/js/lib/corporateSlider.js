import Flickity from 'flickity'

//home page slider
const corporateSlider = () => {
    if(document.querySelector('body.corporate-slider')) {
      return;
    }
    var elem = document.querySelector('.corporate-slider');
    if (elem) {
      var flkty = new Flickity( elem, {
        wrapAround: true,
        autoPlay: 5000,
        prevNextButtons: true,
        draggable: true,
        dragThreshold: 50,
        pauseAutoPlayOnHover: false
      });
    }
}
export default corporateSlider