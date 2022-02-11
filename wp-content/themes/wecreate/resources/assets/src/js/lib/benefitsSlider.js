import Flickity from 'flickity'

//home page slider
const partnersSlider = () => {
    if(document.querySelector('body.meal-benefits-slider')) {
      console.log('hello');
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

    var elem = document.querySelector('.meal-benefits-slider');
    if (elem) {
      var flkty = new Flickity( elem, {
        wrapAround: true,
        autoPlay: 5000,
        prevNextButtons: false,
        draggable: true,
        dragThreshold: 50,
        pauseAutoPlayOnHover: false,
        groupCells: groupCells
      });
    }
}
export default partnersSlider