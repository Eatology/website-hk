const axios = require('axios').default;

const eatologyAPICall = async(apiEndpoint, extra) => {
    let URL, API_KEY
    // URL = params.healthyfood.url
	URL = 'https://healthyfood.app/api/';
    API_KEY = params.healthyfood.token

    switch (apiEndpoint) {
        case "overview":
        case "extraRatings":
            URL = URL + `customers/fetchDetails?woocommerceCustomerId=${extra.wooId}&startDate=${extra.startDate}&endDate=${extra.endDate}`
            break
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
        case "extraGetMenu":
            URL = URL + `sneakPeek?mealPlanId=${extra.mealPlanId}&startDate=${extra.startDate}&endDate=${extra.endDate}&language=${extra.language}`
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

    return wpUId
}

export default eatologyAPICall
