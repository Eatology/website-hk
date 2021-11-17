import eatologyAPICall, { wpUId } from './apiCall'
const menuDescriptionPage = () => {

    var elem = document.querySelector('#menu-description-page');
        if (!elem) {
            return
        }

    function populateMenuDescription() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        let date;
        let mealPlanId;
        let startDate;
        let endDate;
        let type;
        let count = 0;
        
        if(urlParams.has('date') && urlParams.has('mealId') && urlParams.has('startDate') && urlParams.has('endDate') && urlParams.has('type')){
            date = urlParams.get('date');
            mealPlanId  = urlParams.get('mealId');
            startDate  = urlParams.get('startDate');
            endDate  = urlParams.get('endDate');
            type  = urlParams.get('type');
        }
        
        const extraGetMenu = {
            method: "GET",
            mealPlanId: mealPlanId,
            startDate: startDate,
            endDate: endDate,
        }

        const getMenuData = eatologyAPICall("extraGetMenu", extraGetMenu).then(data => {
            
            let results = data.sneakPeekData;
            let otherDishesList = "";
            let searchKey = date.split("-");
            let urlOrigin = window.location.origin;

            for (let [key, value] of Object.entries(results)) {

                let dataValue = value;

                //Selected Menu
                if(key.includes(searchKey[0])){
                    for (let [key, value] of Object.entries(dataValue)) {

                        if(key.includes(type)){

                            document.getElementById("menuCategory").innerHTML = value.tradeName;

                            let ratingStarList = "";
                            let allergensList = "";

                            for (let i = 0; i < value.rating; i++) {
                                ratingStarList += '<li class="star-full"></li>';
                            }

                            if(value.allergens.length > 0){
                                allergensList += '<span>MAY CONTAIN:</span>';
                                allergensList += '<ul class="allergies" >';
                                for (let i = 0; i < value.allergens.length; i++) {
                                    
                                    if(value.allergens[i] != ""){
                                        allergensList += '<li class="allergens-dairy">';
                                        allergensList += '<span>'+value.allergens[i]+'</span>';
                                        allergensList += '</li>';
                                    }
                                    
                                }
                                allergensList += '</ul>';
                                
                            }else{
                                document.getElementsByClassName("allergies-wrap").innerHTML = "";
                            }

                            document.getElementById("menuImage").innerHTML = '<img src="'+value.image+'" alt="">';
                            document.getElementById("menuTitle").innerHTML = value.name;
                            document.getElementById("menuType").innerHTML = type;
                            document.getElementById("menuDescription").innerHTML = value.shortDescription;
                            document.getElementById("menuRatingValue").innerHTML = value.ratingNumbers;
                            document.getElementById("menuRating").innerHTML = ratingStarList;
                            document.getElementById("menuDetails").innerHTML = value.longDescription;;
                            document.getElementById("menuAllergens").innerHTML = allergensList;

                        }

                    }
                    
                }
                //Other dishes for the week
                if(!key.includes(searchKey[0])){
                    let title = key.replace(",", "");
                    title = title.replace(" ", "-");
                    for (let [key, value] of Object.entries(dataValue)) {
                        if(key.includes(type)){
                            const cardNum = "cardNUm"+count++;
                            otherDishesList += '<a href="'+urlOrigin+'/menu-description/?date='+title+'&mealId='+mealPlanId+'&startDate='+startDate+'&endDate='+endDate+'&type='+key+'" class="c-card-menu '+cardNum+'">';
                            otherDishesList += '<div class="c-card-menu__image">';
                            otherDishesList += '<picture>';
                            otherDishesList += '<img src="'+value.image+'" alt="">';
                            otherDishesList += '</picture>';
                            otherDishesList += (value.tag != "") ? '<span class="best-seller">'+value.tag+'</span>' : "";

                            otherDishesList += '<ul class="star-rating">';
                            
                            for (let i = 0; i < value.rating; i++) {
                                otherDishesList += '<li class="star-full"></li>';
                            }

                            otherDishesList += '</ul>';

                            otherDishesList += '</div>';
                            otherDishesList += '<span class="category">'+key+'</span>';
                            otherDishesList += '<h4>'+value.name+'</h4>';
                            otherDishesList += '<span class="description">'+value.shortDescription+'</span>';
                            otherDishesList += '<ul class="allergies">';

                            if(value.allergens.length > 0){

                                for (let i = 0; i < value.allergens.length; i++) {
                                    
                                    if(value.allergens[i] != ""){
                                        otherDishesList += '<li class="allergens-dairy">';
                                        otherDishesList += '<span>'+value.allergens[i]+'</span>';
                                        otherDishesList += '</li>';
                                    }
                                    
                                }
                                
                            }

                            otherDishesList += '</ul>';
                            otherDishesList += '</a>';

                        }

                    }
                    
                }
                document.getElementById("otherDishes").innerHTML = otherDishesList;
            }
            
        });

    }

    document.addEventListener('DOMContentLoaded', populateMenuDescription())
}

export default menuDescriptionPage