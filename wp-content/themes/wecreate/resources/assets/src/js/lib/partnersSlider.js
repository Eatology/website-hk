import Flickity from 'flickity'

//home page slider
const partnersSlider = () => {
    if(document.querySelector('body.partners-slider')) {
      return;
    }
    var elem = document.querySelector('.partners-slider');
    if (elem) {
      var flkty = new Flickity( elem, {
        wrapAround: true,
        // autoPlay: 5000,
        prevNextButtons: true,
        draggable: true,
        pauseAutoPlayOnHover: false
      });
    }
}
export default partnersSlider