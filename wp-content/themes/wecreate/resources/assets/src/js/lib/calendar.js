import { Calendar } from '@fullcalendar/core'
import interactionPlugin, { Draggable } from '@fullcalendar/interaction'
import dayGridPlugin from '@fullcalendar/daygrid'
import eatologyAPICall, { wpUId } from './apiCall'

import moment from 'moment';

// Delivery Dates
const myAccountCalendar = () => {
    const calendarEl = document.getElementById('calendar')

    if (calendarEl) {
        Date.prototype.addDays = function(days) {
            var date = new Date(this.valueOf())
            date.setDate(date.getDate() + days)
            return date
        }

        Date.prototype.minusDays = function(days) {
            var date = new Date(this.valueOf())
            date.setDate(date.getDate() - days)
            return date
        }

        const formatYmd = date => date.toISOString().slice(0, 10)

        const addressWrapper = document.getElementById('address-wrapper')
        const vipStatus = document.getElementById('vip-status')
        let daysAvailable = document.getElementById('days-available')
        const today = new Date()
        const ninetyDays = today.addDays(90)
        const tomorrow = today.addDays(1)
        const endDB = formatYmd(ninetyDays)
        const calendarActionWrapper = document.getElementById('calendar-action-wrapper')
        const calendarAction = document.getElementById('calendar-action')
        const calendarEdit = document.getElementById('calendar-edit')
        const closeButton = document.getElementById('calendar-action-close')
        const calendarH3 = document.getElementById('calendar-h3')
        const calendarIntro = document.getElementById('calendar-intro')
        const calendarActionSlot = document.getElementById('calendar-action-slot')
        const confirmPostponeButton = document.getElementById('calendar-confirm-postpone')
        const confirmAddressButton = document.getElementById('calendar-confirm-address')
        const confirmDeliveryButton = document.getElementById('calendar-confirm-delivery')
        const confirmMealButton = document.getElementById('calendar-confirm-meal')
        const confirmActionSpacePostpone = document.getElementById('calendar-action-space--postpone')
        const confirmActionSpaceAddress = document.getElementById('calendar-action-space--address')
        const confirmActionSpaceDelivery = document.getElementById('calendar-action-space--delivery')
        const confirmActionSpaceMeal = document.getElementById('calendar-action-space--meal')
        const calendarNewEditAddress = document.getElementById('calendar-new-edit-address')
        const addressForm = document.getElementById('address-form')
        const postEditAddressButton = document.getElementById('post-edit-address')
        const addAddressH3 = document.getElementById('add-address-h3')
        const editAddressH3 = document.getElementById('edit-address-h3')
        const postAddressID = document.getElementById('post-address-id')
        const postAddressName = document.getElementById('post-address-name')
        const postAddressRoom = document.getElementById('post-address-room')
        const postAddressFloorNumber = document.getElementById('post-address-floor_number')
        const postAddressTowerBlock = document.getElementById('post-address-tower_block')
        const postAddressBuildingName = document.getElementById('post-address-building_name')
        const postAddressNumberStreetName = document.getElementById('post-address-number_street_name')
        const postAddressArea = document.getElementById('post-address-area')
        const postAddressDistrict = document.getElementById('post-address-district')
        const postAddressRemark = document.getElementById('post-address-remark')
        const calendarNewMeal = document.getElementById('calendar-new-meal')
        const newMealIntro = document.getElementById('new-meal-intro')
        const newMealWrapper = document.getElementById('new-meal-wrapper')
        const addOrdersForm = document.getElementById('add-orders')
        const addAddressWrapper = document.getElementById('add-address-wrapper')
        const addNewAddressButton = document.getElementById('add-new-address')
        const loaderImage = document.getElementById('image-loader')
        const postAddAddressButton = document.getElementById('post-add-address')
        const hiddenWrapper = document.getElementById('hidden-wrapper')
        let buttonAddress = document.getElementById('update-delivery-dates')

        const getDistricts = async() => {
            let response, data, districts, subDistrict, subDistricts, areas
            try {
                // get the districts from the JSON feed
                response = await fetch("/wp-content/themes/wecreate/resources/json/hk_en.json")
                data = await response.json()

                districts = []
                    // i have to change to array from nested objects
                for (var zone in data) {
                    areas = []
                    for (var area in data[zone]) {
                        subDistricts = []
                        for (var district in data[zone][area]) {
                            subDistricts.push(data[zone][area][district])
                        }
                        areas.push({
                            area: '=== ' + area + ' ===',
                            districts: subDistricts.sort()
                        })
                    }

                    subDistrict = {
                        zone: zone,
                        areas: areas.sort(),
                    }
                    districts.push(subDistrict)
                }
                return districts
            } catch (error) {
                console.warn(error)
                return error
            }
        }

        const displayDistricts = async() => {
            getDistricts().then(data => {
                postAddressDistrict.innerHTML = ''
                var emptyOpt = document.createElement('option');
                emptyOpt.value = '';
                emptyOpt.innerHTML = "";
                postAddressDistrict.appendChild(emptyOpt);

                data.map(district_info => {
                    //empty optgroup
                    var optEmpty = document.createElement('optgroup');
                    optEmpty.value = '';
                    optEmpty.setAttribute("label", '')
                    postAddressDistrict.appendChild(optEmpty);


                    // zone optgroup
                    var optGroup = document.createElement('optgroup');
                    optGroup.value = district_info.zone;
                    optGroup.setAttribute("label", district_info.zone)
                    postAddressDistrict.appendChild(optGroup);


                    district_info.areas.map(areas => {
                        //empty optgroup
                        var optEmpty = document.createElement('optgroup');
                        optEmpty.value = '';
                        optEmpty.setAttribute("label", '')
                        postAddressDistrict.appendChild(optEmpty);

                        // area optgroup
                        var opt = document.createElement('optgroup');
                        opt.value = areas.area;
                        opt.setAttribute("label", areas.area)
                        postAddressDistrict.appendChild(opt);

                        //empty optgroup
                        var optEmpty = document.createElement('optgroup');
                        optEmpty.value = '';
                        optEmpty.setAttribute("label", '')
                        postAddressDistrict.appendChild(optEmpty);

                        areas.districts.map(district => {
                            // actual options
                            var optDistrict = document.createElement('option');
                            optDistrict.value = district;
                            optDistrict.innerHTML = district;
                            postAddressDistrict.appendChild(optDistrict);
                        })
                    })
                })
                return
            })
            return
        }

        displayDistricts()


        // Very weird bug happens with event listeners I call the populateCalendar once on page load and add event listeners
        // Then once posted data call again to refresh screens but this will keep adding event listners, every post will cause an additonal event listener
        // So I set trackingFunctions to 0 then pass it through and in restPage() I set to 1, then call populateCalendar then reset to 0.
        // Then event listeners I can check to see if equals 0 and if so then add event listener
        let trackingFunctions = 0

        const resetPage = () => {
            calendarEl.innerHTML = ''
            addressWrapper.innerHTML = ''
            daysAvailable.innerHTML = ''
            closeOverlay()
            trackingFunctions = 1
            populateCalendar(trackingFunctions)
            trackingFunctions = 0
                //loaderImage.style.display = "none"
            return
        }

        function closeOverlay() {
            removeNoBodyScrolling()
            calendarActionWrapper.classList.remove("calendar-wrapper-active")
            calendarAction.classList.remove("calendar-action-active")
            removeActionClasses()
            if (calendarActionSlot.classList.contains("calendar-initial-active")) {
                calendarActionSlot.classList.remove("calendar-initial-active")
            }
            // remove created new meals
            newMealWrapper.innerHTML = ''
            const ordersAmountField = document.getElementById('postponed-orders-amount')
            hiddenWrapper.innerHTML = ''

        }

        // close modal button
        if (closeButton) {
            closeButton.addEventListener("click", closeOverlay)
        }

        // remove the postpone, Address, Delivery and Meal text in the overlay
        const removeActionClasses = () => {
            if (confirmActionSpacePostpone.classList.contains("active")) {
                confirmActionSpacePostpone.classList.remove("active")
            }
            if (confirmActionSpaceAddress.classList.contains("active")) {
                confirmActionSpaceAddress.classList.remove("active")
            }
            if (confirmActionSpaceDelivery.classList.contains("active")) {
                confirmActionSpaceDelivery.classList.remove("active")
            }
            if (confirmActionSpaceMeal.classList.contains("active")) {
                confirmActionSpaceMeal.classList.remove("active")
            }
        }

        // click event for postpone button
        const showPostponeMessage = () => {
            removeActionClasses()
            if (!confirmActionSpacePostpone.classList.contains("active")) {
                confirmActionSpacePostpone.classList.add("active")
            }
        }
        confirmPostponeButton.addEventListener("click", showPostponeMessage)

        // click event for update address button
        const showAddressMessage = () => {
            removeActionClasses()
            if (!confirmActionSpaceAddress.classList.contains("active")) {
                confirmActionSpaceAddress.classList.add("active")
            }
        }
        confirmAddressButton.addEventListener("click", showAddressMessage)

        // click event for update delivery button
        const showDelivery = () => {
            removeActionClasses()
            if (!confirmActionSpaceDelivery.classList.contains("active")) {
                confirmActionSpaceDelivery.classList.add("active")
            }
        }
        confirmDeliveryButton.addEventListener("click", showDelivery)

        // click event for update delivery button
        const showMealMessage = () => {
            removeActionClasses()
            if (!confirmActionSpaceMeal.classList.contains("active")) {
                confirmActionSpaceMeal.classList.add("active")
            }
        }
        confirmMealButton.addEventListener("click", showMealMessage)

        const noBodyScrolling = () => {
            const body = document.querySelector('body')
            if (!body.classList.contains("body-overflow")) {
                body.classList.add("body-overflow")
            }
        }

        const removeNoBodyScrolling = () => {
            const body = document.querySelector('body')
            if (body.classList.contains("body-overflow")) {
                body.classList.remove("body-overflow")
            }
        }

        // click event for add new address button
        // show add new address and hide calendar edit
        const showNewAddressForm = () => {

            if (!postAddAddressButton.classList.contains("active")) {
                postAddAddressButton.classList.add("active")
            }
            if (postEditAddressButton.classList.contains("active")) {
                postEditAddressButton.classList.remove("active")
            }

            if (!addAddressH3.classList.contains("active")) {
                addAddressH3.classList.add("active")
            }
            if (editAddressH3.classList.contains("active")) {
                editAddressH3.classList.remove("active")
            }
            showAddressOverlay()

            // set values from address passed in
            postAddressID.value = ''
            postAddressName.value = ''
            postAddressFloorNumber.value = ''
            postAddressTowerBlock.value = ''
            postAddressRoom.value = ''
            postAddressBuildingName.value = ''
            postAddressNumberStreetName.value = ''
            postAddressRemark.value = ''
            postAddressDistrict.value = ''

        }
        addNewAddressButton.addEventListener("click", showNewAddressForm)

        const showAddressOverlay = () => {
            noBodyScrolling()
            if (calendarEdit.classList.contains("active")) {
                calendarEdit.classList.remove("active")
            }
            if (calendarNewMeal.classList.contains("active")) {
                calendarNewMeal.classList.remove("active")
            }
            if (!calendarNewEditAddress.classList.contains("active")) {
                calendarNewEditAddress.classList.add("active")
            }
            if (!calendarActionWrapper.classList.contains("calendar-active")) {
                calendarActionWrapper.classList.add("calendar-wrapper-active")
                calendarAction.classList.add("calendar-action-active")
            }

            //displayDistricts()                
        }

        // get form data
        const getFormData = (form) => {
            let reqBody = {}
            Object.keys(form.elements).forEach(key => {
                let element = form.elements[key]
                if (element.type !== "submit") {
                    reqBody[element.name] = element.value
                }
            })
            return reqBody
        }

        const checkDistrictAddress = (currentDistrict, district) => {
            // HK -> KLN ( YES)
            // KLN -> HK (YES)
            // NT - > HK (YES)
            // NT -> KLN (YES)
            // NT -> NT ( NO )
            // HK -> NT ( NO )
            // KLN -> NT ( NO)
            const hongKong = "HONG KONG ISLAND"
            const kowloon = "KOWLOON"
            const newTerritories = "NEW TERRITORIES"
            const islands = "ISLANDS"

            if (currentDistrict === "NEED ZONE") {
                currentDistrict = hongKong
            }


            if (currentDistrict === hongKong && (district === kowloon || district === hongKong)) {
                return true
            } else if (currentDistrict === kowloon && (district === kowloon || district === hongKong)) {
                return true
            } else {
                return false
            }
        }

        function populateCalendar(trackingFunctions) {
            if (calendarEl) {
                loaderImage.style.display = "block"
                    // the array will store the selected meals
                var selected_meals = []
                var available_meals_arr = []


                // Edit address show overlay and populate fields
                const editAddress = (event, address) => {
                    event.preventDefault()
                    showAddressOverlay()

                    if (postAddAddressButton.classList.contains("active")) {
                        postAddAddressButton.classList.remove("active")
                    }
                    if (!postEditAddressButton.classList.contains("active")) {
                        postEditAddressButton.classList.add("active")
                    }

                    if (addAddressH3.classList.contains("active")) {
                        addAddressH3.classList.remove("active")
                    }
                    if (!editAddressH3.classList.contains("active")) {
                        editAddressH3.classList.add("active")
                    }
                    // display the districts and select it if equals address district
                    var districtOptionsLength = postAddressDistrict.options.length;
                    for (var i = 0; i < districtOptionsLength; i++) {
                        let optionValue = postAddressDistrict.options[i].value;
                        if (optionValue === address.district) {
                            postAddressDistrict.options[i].selected = true;
                        }
                    }


                    // set values from address passed in
                    postAddressID.value = address.id
                    postAddressName.value = address.name
                    postAddressFloorNumber.value = address.floorNumber
                    postAddressTowerBlock.value = address.towerBlock
                    postAddressRoom.value = address.room
                    postAddressBuildingName.value = address.buildingName
                    postAddressNumberStreetName.value = address.numberStreetName
                    postAddressRemark.value = address.remark

                    // click event
                    if (trackingFunctions === 0) {
                        postEditAddressButton.addEventListener('click', (event) => postEditAddress(event, address))
                    }
                }


                // click event on Post address to API
                const postEditAddress = (event, address) => {
                    event.preventDefault()
                    loaderImage.style.display = "block"
                    const addressToEdit = document.getElementById(`address-${address.id}`)
                    let reqBody = {};
                    Object.keys(addressForm.elements).forEach(key => {
                        let element = addressForm.elements[key];
                        if (element.type !== "submit") {
                            reqBody[element.name] = element.value;
                        }
                    });

                    const extraEditAddress = {
                        method: "POST",
                        data: {
                            address: {
                                addressId: reqBody['post-address-id'],
                                name: reqBody['post-address-name'],
                                room: reqBody['post-address-room'],
                                floorNumber: reqBody['post-address-floor_number'],
                                buildingName: reqBody['post-address-building_name'],
                                towerBlock: reqBody['post-address-tower_block'],
                                numberStreetName: reqBody['post-address-number_street_name'],
                                district: reqBody['post-address-district'],
                                remark: reqBody['post-address-remark'],
                                deliveryTimeFrom: address.deliveryTimeFrom, // don't have
                                deliveryTimeTo: address.deliveryTimeTo // don't have
                            }
                        }
                    }
                    const editAddress = eatologyAPICall("extraEditAddress", extraEditAddress).then(data => {
                        // change address in DOM        
                        const message = 'Customer address successfully edited'
                        if (data.message === message) {
                            console.log(message)
                            resetPage()
                            return false
                        } else {
                            console.log('Could not edit address')
                            loaderImage.style.display = "none"
                        }
                        return
                    })
                    return
                }


                // click event on Post new address to API
                const postNewAddress = (event, customer) => {
                    event.preventDefault()
                    loaderImage.style.display = "block"
                    let reqBody = {};
                    Object.keys(addressForm.elements).forEach(key => {
                        let element = addressForm.elements[key];
                        if (element.type !== "submit") {
                            reqBody[element.name] = element.value;
                        }
                    });

                    const extraNewAddress = {
                        method: "POST",
                        data: {
                            address: {
                                customerId: customer.id,
                                name: reqBody['post-address-name'],
                                room: reqBody['post-address-room'],
                                floorNumber: reqBody['post-address-floor_number'],
                                buildingName: reqBody['post-address-building_name'],
                                towerBlock: reqBody['post-address-tower_block'],
                                numberStreetName: reqBody['post-address-number_street_name'],
                                district: reqBody['post-address-district'],
                                remark: reqBody['post-address-remark'],
                                deliveryTimeFrom: "08:00 AM", // don't have
                                deliveryTimeTo: "08:30 AM" // don't have
                            }
                        }
                    }
                    const newAddress = eatologyAPICall("extraNewAddress", extraNewAddress).then(data => {
                        // change address in DOM   
                        const message = 'Customer address successfully added'
                        if (data.message === message) {
                            console.log(message)
                            resetPage()
                            return false
                        } else {
                            console.log('Could not add address')
                            loaderImage.style.display = "none"
                        }
                    })
                    return
                }

                const deleteAddress = (event, address) => {
                    event.preventDefault()
                    loaderImage.style.display = "block"
                    const extraDeleteAddress = {
                        method: "POST",
                        data: {
                            addressId: address.id
                        }
                    }
                    const deleteAddress = eatologyAPICall("extraDeleteAddress", extraDeleteAddress).then(data => {
                        // remove address from DOM             
                        const message = 'Customer address successfully deleted'
                        if (data.message === message) {
                            console.log(message)
                            resetPage()
                        } else {
                            console.log('Could not remove address')
                            loaderImage.style.display = "none"
                        }
                        return
                    })
                    return
                }

                // params for get customer api
                let extra = {
                    wooId: wpUId(),
                    //startDate: tomorrowDB,
                    startDate: '2020-01-01',
                    endDate: endDB,
                    method: "GET",
                }

                // call to get customer api
                let addresses = []
                const data = eatologyAPICall("overview", extra).then(data => {
                    if (data.message === 'Customer not found...') {
                        loaderImage.style.display = "none";
                        document.getElementById('error-modal').style.display = 'block';
                        // attach an event for error modal
                        document.getElementById('error-modal-close').addEventListener('click', () => {
                            document.getElementById('error-modal').style.display = 'none';
                        });
                        return false
                    }

                    loaderImage.style.display = "block"
                    const customer = data.customer
                    addresses = data.customer.addresses
                    const orders = data.customer.orders
                    const calendarDates = data.calendarDates
                    const postponedOrders = customer.postponedOrders
                    const daysLeft = data.customer.daysLeft
                    const mealPlans = data.mealPlans
                    let orderEvents = []
                    const timeSlots = ["07:00 AM - 07:30 AM", "07:30 AM - 08:00 AM", "08:00 AM - 08:30 AM", "08:30 AM - 09:00 AM", "09:00 AM - 09:30 AM", "09:30 AM - 10:00 AM", "10:00 AM - 10:30 AM", "05:30 PM - 06:00 PM", "06:00 PM - 06:30 PM", "06:30 PM - 07:00 PM", "07:00 PM - 07:30 PM"]

                    // click event for adding new address - need customer id
                    if (trackingFunctions === 0) {
                        postAddAddressButton.addEventListener('click', (event) => postNewAddress(event, customer))
                    }
                    // create an array to contain the postponed orders
                    if (postponedOrders.length > 0) {
                        postponedOrders.map(postponed_order => {
                            var meal_obj = new Object();
                            meal_obj.id = postponed_order.id
                            meal_obj.calories = postponed_order.calories
                            meal_obj.postponed_order_id = postponed_order.orderId;

                            meal_obj.meal_plan_name = postponed_order.mealPlan.name;
                            meal_obj.meal_plan_id = postponed_order.mealPlan.id;
                            if (postponed_order.breakfast === 1 && postponed_order.lunch === 1 && postponed_order.dinner === 1) {
                                meal_obj.meal_type = " (Breakfast, Lunch and Dinner)"
                            } else if (postponed_order.breakfast === 1 && postponed_order.lunch === 1 && postponed_order.dinner === 0) {
                                meal_obj.meal_type = " (Breakfast and Lunch)"
                            } else if (postponed_order.breakfast === 0 && postponed_order.lunch === 1 && postponed_order.dinner === 0) {
                                meal_obj.meal_type = " (Lunch and Dinner)"
                            } else {
                                meal_obj.meal_type = ""
                            }

                            const optionAlreadyAdded = available_meals_arr.filter(option => option.meal_plan_id === meal_obj.meal_plan_id).length > 0;
                            if (optionAlreadyAdded)
                                return;

                            available_meals_arr.push(meal_obj)
                        })
                    }

                    // check if the date equal to tomorrow 
                    // or falls in Sunday
                    const validateDropDate = (date) => {
                        const momentDate = moment(new Date(date));
                        const dayDiff = moment().diff(momentDate, 'days');
                        const dayInString = momentDate.format('dddd');
                        return !(dayDiff === 0 || dayInString === 'Sunday');
                    }

                    // properly format the date else today and tomorrow 
                    // will result both 0
                    const isTomorrow = (date) => {
                        const eventDay = moment(new Date(date)).format('YYYY-MM-DD');
                        const eventToday = moment(new Date()).format('YYYY-MM-DD');
                        const isTomorrow = moment(eventToday).diff(eventDay, 'days');

                        return (isTomorrow === -1);
                    }


                    // Add order to events for calendar
                    if (orders.length > 0) {
                        orders.map(order => {
                            let startTime = order.deliveryTimeFrom.slice(0, -3)
                            let endTime = order.deliveryTimeTo.slice(0, -3)
                            let mealType = ''
                            let address = order.address
                            let address_name = ''
                            let district = null
                            let mealPlanName = 'NEED MEAL PLAN NAME'
                            let mealPlanMenuName = (order.mealPlan && order.mealPlan.menuName) ? order.mealPlan.menuName : ''
                            let mealPlanCalories = 'NEED MEAL PLAN CALORIES'
                            if (address) {
                                district = order.address.district_info
                                address_name = order.address.name
                            }
                            let districtZone = "NEED ZONE"
                            if (district !== null && order.districtZone !== null) {
                                districtZone = order.districtZone
                            }

                            if (order.mealPlan && order.mealPlan.name) {
                                mealPlanName = order.mealPlan.name
                            }

                            if (order.mealPlan && order.mealPlan.calories) {
                                mealPlanCalories = order.mealPlan.calories
                            }

                            if (order.breakfast === 1 && order.lunch === 1 && order.dinner === 1) {
                                mealType = "Full Day"
                            }
                            if (order.breakfast === 1 && order.lunch === 1 && order.dinner === 0) {
                                mealType = "Breakfast & Lunch"
                            }
                            if (order.breakfast === 0 && order.lunch === 1 && order.dinner === 1) {
                                mealType = "Lunch & Dinner"
                            }
                            let event = {
                                //title: `Deliver to ${address_name} \n \n ${startTime} - ${endTime}`,
                                title: `${address_name}\n ${mealPlanMenuName}\n ${startTime} - ${endTime}`,
                                start: `${order.date}T${startTime}`,
                                end: `${order.date}T${endTime}`,
                                extendedProps: {
                                    mealPlan: mealPlanName,
                                    selectedMeals: mealType,
                                    calories: mealPlanCalories,
                                    id: order.id,
                                    addressId: order.address.id,
                                    mealPlanId: order.mealPlan.id,
                                    date: order.date,
                                    district: districtZone,
                                    deliveryTimeFrom: order.deliveryTimeFrom,
                                    deliveryTimeTo: order.deliveryTimeTo
                                }
                            }

                            if (isTomorrow(order.date)) {
                                event.editable = false; //not draggable, not resizeable
                            }

                            orderEvents.push(event)
                        })
                    }

                    // remove sundays
                    let sundays = {
                        daysOfWeek: ['0'],
                        display: 'background',
                        allDay: true,
                        color: '#F9F8F9',
                        displayEventTime: true,
                    }
                    orderEvents.push(sundays)

                    // remove today
                    let todayDate = {
                        start: today,
                        end: today,
                        display: 'background',
                        allDay: true,
                    }
                    orderEvents.push(todayDate)

                    // remove tomorrow
                    let tomorrowDate = {
                        start: tomorrow,
                        end: tomorrow,
                        display: 'background',
                        allDay: true,
                    }
                    orderEvents.push(tomorrowDate)

                    // // Add blocked days to events for calendar
                    // if (calendarDates.length > 0) {
                    //     calendarDates.map(calendarDate => {
                    //         let event = {
                    //             start: `${calendarDate}T09:00`,
                    //             end: `${calendarDate}T10:00`,
                    //             display: 'background',
                    //             allDay: true,
                    //         }
                    //         orderEvents.push(event)
                    //     })
                    // }

                    // Days available
                    if (daysAvailable) {
                        daysAvailable.innerHTML = (typeof customer.daysLeft !== 'undefined' ? customer.daysLeft : 0);
                    }

                    // VIP status
                    if (vipStatus) {
                        vipStatus.innerHTML = ((typeof customer.VIP !== 'undefined' && customer.VIP === 1) ? 'VIP' : 'Regular Customer');
                    }

                    const addressDisplay = (divWrapper, addresses, extendedProps = null, mealsInForm = null, checkDistrict = false, isNewOrder = false) => {
                        if (addresses.length === 0) {
                            return
                        } else {
                            let addressId, currentDistrict
                            let containsUndeliverableAddresses = false
                            if (extendedProps !== null) {
                                addressId = extendedProps.addressId
                                currentDistrict = extendedProps.district
                            }
                            // for new orders just show hk island ones
                            if (isNewOrder === true) {
                                currentDistrict = "HONG KONG ISLAND"
                                checkDistrict = true
                            }

                            let nodes = addresses.map((address, index) => {
                                index++
                                let div = document.createElement('div')
                                div.className = "address"
                                div.setAttribute("id", `address-${address.id}`)


                                let district = address.district
                                let district_info
                                if (address.districtZone) {
                                    district_info = address.districtZone
                                }

                                let checkedDistrict
                                if (checkDistrict && currentDistrict && district_info) {
                                    checkedDistrict = checkDistrictAddress(currentDistrict, district_info, isNewOrder)
                                }
                                if (addressId && addressId === address.id) {
                                    div.className = "address current-address"
                                } else if ((addressId && addressId !== address.id) && checkedDistrict === false) {
                                    div.className = "address no-edit"
                                    containsUndeliverableAddresses = true
                                } else if (isNewOrder && checkedDistrict === false) {
                                    div.className = "address no-edit"
                                    containsUndeliverableAddresses = true
                                }
                                let name = address.name
                                let floorNumber = address.floorNumber
                                let room = address.room
                                let towerBlock = address.towerBlock
                                let buildingName = address.buildingName
                                let numberStreetName = address.numberStreetName
                                let address1 = ''
                                let building = ''

                                if (((addressId && addressId !== address.id) && checkedDistrict === false) || (isNewOrder && checkedDistrict === false)) {
                                    name = "* " + name
                                }

                                if (floorNumber !== null && room !== null) {
                                    address1 += floorNumber + ", " + room + ","
                                } else if (floorNumber !== null && room === null) {
                                    address1 += floorNumber + ","
                                } else if (floorNumber === null && room !== null) {
                                    address1 += room + ","
                                }

                                if (towerBlock !== null && buildingName !== null) {
                                    building += towerBlock + ", " + buildingName + ","
                                } else if (buildingName !== null && towerBlock === null) {
                                    building += buildingName + ","
                                }

                                let div1 = document.createElement('div')

                                let input = document.createElement('input')
                                input.setAttribute("type", "radio")
                                if (mealsInForm !== null && mealsInForm !== false) {
                                    input.setAttribute("name", "select-address" + mealsInForm)
                                } else {
                                    input.setAttribute("name", "select-address")
                                }

                                if (checkedDistrict === false) {
                                    input.setAttribute("disabled", true)
                                }

                                // set checked if current address of meal
                                input.checked = ((addressId && addressId === address.id) ? true : false)

                                input.setAttribute("value", address.id)
                                div1.appendChild(input)

                                let spanAddress1 = document.createElement('span')
                                spanAddress1.className = "address-no"
                                spanAddress1.innerHTML = '<span>' + index + '. </span>'
                                div1.appendChild(spanAddress1)
                                div.appendChild(div1)

                                let spanAddress2 = document.createElement('span')
                                spanAddress2.className = "address-name"
                                spanAddress2.textContent = name + ","
                                div1.appendChild(spanAddress2)
                                div.appendChild(div1)

                                if (address1 !== '') {
                                    let div2 = document.createElement('div')
                                    div2.textContent = address1 + ' ' + (building || '')
                                    div.appendChild(div2)
                                }

                                // combine with address1
                                //
                                // if (building !== '') {
                                //     let div3 = document.createElement('div')
                                //     div3.textContent = building
                                //     div.appendChild(div3)
                                // }

                                if (numberStreetName !== null) {
                                    let div4 = document.createElement('div')
                                    div4.textContent = numberStreetName + ","
                                    div.appendChild(div4)
                                }

                                if (district !== null) {
                                    let div5 = document.createElement('div')
                                    div5.textContent = district
                                    div.appendChild(div5)
                                }

                                if (district_info !== null) {
                                    let div5a = document.createElement('div')
                                    div5a.textContent = district_info
                                    div.appendChild(div5a)
                                }

                                let div6 = document.createElement('div')
                                div6.className = "address-actions"

                                let editA = document.createElement('a')
                                editA.className = "edit-address"
                                editA.innerHTML = '<span class="icon-address_edit"></span> EDIT'
                                editA.href = '#'
                                editA.onclick = function(event) {
                                    editAddress(event, address)
                                }
                                div6.appendChild(editA)

                                let deleteA = document.createElement('a')
                                deleteA.className = "delete-address"
                                deleteA.innerHTML = '<span class="icon-icon-delete"></span> DELETE'
                                deleteA.href = '#'
                                deleteA.onclick = function(event) {
                                    deleteAddress(event, address)
                                };
                                //div6.appendChild(deleteA); // temporarily hide the delete until api is fixed
                                div.appendChild(div6)

                                return div
                            })
                            divWrapper.append(...nodes)
                            if (containsUndeliverableAddresses === true) {
                                let divNotice = document.createElement('div')
                                divNotice.className = "address-notice"
                                divNotice.textContent = "* Cannot be delivered - out of paid delivery area. Please contact Eatology to update it"
                                divWrapper.appendChild(divNotice)
                            }
                        }

                    }

                    //
                    const displayCurrentOrderDetails = (display = 'address', orderDetail) => {
                        // address
                        let district = orderDetail.address.district
                        let district_info
                        let name = orderDetail.address.name
                        let floorNumber = orderDetail.address.floorNumber
                        let room = orderDetail.address.room
                        let towerBlock = orderDetail.address.towerBlock
                        let buildingName = orderDetail.address.buildingName
                        let numberStreetName = orderDetail.address.numberStreetName
                        let address1 = ''
                        let building = ''
                        let addressDiv = document.createElement('div')
                        addressDiv.className = 'current-address'
                        let headerAddressDiv = document.createElement('h6')
                        headerAddressDiv.textContent = "Current Address"
                        addressDiv.appendChild(headerAddressDiv)

                        if (floorNumber !== null && room !== null) {
                            address1 += floorNumber + ", " + room + ","
                        } else if (floorNumber !== null && room === null) {
                            address1 += floorNumber + ","
                        } else if (floorNumber === null && room !== null) {
                            address1 += room + ","
                        }

                        if (towerBlock !== null && buildingName !== null) {
                            building += towerBlock + ", " + buildingName + ","
                        } else if (buildingName !== null && towerBlock === null) {
                            building += buildingName + ","
                        }
                        if (address1 !== '') {
                            let address1Element = document.createElement('div')
                            address1Element.textContent = address1
                            addressDiv.appendChild(address1Element)
                        }

                        if (building !== '') {
                            let buildingElement = document.createElement('div')
                            buildingElement.textContent = building
                            addressDiv.appendChild(buildingElement)
                        }

                        if (numberStreetName !== null) {
                            let numberStreetNameElement = document.createElement('div')
                            numberStreetNameElement.textContent = numberStreetName
                            addressDiv.appendChild(numberStreetNameElement)
                        }

                        if (district !== null) {
                            let districtElement = document.createElement('div')
                            districtElement.textContent = building
                            addressDiv.appendChild(districtElement)
                        }

                        if (district_info !== null) {
                            let districtInfoElement = document.createElement('div')
                            districtInfoElement.textContent = district_info
                            addressDiv.appendChild(districtInfoElement)
                        }

                        // delivery time
                        let deliveryDiv = document.createElement('div')
                        deliveryDiv.className = 'current-delivery-time'
                        let headerDeliveryTimeElement = document.createElement('h6')
                        headerDeliveryTimeElement.textContent = "Current Delivery Time"
                        deliveryDiv.appendChild(headerDeliveryTimeElement)
                        let deliveryTime = (typeof orderDetail.deliveryTimeFrom !== 'undefined' &&
                            orderDetail.deliveryTimeFrom.length > 0 ? orderDetail.deliveryTimeFrom + ' - ' +
                            (typeof orderDetail.deliveryTimeTo !== 'undefined' && orderDetail.deliveryTimeTo.length > 0 ? orderDetail.deliveryTimeTo : '') : '')

                        let deliveryTimeElement = document.createElement('div')
                        deliveryTimeElement.textContent = deliveryTime
                        deliveryDiv.appendChild(deliveryTimeElement)

                        switch (display) {
                            case 'delivery-time':
                                return deliveryDiv;
                                break;
                            case 'address':
                                return addressDiv
                                break;
                            default:
                                return addressDiv
                                break;
                        }

                    }

                    // display addresses at bottom of calendar
                    if (addressWrapper) {
                        addressDisplay(addressWrapper, addresses)
                    }

                    // format date to dd-mm-yyyy
                    const formatDate = (date, type = "") => {
                        let newOrderDate
                        const rangeStartDateNew = new Date(date);
                        const month = rangeStartDateNew.getMonth() + 1
                        const monthLong = rangeStartDateNew.toLocaleString('default', { month: 'long' })
                        const day = rangeStartDateNew.getDate()
                        const year = rangeStartDateNew.getFullYear()
                        const dbDate = rangeStartDateNew.toISOString().slice(0, 10);

                        const momentDate = moment(new Date(date));

                        if (type === "readable") {
                            newOrderDate = momentDate.format("ddd, Do MMM, YYYY");
                        } else if (type === "db") {
                            newOrderDate = dbDate
                        } else {
                            newOrderDate = momentDate.format("YYYY-MM-DD");
                        }

                        return newOrderDate
                    }




                    // Start Calendar
                    if (calendarEl) {
                        let calendar = new Calendar(calendarEl, {
                            plugins: [interactionPlugin, dayGridPlugin],
                            droppable: true,
                            firstDay: 1,
                            selectable: true,
                            editable: true,
                            height: "auto",
                            longPressDelay: 300,
                            // insert meal as selecting empty slot
                            select: function(info) {
                                if (postponedOrders.length === 0) {
                                    alert('You need to postpone a meal first before changing days')
                                    return
                                }

                                noBodyScrolling()
                                if (calendarEdit.classList.contains("active")) {
                                    calendarEdit.classList.remove("active")
                                }
                                if (calendarNewEditAddress.classList.contains("active")) {
                                    calendarNewEditAddress.classList.remove("active")
                                }
                                if (!calendarNewMeal.classList.contains("active")) {
                                    calendarNewMeal.classList.add("active")
                                }

                                const rangeStartDate = moment(info.startStr, "YYYY-MM-DD");
                                const rangeEndDate = moment(info.startStr, "YYYY-MM-DD");
                                const differenceInDays = rangeStartDate.diff(rangeEndDate, 'days');
                                let dateRangeString = '';
                                let dateRange = [];
                                let orderDate = null;
                                let dbDate = '';
                                let elementIncrement = 1;

                                // add new dates to array

                                dateRange.push(formatDate(rangeStartDate.format("YYYY-MM-DD")))


                                dbDate = formatDate(rangeStartDate.format("YYYY-MM-DD"), 'db')


                                orderDate = document.createElement('input')
                                orderDate.setAttribute("id", "order-date" + elementIncrement)
                                orderDate.setAttribute("name", "order-date" + elementIncrement)
                                orderDate.setAttribute("type", "hidden")
                                orderDate.value = dbDate
                                hiddenWrapper.appendChild(orderDate)
                                elementIncrement = elementIncrement + 1




                                // if it's 1 day then just use start date
                                // if it's 2 days then use start and end
                                // else make new dates for more than 2
                                if (differenceInDays === 1) {
                                    dateRangeString = formatDate(rangeStartDate)
                                } else if (differenceInDays === 2) {
                                    dateRangeString = formatDate(rangeStartDate) + ' and ' + formatDate(rangeStartDate.addDays(1))
                                } else {
                                    for (let i = 0; i <= dateRange.length; i++) {
                                        if (i === (dateRange.length - 1)) {
                                            dateRangeString += ' and ' + dateRange[i]
                                            break
                                        } else {
                                            dateRangeString += dateRange[i] + ', '
                                        }
                                    }
                                }



                                // layout meal add boxes
                                let divMeal
                                let mealsInForm = 1
                                for (let i = 0; i <= dateRange.length; i++) {

                                    divMeal = document.createElement('div')
                                        // set calendarIntro info
                                    let h6MealDate = document.createElement('h6')
                                    h6MealDate.textContent = formatDate(dateRange[i], 'readable')
                                    divMeal.appendChild(h6MealDate)

                                    // select address
                                    let selectAddress = document.createElement('div')
                                    selectAddress.className = "select-address-title"
                                    selectAddress.textContent = "Select Addresss"
                                    divMeal.appendChild(selectAddress)

                                    let divAddress = document.createElement('div')
                                    divAddress.className = "addresses-wrapper"
                                    addressDisplay(divAddress, addresses, null, mealsInForm, true, true)
                                    divMeal.appendChild(divAddress)

                                    let selectMealTitle = document.createElement('div')
                                    selectMealTitle.className = "select-meal-title"
                                    selectMealTitle.textContent = "Select Meal"
                                    divMeal.appendChild(selectMealTitle)


                                    let mpSelect
                                        // loop through calories
                                    if (postponedOrders.length > 0) {

                                        let listOrderWrapper = document.createElement('div')
                                        listOrderWrapper.className = "order-wrapper"
                                        divMeal.appendChild(listOrderWrapper)


                                        // Meal Plan
                                        let mealPlan = document.createElement('div')
                                        mealPlan.className = "meal-plan"
                                        let spanMPTitle = document.createElement('label')
                                        spanMPTitle.textContent = "Meal Plan"
                                        mealPlan.appendChild(spanMPTitle)

                                        let spanMPSelect = document.createElement('span')
                                        spanMPSelect.className = "select-span"
                                        mealPlan.appendChild(spanMPSelect)

                                        mpSelect = document.createElement('select')
                                        mpSelect.setAttribute("name", "select-meal-plan" + mealsInForm)
                                        mpSelect.setAttribute("id", "select-meal-plan" + mealsInForm)
                                        mpSelect.setAttribute("class", "select-meal-plan")
                                        spanMPSelect.appendChild(mpSelect)

                                        //Create and append the options
                                        // reset postpone meals temporarily
                                        //available_meals_arr = []

                                        if (available_meals_arr.length > 0) {
                                            var meal_option = document.createElement("option")
                                            meal_option.value = ''
                                            meal_option.text = 'Select'
                                            meal_option.selected = true

                                            mpSelect.appendChild(meal_option)

                                            available_meals_arr.map(meal_obj => {
                                                meal_option = document.createElement("option")
                                                meal_option.value = meal_obj.order_id
                                                    //meal_option.text = meal_obj.meal_plan_name + ' - ' + meal_obj.calories

                                                meal_option.text = meal_obj.meal_plan_name + meal_obj.meal_type
                                                meal_option.setAttribute('data-meal-id', meal_obj.meal_plan_id)
                                                meal_option.setAttribute('data-meal-name', meal_obj.meal_plan_id)
                                                meal_option.setAttribute('data-calories', meal_obj.calories)
                                                mpSelect.appendChild(meal_option)
                                            })
                                        }


                                        for (var k = 0; k < timeSlots.length; k++) {}

                                        listOrderWrapper.appendChild(mealPlan)


                                        // Delivery Time
                                        let divDelivery = document.createElement('div')
                                        divDelivery.className = "delivery-time"
                                        let spanTitle = document.createElement('label')
                                        spanTitle.textContent = "Delivery Time"
                                        divDelivery.appendChild(spanTitle)

                                        let spanSelect = document.createElement('span')
                                        spanSelect.className = "select-span"
                                        divDelivery.appendChild(spanSelect)

                                        let select = document.createElement('select')
                                        select.setAttribute("name", "select-delivery-time" + mealsInForm)
                                        select.setAttribute("id", "select-delivery-time" + mealsInForm)
                                        spanSelect.appendChild(select)

                                        //Create and append the options
                                        for (var j = 0; j < timeSlots.length; j++) {
                                            var option = document.createElement("option")
                                            option.value = timeSlots[j]
                                            option.text = timeSlots[j]
                                            select.appendChild(option)
                                        }

                                        listOrderWrapper.appendChild(divDelivery)

                                    }

                                    newMealWrapper.appendChild(divMeal)


                                    if (i === (dateRange.length - 1)) {
                                        break
                                    }
                                    mealsInForm++
                                }

                                if (buttonAddress) {
                                    buttonAddress.remove()
                                }
                                if (postponedOrders.length > 0) {
                                    buttonAddress = document.createElement('button')
                                    buttonAddress.setAttribute("id", "update-delivery-dates")
                                    buttonAddress.setAttribute("type", "submit")
                                    buttonAddress.textContent = "Update"
                                    addOrdersForm.appendChild(buttonAddress)

                                    let ordersAmountField = null
                                    ordersAmountField = document.createElement('input')
                                    ordersAmountField.setAttribute("id", "postponed-orders-amount")
                                    ordersAmountField.setAttribute("name", "postponed-orders-amount")
                                    ordersAmountField.setAttribute("type", "hidden")
                                    ordersAmountField.value = differenceInDays
                                    hiddenWrapper.appendChild(ordersAmountField)
                                }


                                // newMealIntro.innerHTML = "You have selected " + formatDate(dateRangeString, 'readable'); 
                                if (!calendarActionWrapper.classList.contains("calendar-active")) {
                                    calendarActionWrapper.classList.add("calendar-wrapper-active")
                                    calendarAction.classList.add("calendar-action-active")
                                }



                                // function to handle select options feeding /// expects the select_meal element ID and selected meal ID
                                const handleMealOptions = (select_meal_wrapper_id, selected_meal_id) => {

                                    // loop through each meal select element
                                    document.querySelectorAll('.select-meal-plan').forEach(function(el) {
                                        var all_select_meal_wrapper_id = el.getAttribute('id')

                                        if (all_select_meal_wrapper_id != select_meal_wrapper_id) {
                                            Array.from(el.options).forEach(function(option_element) {
                                                let option_value = option_element.value
                                                    // to disable the option, the option in the loop should be same as crrent option and the option selected should not be empty
                                                if (selected_meal_id == option_value && selected_meal_id != '') {
                                                    option_element.setAttribute('disabled', true)
                                                } else {
                                                    option_element.removeAttribute('disabled')
                                                }
                                            })
                                        }
                                    })
                                }

                                // loop through each meal select element
                                document.querySelectorAll('.select-meal-plan').forEach(function(el) {
                                    el.addEventListener('change', function() {
                                        var select_meal_wrapper_id = this.getAttribute('id')
                                        handleMealOptions(select_meal_wrapper_id, this.value)
                                    })
                                })


                                // click event for confirm mealtime button
                                const postNewOrderDays = (event) => {
                                    loaderImage.style.display = "block"
                                    event.preventDefault()

                                    let formData = {}
                                    Object.keys(addOrdersForm.elements).forEach(key => {
                                        let element = addOrdersForm.elements[key];
                                        if (element.type !== "submit") {
                                            formData[element.name] = element.value;
                                        }
                                    });

                                    let orders = [];

                                    const addressesNodes = document.getElementsByName("select-address1");

                                    let selectedAddressId = Array.prototype.slice.call(addressesNodes, 0).filter(element => element.checked)[0].value;
                                    let selectedDeliveryTime = formData[`select-delivery-time1`];
                                    let orderDate = formData[`order-date1`];

                                    const timeArray = selectedDeliveryTime.split(" - ");

                                    let order = {
                                        deliveryTimeFrom: timeArray[0],
                                        deliveryTimeTo: timeArray[1],
                                        customerId: customer.id,
                                        date: orderDate,
                                        postponedOrderId: postponedOrders[0].orderId,
                                        mealPlanId: postponedOrders[0].mealPlan.id,
                                        addressId: selectedAddressId,
                                        remark: ""
                                    };
                                    orders.push(order);


                                    const extraNewOrderDays = {
                                        method: "POST",
                                        data: {
                                            orders
                                        }
                                    }
                                    const newOrderDays = eatologyAPICall("extraNewOrderDays", extraNewOrderDays).then(data => {
                                        // change address in DOM
                                        const message = 'Orders successfully added'
                                        if (data.message === message) {
                                            console.log(message)
                                            resetPage()
                                            return false
                                        } else {
                                            console.log('Could not insert')
                                            loaderImage.style.display = "none"
                                        }
                                    })
                                    return
                                }
                                addOrdersForm.addEventListener("submit", postNewOrderDays)
                            },
                            // UNCOMMENT BELOW TO SHOW PAST DATES
                            validRange: {
                                start: tomorrow,
                                end: ninetyDays
                            },
                            headerToolbar: {
                                center: 'prev, title, next',
                                start: '',
                                end: 'today'
                            },
                            selectOverlap: function(event) {
                                return event.rendering === 'background'
                            },
                            events: orderEvents,
                            eventClick: function(info) {
                                info.jsEvent.preventDefault() // don't let the browser navigate
                                if (info.event.display === 'background') {
                                    return
                                }
                                noBodyScrolling()
                                    // hide add neww address and show calendar edit
                                if (calendarNewEditAddress.classList.contains("active")) {
                                    calendarNewEditAddress.classList.remove("active")
                                }
                                if (!calendarEdit.classList.contains("active")) {
                                    calendarEdit.classList.add("active")
                                }
                                if (calendarNewMeal.classList.contains("active")) {
                                    calendarNewMeal.classList.remove("active")
                                }
                                if (info.event.display !== 'background') {
                                    //extendedProps writeable
                                    let extendedProps = Object.assign({}, info.event.extendedProps)
                                        // format date
                                    var d = new Date(extendedProps.date),
                                        month = d.toLocaleString('default', { month: 'long' }),
                                        day = d.getDate(),
                                        year = d.getFullYear(),
                                        isDayTomorrow = isTomorrow(extendedProps.date)

                                    // reset html as empty
                                    calendarIntro.innerHTML = ''
                                    confirmActionSpacePostpone.innerHTML = ''
                                    confirmActionSpaceDelivery.innerHTML = ''
                                    confirmActionSpaceAddress.innerHTML = ''
                                    confirmActionSpaceMeal.innerHTML = ''

                                    // find the specific order detail
                                    var orderDetails = orders.find(order => order.id === extendedProps.id);

                                    // set h3 text
                                    calendarH3.innerHTML = `Order Details: ${month} ${day}, ${year}`

                                    // set calendarIntro info
                                    let div = document.createElement('div')
                                    div.innerHTML = `<span class="plan-title">Meal Plan:</span> <span class="plan-value">${extendedProps.mealPlan}</span>`
                                    calendarIntro.appendChild(div)

                                    let div2 = document.createElement('div')
                                    div2.innerHTML = `<span class="plan-title">Number of Calories:</span> <span class="plan-value">${extendedProps.calories}</span>`
                                    calendarIntro.appendChild(div2)

                                    let div3 = document.createElement('div')
                                    div3.innerHTML = `<span class="plan-title">Selected Meals:</span> <span class="plan-value">${extendedProps.selectedMeals}</span>`
                                    calendarIntro.appendChild(div3)

                                    //calendarIntro.innerHTML = "You have selected to postpone meal deliveries on" + info.event.start + ". Confirming will add 1 day to the days available."
                                    calendarActionSlot.classList.add("calendar-initial-active")


                                    // add postpone details
                                    let h6Postpone = document.createElement('h6')
                                    h6Postpone.textContent = "Confirm postpone?"
                                    let currentDeliveryTime = displayCurrentOrderDetails('delivery-time', orderDetails)
                                    if (currentDeliveryTime) {
                                        confirmActionSpacePostpone.appendChild(currentDeliveryTime)
                                    }
                                    let currentAddress = displayCurrentOrderDetails('address', orderDetails)
                                    if (currentAddress) {
                                        confirmActionSpacePostpone.appendChild(currentAddress)
                                    }
                                    confirmActionSpacePostpone.appendChild(h6Postpone)

                                    let pPostpone = document.createElement('p')
                                    pPostpone.innerHTML = `<p>You have selected to postpone  meal deliveries for ${day}-${month}-${year}. Confirming will add one day to the days available.<br>
                                    Current days available : ${customer.postponedOrders.length}<br>
                                    After postponing, days available: ${customer.postponedOrders.length +1}`
                                    confirmActionSpacePostpone.appendChild(pPostpone)

                                    let buttonPostpone = document.createElement('button')
                                    buttonPostpone.setAttribute("id", "calendar-confirm-postpone--confirmed")
                                    buttonPostpone.textContent = "Confirm";
                                    // do not allow update for tomorrow orders
                                    if (isDayTomorrow) {
                                        buttonPostpone.disabled = true;
                                    }
                                    confirmActionSpacePostpone.appendChild(buttonPostpone)

                                    // click event for confirm postpone button
                                    const postPostpone = () => {
                                        loaderImage.style.display = "block"
                                        const extraPostponeOrder = {
                                            method: "POST",
                                            wooId: wpUId(),
                                            data: {
                                                orders: [{
                                                    id: extendedProps.id,
                                                    customerId: customer.id,
                                                    districtZone: 'HONG KONG ISLAND' // need to change after addresses have districtZone
                                                        //districtZone: extendedProps.districtZone
                                                }]
                                            }
                                        }
                                        const postponeOrder = eatologyAPICall("extraPostponeOrder", extraPostponeOrder).then(data => {
                                            const message = 'Orders successfully postponed'
                                            if (data.message === message) {
                                                console.log(message)
                                                resetPage()
                                            } else {
                                                console.log('Could not postpone order')
                                                loaderImage.style.display = "none"
                                            }
                                        })
                                    }
                                    buttonPostpone.addEventListener("click", postPostpone)

                                    // update extendedProps for addressId
                                    if (typeof extendedProps['addressId'] === 'undefined' && typeof orderDetails.address !== 'undefined') {
                                        extendedProps['addressId'] = orderDetails.address.id
                                    }



                                    // add address details
                                    let h6Address = document.createElement('h6')
                                    h6Address.textContent = "Update Address"
                                    confirmActionSpaceAddress.appendChild(h6Address)

                                    let divAddress = document.createElement('div')
                                    divAddress.className = "addresses"
                                    addressDisplay(divAddress, addresses, extendedProps, false, true)
                                    confirmActionSpaceAddress.appendChild(divAddress)

                                    let buttonAddress = document.createElement('button')
                                    buttonAddress.setAttribute("id", "calendar-confirm-address--confirmed")
                                    buttonAddress.textContent = "Change";
                                    // do not allow update for tomorrow orders
                                    if (isDayTomorrow) {
                                        buttonAddress.disabled = true;
                                    }
                                    confirmActionSpaceAddress.appendChild(buttonAddress)


                                    // click event for confirm meal plan button
                                    const postChangeOrderAddress = () => {
                                        loaderImage.style.display = "block"
                                        const newAddressId = document.querySelector('input[name="select-address"]:checked').value

                                        if (newAddressId === null) {
                                            alert('Please select a new address')
                                            return
                                        }

                                        const extraChangeOrderAddress = {
                                            method: "POST",
                                            data: {
                                                orders: [{
                                                    id: extendedProps.id,
                                                    addressId: newAddressId,
                                                }]
                                            }
                                        }

                                        const postOrderAddress = eatologyAPICall("extraChangeOrderAddress", extraChangeOrderAddress).then(data => {
                                            const message = 'Orders successfully edited'
                                            if (data.message === message) {
                                                console.log(message)
                                                resetPage()
                                            } else {
                                                console.log('Could not change order address')
                                                loaderImage.style.display = "none"
                                            }
                                        })
                                    }
                                    buttonAddress.addEventListener("click", postChangeOrderAddress)



                                    // add delivery time details
                                    let h6Delivery = document.createElement('h6')
                                    h6Delivery.textContent = "Update Time"
                                    confirmActionSpaceDelivery.appendChild(h6Delivery)

                                    let divDelivery = document.createElement('div')
                                    divDelivery.className = "delivery-time"
                                    let spanTitle = document.createElement('label')
                                    spanTitle.textContent = "Delivery Time"
                                    divDelivery.appendChild(spanTitle)

                                    let spanSelect = document.createElement('span')
                                    spanSelect.className = "select-span" + (isDayTomorrow ? " disabled" : "")
                                    divDelivery.appendChild(spanSelect)

                                    let select = document.createElement('select')
                                    select.setAttribute("name", "select-delivery-time")
                                    select.setAttribute("id", "select-delivery-time")
                                    spanSelect.appendChild(select)

                                    //Create and append the options
                                    const deliveryTimeString = extendedProps.deliveryTimeFrom + ' - ' + extendedProps.deliveryTimeTo
                                    for (var i = 0; i < timeSlots.length; i++) {
                                        var option = document.createElement("option")
                                        option.value = timeSlots[i]
                                        option.text = timeSlots[i]
                                        if (deliveryTimeString === timeSlots[i]) {
                                            option.selected = true
                                        }
                                        select.appendChild(option)

                                    }
                                    confirmActionSpaceDelivery.appendChild(divDelivery)

                                    let buttonDelivery = document.createElement('button')
                                    buttonDelivery.setAttribute("id", "calendar-confirm-delivery--confirmed")
                                    buttonDelivery.textContent = "Change";
                                    // do not allow update for tomorrow orders
                                    if (isDayTomorrow) {
                                        buttonDelivery.disabled = true;
                                    }
                                    confirmActionSpaceDelivery.appendChild(buttonDelivery)

                                    // click event for confirm mealtime button
                                    const postChangeDeliveryTime = (select) => {
                                        loaderImage.style.display = "block"
                                        const timeArray = select.value.split(" - ")

                                        const extraChangeDeliveryTime = {
                                            method: "POST",
                                            data: {
                                                orders: [{
                                                    id: extendedProps.id,
                                                    deliveryTimeFrom: timeArray[0],
                                                    deliveryTimeTo: timeArray[1]
                                                }]
                                            }
                                        }

                                        const postdeliveryTime = eatologyAPICall("extraChangeDeliveryTime", extraChangeDeliveryTime).then(data => {
                                            const message = 'Orders successfully edited'
                                            if (data.message === message) {
                                                console.log(message)
                                                resetPage()
                                            } else {
                                                console.log('Could not change delivery time')
                                                loaderImage.style.display = "none"
                                            }
                                        })
                                    }
                                    buttonDelivery.addEventListener("click", () => postChangeDeliveryTime(select))

                                    // add meal details
                                    let h6Meal = document.createElement('h6')
                                    h6Meal.textContent = "Update Meal Plan"
                                    confirmActionSpaceMeal.appendChild(h6Meal)

                                    let pMeal = document.createElement('p')
                                    pMeal.textContent = "Only meal plans with the same number of calories will be available."
                                    confirmActionSpaceMeal.appendChild(pMeal)

                                    let divMeal = document.createElement('div')
                                    divMeal.className = "delivery-time"
                                    let spanTitleMeal = document.createElement('label')
                                    spanTitleMeal.textContent = "Select Meal Plans"
                                    divMeal.appendChild(spanTitleMeal)


                                    let spanSelectMeal = document.createElement('span')
                                    spanSelectMeal.className = "select-span" + (isDayTomorrow ? " disabled" : "")
                                    divMeal.appendChild(spanSelectMeal)


                                    let selectMeal = document.createElement('select')
                                    selectMeal.setAttribute("name", "select-meal")
                                    selectMeal.setAttribute("id", "select-meal")
                                    spanSelectMeal.appendChild(selectMeal)

                                    // update mealPlan
                                    if (typeof extendedProps['mealPlanId'] === 'undefined' && typeof orderDetails.mealPlan !== 'undefined') {
                                        extendedProps['mealPlanId'] = orderDetails.mealPlan.id;
                                    }


                                    const mealPlansSameCalories = mealPlans.filter(mealPlan => mealPlan.calories === extendedProps.calories)

                                    //Create and append the options
                                    for (var i = 0; i < mealPlansSameCalories.length; i++) {
                                        var optionMeal = document.createElement("option")
                                        optionMeal.value = mealPlansSameCalories[i].id;
                                        optionMeal.text = mealPlansSameCalories[i].menuName;

                                        if (extendedProps.mealPlanId === mealPlansSameCalories[i].id) {
                                            optionMeal.selected = true
                                        }
                                        selectMeal.appendChild(optionMeal)
                                    }

                                    confirmActionSpaceMeal.appendChild(divMeal)

                                    let buttonMeal = document.createElement('button')
                                    buttonMeal.setAttribute("id", "calendar-confirm-meal--confirmed")
                                    buttonMeal.textContent = "Change";
                                    // do not allow update for tomorrow orders
                                    if (isDayTomorrow) {
                                        buttonMeal.disabled = true;
                                    }
                                    confirmActionSpaceMeal.appendChild(buttonMeal)

                                    if (!calendarActionWrapper.classList.contains("calendar-active")) {
                                        calendarActionWrapper.classList.add("calendar-wrapper-active")
                                        calendarAction.classList.add("calendar-action-active")
                                    }


                                    // click event for confirm mealtime button
                                    const postChangeMealPlan = (selectMeal) => {
                                        loaderImage.style.display = "block"
                                        const extraChangeMealPlan = {
                                            method: "POST",
                                            data: {
                                                orders: [{
                                                    id: extendedProps.id,
                                                    mealPlanId: selectMeal.value
                                                }]
                                            }
                                        }

                                        const postMealPlan = eatologyAPICall("extraChangeMealPlan", extraChangeMealPlan).then(data => {
                                            const message = 'Orders successfully edited'
                                            if (data.message === message) {
                                                console.log(message)
                                                resetPage()
                                            } else {
                                                console.log('Could not change meal plan')
                                                loaderImage.style.display = "none"
                                            }
                                        })
                                    }
                                    buttonMeal.addEventListener("click", () => postChangeMealPlan(selectMeal))


                                }
                            },
                            eventColor: '#F9F5FB',
                            eventBackgroundColor: '#F9F8F9',
                            eventAllow: function(dropInfo) {
                                if (!validateDropDate(dropInfo.start)) {
                                    console.log('NOT ALLOWED to drop in tomorrow or sunday cell');
                                    return false;
                                }
                                return true;
                            },
                            eventDrop: function(info) {
                                const newDate = info.event.startStr.slice(0, 10)
                                    // check this date is ok
                                    // post to change date
                                loaderImage.style.display = "block"
                                const extraChangeDeliveryDate = {
                                    method: "POST",
                                    data: {
                                        orders: [{
                                            id: info.event.extendedProps.id,
                                            date: newDate
                                        }]
                                    }
                                }

                                const postdeliveryTime = eatologyAPICall("extraChangeDeliveryDate", extraChangeDeliveryDate).then(data => {
                                    const message = 'Orders successfully edited'
                                    if (data.message === message) {
                                        console.log(message)
                                        resetPage()
                                    } else {
                                        console.log('Could not change delivery day')
                                        loaderImage.style.display = "none"
                                    }
                                })
                            },
                        })

                        calendar.render()
                        loaderImage.style.display = "none"
                    }

                })
            }
            return
        }
        document.addEventListener('DOMContentLoaded', populateCalendar(trackingFunctions))
    }
}
export default myAccountCalendar