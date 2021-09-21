import eatologyAPICall, { wpUId } from './apiCall'

const myAccountRatings = () => {
    const ratingsTable = document.getElementById('ratings-table')

    if (ratingsTable) {
        const formatYmd = date => date.toISOString().slice(0, 10)
        const todayDB = formatYmd(new Date())
        const startDate = "2018-01-01"
        const calendarActionWrapper = document.getElementById('calendar-action-wrapper')
        const calendarAction = document.getElementById('calendar-action')
        const ratingsList = document.getElementById('ratings-list')
        const closeButton = document.getElementById('calendar-action-close')
        const ratingH3 = document.getElementById('ratings-h3')
        const body = document.querySelector('body')
        const ratingsDailyForm = document.getElementById('ratings-daily-form')
        const loaderImage = document.getElementById('image-loader')

        let trackingFunctions = 0;
        const extraRatings = {
            method: "GET",
            wooId: wpUId(),
            startDate: startDate,
            endDate: todayDB,
        }


        const resetPage = () => {
            ratingsTable.innerHTML = ''
            ratingH3.innerHTML = ''
            ratingsDailyForm.innerHTML = ''
            closeOverlay()
            trackingFunctions = 1
            getRatings(trackingFunctions)
            trackingFunctions = 0
            return
        }

        function closeOverlay() {
            removeNoBodyScrolling()
            ratingH3.innerHTML = ''
            ratingsDailyForm.innerHTML = ''
            calendarActionWrapper.classList.remove("calendar-wrapper-active")
            calendarAction.classList.remove("calendar-action-active")
        }

        const isDefinedMeal = (meals, meal) => {
            let result = true;
            switch (true) {
                case (typeof meals === 'undefined'):
                case (meals && meals.length === 0):
                case (meals && meals.length > 0 && !meals.includes(meal)):
                    result = false;
                    break;
            }
            return result;
        }

        // close modal button
        if (closeButton)
            closeButton.addEventListener("click", closeOverlay)




        const noBodyScrolling = () => {
            if (!body.classList.contains("body-overflow")) {
                body.classList.add("body-overflow")
            }
        }

        const removeNoBodyScrolling = () => {
            if (body.classList.contains("body-overflow")) {
                body.classList.remove("body-overflow")
            }
        }

        const showAddressOverlay = (event, rating, customerId, ordersIdAndDay, ratedMeal) => {
            event.preventDefault()
            ratingH3.textContent = formatDate(rating.date)

            if (!isDefinedMeal(ratedMeal.meals || [], 'Breakfast')) {
                getMealRatingsRow(rating.meals.Breakfast, 'Breakfast')
            }
            if (!isDefinedMeal(ratedMeal.meals || [], 'Lunch')) {
                getMealRatingsRow(rating.meals.Lunch, 'Lunch')
            }
            if (!isDefinedMeal(ratedMeal.meals || [], 'Dinner')) {
                getMealRatingsRow(rating.meals.Dinner, 'Dinner')
            }

            // add button div
            let buttonDiv = document.createElement('div')
            buttonDiv.className = "button-wrapper"

            let buttonSubmit = document.createElement('button')
            buttonSubmit.setAttribute("id", "rating-submit")
            buttonSubmit.textContent = "Submit"
            ratingsDailyForm.appendChild(buttonSubmit)

            noBodyScrolling()
            if (!calendarActionWrapper.classList.contains("calendar-active")) {
                calendarActionWrapper.classList.add("calendar-wrapper-active")
                calendarAction.classList.add("calendar-action-active")
            }

            ratingsDailyForm.addEventListener("submit", (event) => processRating(event, rating, customerId, ordersIdAndDay, ratedMeal))
        }

        // get form data
        const processRating = (event, rating, customerId, orderId, ratedMeal) => {
			console.log('event',event, 'rating', rating, 'customerId', customerId, 'orderId', orderId, 'data.orderId', data.orderId, 'ratedMeal', ratedMeal);
            event.preventDefault()
            loaderImage.style.display = "block"
            const form = ratingsDailyForm
            let reqBody = {};
            const date = rating.date

            // get form values
            // get the object jey then check if radio and checked or textarea and then get the value and put
            // in new object
            Object.keys(form.elements).forEach(key => {
                let element = form.elements[key];
                if (element.type === 'radio' || element.type === 'textarea') {
                    if ((element.type === 'radio' && element.checked === true) || element.type === 'textarea') {
                        reqBody[element.name] = element.value;
                    }
                }
            });
            // create an object for each rating from the form data
            let meals = []
                // meals = generateRatingObject(rating.meals.Breakfast, "Breakfast", reqBody, date, customerId, meals)
                // meals = generateRatingObject(rating.meals.Lunch, "Lunch", reqBody, date, customerId, meals)
                // meals = generateRatingObject(rating.meals.Dinner, "Dinner", reqBody, date, customerId, meals)

            const breakfastNodes = document.getElementsByName("rating-Breakfast-radio");
            const breakfastRating = Array.prototype.slice.call(breakfastNodes, 0).filter(element => element.checked);
            const breakfastRemarksNode = document.getElementById("rating-textarea-Breakfast");
            const breakfastRemarks = (breakfastRemarksNode) ? breakfastRemarksNode.value : '';

            if (!isDefinedMeal(ratedMeal.meals || [], 'Breakfast') && breakfastRemarks.length > 0) {
                meals.push({
                    date: date,
                    meal: "Breakfast",
                    customerId: customerId,
					orderId: ordersIdAndDay.filter(idAndDate => idAndDate[1] === date).map(idAndDate => idAndDate[0]), //new line
                    rating: ((breakfastRating && typeof breakfastRating[0] !== 'undefined') ? breakfastRating[0].value : '5'),
                    remark: breakfastRemarks
                })
            }


            const lunchNodes = document.getElementsByName("rating-Lunch-radio");
            const lunchRating = Array.prototype.slice.call(lunchNodes, 0).filter(element => element.checked);
            const lunchRemarksNode = document.getElementById("rating-textarea-Lunch");
            const lunchRemarks = (lunchRemarksNode) ? lunchRemarksNode.value : '';

            if (!isDefinedMeal(ratedMeal.meals || [], 'Lunch') && lunchRemarks.length > 0) {
                meals.push({
                    date: date,
                    meal: "Lunch",
                    customerId: customerId,
					orderId: ordersIdAndDay.filter(idAndDate => idAndDate[1] === date).map(idAndDate => idAndDate[0]), //new line
                    rating: ((lunchRating && typeof lunchRating[0] !== 'undefined') ? lunchRating[0].value : '5'),
                    remark: lunchRemarks
                });
            }


            const dinnerNodes = document.getElementsByName("rating-Dinner-radio");
            const dinnerRating = Array.prototype.slice.call(dinnerNodes, 0).filter(element => element.checked);
            const dinnerRemarksNode = document.getElementById("rating-textarea-Dinner");
            const dinnerRemarks = (dinnerRemarksNode) ? dinnerRemarksNode.value : '';

            if (!isDefinedMeal(ratedMeal.meals || [], 'Dinner') && dinnerRemarks.length > 0) {
                meals.push({
                    date: date,
                    meal: "Dinner",
                    customerId: customerId,
					orderId: ordersIdAndDay.filter(idAndDate => idAndDate[1] === date).map(idAndDate => idAndDate[0]), //new line
                    rating: ((dinnerRating && typeof dinnerRating[0] !== 'undefined') ? dinnerRating[0].value : '5'),
                    remark: dinnerRemarks
                })
            }


            const extraNewRating = {
                method: "POST",
                data: {
                    meals
                }
            }
            const newRating = eatologyAPICall("extraNewRating", extraNewRating).then(data => {
                console.log("data", data)
                    // change address in DOM
                const message = 'Meals successfully rated'
                if (data.message === message) {
                    console.log(message)
                    resetPage()
                    return false
                } else {
                    console.log('Could not rate meal')
                    loaderImage.style.display = "none"
                }
            })
            return
        }

        const generateRatingObject = (meals, mealType, formBody, date, customerId, ratingArray) => {
            meals.map(dish => {
                let id = dish.dishId
                let textarea = `rating-${id}-textarea`
                let stars = `rating-${id}-radio`
                let textareaValue = ''
                let starsValue = 0
                Object.keys(formBody).map(function(key) {
                    if (key === textarea) {
                        textareaValue = formBody[key]
                        return
                    }
                })

                Object.keys(formBody).map(function(key) {
                    if (key === stars) {
                        starsValue = formBody[key]
                        return
                    }
                })

                ratingArray.push({
                    date: date,
                    meal: mealType,
					orderId: orderId,
                    customerId: customerId,
                    remark: textareaValue,
                    rating: parseInt(starsValue)
                })
            })

            return ratingArray;
        }

        function ordinal(date) {
            return (date > 20 || date < 10) ? ([false, "st", "nd", "rd"])[(date % 10)] || "th" : "th";
        }

        const formatDate = (date => {
            let newReviewDate
            const formatDateString = new Date(date)
            const month = formatDateString.toLocaleString('default', { month: 'short' })
            const day = formatDateString.getDate()
            const year = formatDateString.getFullYear()
            newReviewDate = `${day}${ordinal(day)} ${month} ${year}`
            return newReviewDate
        })


        const getStars = (meal, element) => {
            // add fieldset
            let fieldset = document.createElement('fieldset')
            fieldset.className = "rating-stars"
                //let starRating = Math.floor(dish.dishAverageRatings)
            let starRating = null
                // add div
            let div = document.createElement('div')
            div.className = "rating-stars__stars"

            // add star input 1
            let input1 = document.createElement('input')
            input1.className = "rating-stars__input"
            input1.setAttribute("id", "rating-id-1-" + meal)
            input1.setAttribute("type", "radio")
            input1.setAttribute("name", `rating-${meal}-radio`)
            input1.setAttribute("value", "1")
            if (starRating === 1) {
                input1.setAttribute("checked", true)
            }
            div.appendChild(input1)
                // add star label
            let label1 = document.createElement('label')
            label1.className = "rating-stars__label"
            label1.setAttribute("for", "rating-id-1-" + meal)
            div.appendChild(label1)

            // add star input 2
            let input2 = document.createElement('input')
            input2.className = "rating-stars__input"
            input2.setAttribute("id", "rating-id-2-" + meal)
            input2.setAttribute("type", "radio")
            input2.setAttribute("name", `rating-${meal}-radio`)
            input2.setAttribute("value", "2")
            if (starRating === 2) {
                input2.setAttribute("checked", true)
            }
            div.appendChild(input2)
                // add star label
            let label2 = document.createElement('label')
            label2.className = "rating-stars__label"
            label2.setAttribute("for", "rating-id-2-" + meal)
            div.appendChild(label2)

            // add star input 3
            let input3 = document.createElement('input')
            input3.className = "rating-stars__input"
            input3.setAttribute("id", "rating-id-3-" + meal)
            input3.setAttribute("type", "radio")
            input3.setAttribute("name", `rating-${meal}-radio`)
            input3.setAttribute("value", "3")
            if (starRating === 3) {
                input3.setAttribute("checked", true)
            }
            div.appendChild(input3)
                // add star label
            let label3 = document.createElement('label')
            label3.className = "rating-stars__label"
            label3.setAttribute("for", "rating-id-3-" + meal)
            div.appendChild(label3)


            // add star input 4
            let input4 = document.createElement('input')
            input4.className = "rating-stars__input"
            input4.setAttribute("id", "rating-id-4-" + meal)
            input4.setAttribute("type", "radio")
            input4.setAttribute("name", `rating-${meal}-radio`)
            input4.setAttribute("value", "4")
            if (starRating === 4) {
                input4.setAttribute("checked", true)
            }
            div.appendChild(input4)
                // add star label
            let label4 = document.createElement('label')
            label4.className = "rating-stars__label"
            label4.setAttribute("for", "rating-id-4-" + meal)
            div.appendChild(label4)

            // add star input 5
            let input5 = document.createElement('input')
            input5.className = "rating-stars__input"
            input5.setAttribute("id", "rating-id-5-" + meal)
            input5.setAttribute("type", "radio")
            input5.setAttribute("name", `rating-${meal}-radio`)
            input5.setAttribute("value", "5")
                // if (starRating === 5) {
                //     input5.setAttribute("checked", true)
                // }
            input5.setAttribute("checked", true)
            div.appendChild(input5)
                // add star label
            let label5 = document.createElement('label')
            label5.className = "rating-stars__label"
            label5.setAttribute("for", "rating-id-5-" + meal)
            div.appendChild(label5)

            // add div
            let divFocus = document.createElement('div')
            divFocus.className = "rating-stars__focus"
            div.appendChild(divFocus)


            fieldset.appendChild(div)

            // append fieldset to element
            element.appendChild(fieldset)

            return element
        }

        const getMealRatingsRow = (mealContent, meal) => {

            if (mealContent) {
                // add div row
                let formRow = document.createElement('div')
                formRow.className = "form-row"

                // add right col
                let formRowRight = document.createElement('div')
                formRowRight.className = "form-content"

                // add header
                let h6 = document.createElement('h6')
                h6.className = "rating-title"
                h6.textContent = meal
                formRowRight.appendChild(h6)

                // add meal name
                let h5 = document.createElement('h5')
                h5.className = "rating-meal-name"
                h5.textContent = mealContent
                formRowRight.appendChild(h5)

                // add comment title
                let p = document.createElement('p')
                p.className = "rating-comment-title"
                p.textContent = 'Comment'
                formRowRight.appendChild(p)

                // add comment textarea
                let textarea = document.createElement('textarea')
                textarea.setAttribute("id", "rating-textarea-" + meal)
                textarea.setAttribute("name", `rating-${meal}-textarea`)
                textarea.setAttribute("class", "rating-listing-textarea")
                textarea.setAttribute("value", "")
                formRowRight.appendChild(textarea)

                let starsDiv = document.createElement('div')
                starsDiv.className = "stars-wrapper"
                starsDiv = getStars(meal, starsDiv)

                // append starsDiv to row
                formRowRight.appendChild(starsDiv)


                // append right div to form
                formRow.appendChild(formRowRight)

                // append div to form
                ratingsDailyForm.appendChild(formRow)
            }
        }

        // const formatAverageRatings = (rating) => {
        //     let mealRatings = []
        //     if (rating.meals.Breakfast && rating.meals.Breakfast.length !== 0)  {
        //         mealRatings = getMealRatings(rating.meals.Breakfast, mealRatings)
        //     }
        //     if (rating.meals.Lunch && rating.meals.Lunch.length !== 0)  {
        //         mealRatings = getMealRatings(rating.meals.Lunch, mealRatings)
        //     }
        //     if (rating.meals.Dinner && rating.meals.Dinner.length !== 0)  {
        //         mealRatings = getMealRatings(rating.meals.Dinner, mealRatings)
        //     }
        //
        //     return mealRatings
        // }
        //
        // const averageRating = (arr) => arr.reduce((a,b) => a + b, 0) / arr.length

        // const getMealRatings = (mealToMap, mealArray) => {
        //     if (mealToMap.length > 0 ) {
        //         mealToMap.map(dish => {
        //             if (dish.dishAverageRatings !== null) {
        //                 mealArray.push(parseFloat(dish.dishAverageRatings))
        //             }
        //         })
        //     }
        //     return mealArray
        // }


        function getRatings() {
            const ratingsAPI = eatologyAPICall("extraRatings", extraRatings).then(data => {
				console.log('data',data);
                loaderImage.style.display = "block"
                const dishesRatings = data.mealsToBeRated
                const customerId = data.customer.id
				const ordersIdAndDay = data.mealsToBeRated.map( dayToBeRated => [dayToBeRated.orderId, dayToBeRated.date] )
                const ratedMeals = data.customer.ratedMeals

                dishesRatings.map(rating => {
                    // rating
                    const ratedMeal = ratedMeals[rating.date] || [];

                    if (rating.meals && Object.values(rating.meals).length !== 0) {
                        // const averageRatings = formatAverageRatings(rating);

                        let displayAverageRating
                            // if (averageRatings.length > 0)
                            //     displayAverageRating = averageRating(averageRatings).toFixed(1)
                            // else
                        displayAverageRating = '---'


                        // Add TR
                        let trRating = document.createElement('tr')
                        trRating.className = "woocommerce-orders-table__row woocommerce-ratings-table__row--meal-date"

                        // ADD TDs
                        let tdID = document.createElement('td')
                        tdID.className = "woocommerce-orders-table__cell woocommerce-ratings-table__meal-date"
                        tdID.textContent = formatDate(rating.date)
                        tdID.setAttribute("data-title", "Meal Date")
                            // 27th Sept 2020
                        trRating.appendChild(tdID)

                        let tdProduct = document.createElement('td')
                        tdProduct.className = "woocommerce-orders-table__cell woocommerce-ratings-table__product"
                        tdProduct.textContent = rating.mealPlanMenuName
                        tdProduct.setAttribute("data-title", "Product")
                        trRating.appendChild(tdProduct)

                        // let tdRating = document.createElement('td')
                        // tdRating.className = "woocommerce-orders-table__cell woocommerce-ratings-table__rating"
                        // tdRating.textContent = displayAverageRating
                        // tdRating.setAttribute("data-title", "Ratings")
                        // trRating.appendChild(tdRating)

                        let tdAction = document.createElement('td')
                        tdAction.className = "woocommerce-orders-table__cell woocommerce-ratings-table__action"
                        tdAction.setAttribute("data-title", "Action")
                        let viewA = document.createElement('a')
                        viewA.className = "edit-address"
                        viewA.text = 'View'
                        viewA.href = '#'
                        viewA.onclick = (event) => {
                            showAddressOverlay(event, rating, customerId, ordersIdAndDay, ratedMeal)
                        }
                        tdAction.appendChild(viewA)

                        trRating.appendChild(tdAction)

                        ratingsTable.appendChild(trRating)
                    }
                })
                loaderImage.style.display = "none"
            })
        }

        document.addEventListener('DOMContentLoaded', function() {
            getRatings()
        })
    }
}

export default myAccountRatings
