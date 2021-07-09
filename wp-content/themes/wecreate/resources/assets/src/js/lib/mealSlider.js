import Flickity from 'flickity'

//home page slider
const mealSlider = () => {
    if(document.querySelector('body.meal-slider')) {
      return;
    }

    // check the browser width and set the groupcell value
    var size = {
      width: window.innerWidth || document.body.clientWidth,
      height: window.innerHeight || document.body.clientHeight
    }

    var groupCells = 3;
    if(size.width <= 1199)
    {
      groupCells = 2;
    }

    var elem = document.querySelector('.meal-slider');
    if (elem) {
      var flkty = new Flickity( elem, {
        wrapAround: true,
        autoPlay: 5000,
        prevNextButtons: true,
        draggable: true,
        dragThreshold: 50,
        pauseAutoPlayOnHover: false,
        groupCells: groupCells
      });
    }
}
export default mealSlider