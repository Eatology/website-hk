const products = () => {
    const pa_days = document.querySelector('label[for="pa_days"]')
        // const pa_days_hidden = document.querySelector('#pa_days')

    // setTimeout(() => {
    //     let price = document.querySelector('.woocommerce-variation-price .price .amount')
    //     let price_div_parent = document.querySelector('.woocommerce-variation-price')
    //     let price_div_child = document.querySelector('.woocommerce-variation-price .price')
    //     if (price) {
    //         price = price.innerHTML
    //         price = price.replace(/ *\<[^)]*\> */g, "")
    //         price = parseInt(price, 10)
    //         if (pa_days_hidden && per_days_text) {
    //             var pa_days_value = pa_days_hidden.options[pa_days_hidden.selectedIndex].value;
    //             if (pa_days_value) {
    //                 pa_days_value = pa_days_value.replace("-days", "")
    //                 pa_days_value = parseInt(pa_days_value, 10)
    //                 const price_per_day = price / pa_days_value

    //                 const priceDiv = document.createElement('div')
    //                 priceDiv.innerHTML = '$' + price_per_day + per_days_text
    //                 price_div_parent.insertBefore(priceDiv, price_div_child)


    //             }

    //         }
    //     }
    // }, 500);







    if (pa_days && extra_days_info) {
        const span = document.createElement('span')
        span.innerHTML = extra_days_info;
        pa_days.appendChild(span)
    }


    // replace proce in brackets on add ons
    Array.from(document.querySelectorAll(".wc-pao-addon-container p label ")).forEach(function(add_on_wrap) {
        add_on_wrap.innerHTML = add_on_wrap.innerHTML.replace(/ *\([^)]*\) */g, "")

        const span = document.createElement('span')
        span.classList.add("checkmark")
        add_on_wrap.appendChild(span)
    })


    return
}
export default products