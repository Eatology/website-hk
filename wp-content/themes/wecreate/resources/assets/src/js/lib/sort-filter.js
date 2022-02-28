const products = () => {

    let sortDrop = document.querySelectorAll('.js-optionDropdown > li');

    function sortMeBy(arg, sel, order) {
        var $selector = document.querySelector(sel),
            items = Array.prototype.slice.call($selector.children);

        items.sort(function(a, b) {
            var an = parseInt(a.getAttribute(arg)),
                bn = parseInt(b.getAttribute(arg));

            if (order == 'asc') {
                if (an > bn) return 1;
                if (an < bn) return -1;
            } else if (order == 'desc') {
                if (an < bn) return 1;
                if (an > bn) return -1;
            }
            return 0;
        });

        for(var i = 0, len = items.length; i < len; i++) {
            var parent = items[i].parentNode;
            var detatchedItem = parent.removeChild(items[i]);
            parent.appendChild(detatchedItem);
        }

    }
        
    sortDrop.forEach(el => {
        el.addEventListener('click', e => {
            sortDrop.forEach(el => {
                el.classList.remove('active');
            });
            e.target.classList.add('active');

            if (e.target.dataset.sort == 'popular') {
                sortMeBy('data-popular', '.js-card-list', 'desc');
            }

            if (e.target.dataset.sort == 'low') {
                sortMeBy('data-price', '.js-card-list', 'asc');
            }

            if (e.target.dataset.sort == 'high') {
                sortMeBy('data-price', '.js-card-list', 'desc');
            }
            
        });
    });
}

export default products