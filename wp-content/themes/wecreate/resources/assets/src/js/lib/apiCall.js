const axios = require('axios').default;

const eatologyAPICall = async(apiEndpoint, extra) => {
    let URL, API_KEY
    URL = 'https://dev.healthyfood.app:443/api/';
    API_KEY = 'b1f66cdc-e2e4-488b-9173-ced79450f91b';

    switch (apiEndpoint) {
        case "overview":
        case "extraRatings":
            URL = URL + `customers/fetchDetails?woocommerceCustomerId=${extra.wooId}&startDate=${extra.startDate}&endDate=${extra.endDate}`
            break
            // case "extraRatings":
            //     URL = URL + `customer/${extra.wooId}/rateMeals?startDate=${extra.startDate}&endDate=${extra.endDate}&apiKey=${API_KEY}`
            //     break                       
            //     break                       
            //     break                       
        case "extraEditAddress":
            URL = URL + `customers/editAddress`
            break
        case "extraDeleteAddress":
            URL = URL + `customers/deleteAddress`
            break
        case "extraUpdateAddress":
            URL = URL + `orders/postponeOrders`
            break
        case "extraChangeDeliveryTime":
        case "extraChangeOrderAddress":
        case "extraChangeDeliveryDate":
        case "extraChangeMealPlan":
            URL = URL + `orders/editOrders`
            break
        case "extraNewAddress":
            URL = URL + `customers/createAddress`
            break
        case "extraPostponeOrder":
            URL = URL + `orders/postponeOrders`
            break
        case "extraNewOrderDays":
            URL = URL + `orders/addOrders`
            break
        case "extraNewRating":
            URL = URL + `rateMeals`
            break
        default:
            break
    }

    let response, data
    if (extra.method === 'GET') {
        try {
            response = await axios.get(URL, {
                headers: {
                    'Authorization': `Bearer ${API_KEY}`
                }
            });

            data = response.data;
        } catch (error) {
            console.warn(error)
            data = error.response.data
        }
    } else if (extra.method === 'POST') {

        console.log("Data sent to POST request: ", extra.data);

        try {
            response = await axios.post(
                URL,
                extra.data, {
                    headers: { 'Authorization': `Bearer ${API_KEY}` }
                }
            );

            data = response.data;

        } catch (error) {
            console.warn(error);
            data = error;
            console.log("Error: ", data);
        }
    }
    return data;
}

export const wpUId = () => {
    let wpUId = window.cus
        //let wpUId = 34
        //if (window.cus && window.cus !== 1 && (location.hostname !== "localhost" && location.hostname !== "127.0.0.1" && location.hostname !== "eatology.wecreatelabs.com.hk")) {
        //    wpUId = window.cus
        //}
    return wpUId
}

export default eatologyAPICall