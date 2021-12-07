import eatologyAPICall, { wpUId } from './apiCall'
const menuDescriptionPage = () => {
    var caloriesMealPlanId = "";

    var elem = document.querySelector('#menu-description-page');
        if (!elem) {
            return
        }

    // Calories Click
    
  

    function populateMenuDescription() {

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        let date;
        let mealPlanId;
        let startDate;
        let endDate;
        let type;
        let count = 0;
        let mealPlanName;
        
        if(urlParams.has('date') && urlParams.has('mealId') && urlParams.has('startDate') && urlParams.has('endDate') && urlParams.has('type')){
            date = urlParams.get('date');
            mealPlanId  = (caloriesMealPlanId) ? caloriesMealPlanId : urlParams.get('mealId');
            startDate  = urlParams.get('startDate');
            endDate  = urlParams.get('endDate');
            type  = urlParams.get('type');
        }

        
        mealPlanName = meal_plan_name_list(mealPlanId);

        calories_list(mealPlanName);

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

                            var nutrients = "";

                            nutrients+='<ul>';
                            nutrients+='<li>';
                            nutrients+='<span>'+value.nutrition.proteins+'g</span>';
                            nutrients+='<p>PROTEIN</p>';
                            nutrients+='</li>';
                            nutrients+='<li>';
                            nutrients+='<span>'+value.nutrition.carbs+'g</span>';
                            nutrients+='<p>CARBS</p>';
                            nutrients+='</li>';
                            nutrients+='<li>';
                            nutrients+='<span>'+value.nutrition.fats+'g</span>';
                            nutrients+='<p>FAT</p>';
                            nutrients+='</li>';
                            nutrients+='<li>';
                            nutrients+='<span>'+value.nutrition.calories+'kcal</span>';
                            nutrients+='<p>CALORIES</p>';
                            nutrients+='</li>';
                            nutrients+='</ul>'

                            var mainImage = "";
                            if(value.image == ""){
                                mainImage = 'https://storage.googleapis.com/webapp-dishes-image/missing-picture.jpeg';
                            }else{
                                mainImage = value.image;
                            }

                            document.getElementById("menuImage").innerHTML = '<picture><img src="'+mainImage+'" alt=""></picture>';
                            document.getElementById("menuTitle").innerHTML = value.name;
                            document.getElementById("menuType").innerHTML = type;
                            document.getElementById("menuDescription").innerHTML = value.shortDescription;
                            document.getElementById("menuRatingValue").innerHTML = value.ratingNumbers ? '('+value.ratingNumbers+')' : '';
                            document.getElementById("menuRating").innerHTML = ratingStarList;
                            document.getElementById("menuDetails").innerHTML = value.longDescription;;
                            document.getElementById("menuAllergens").innerHTML = allergensList;
                            document.getElementById("nutrients").innerHTML = nutrients;

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


    function calories_list(mealPlanName){

        var caloriesID = "";

        if(mealPlanName == "Asian"){ 
            caloriesID+='<input type="radio" id="tab1" name="tab" value="1" checked>';
            caloriesID+='<label for="tab1">1200 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab2" checked="checked" name="tab" value="2">';
            caloriesID+='<label for="tab2">1500 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab3" name="tab" value="3">';
            caloriesID+='<label for="tab3">1800 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab4" name="tab" value="192">';
            caloriesID+='<label for="tab4">2200 <span>KCAL</span></label>';
        }

        if(mealPlanName == "Low Carbs"){ 
            caloriesID+='<input type="radio" id="tab1" name="tab" value="5">';
            caloriesID+='<label for="tab1">1200 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab2" checked="checked" name="tab" value="6">';
            caloriesID+='<label for="tab2">1500 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab3" name="tab" value="7">';
            caloriesID+='<label for="tab3">1800 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab4" name="tab" value="189">';
            caloriesID+='<label for="tab4">2200 <span>KCAL</span></label>';
        }

        if(mealPlanName == "Mediterranean"){ 
            caloriesID+='<input type="radio" id="tab1" name="tab" value="8">';
            caloriesID+='<label for="tab1">1200 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab2" checked="checked" name="tab" value="9">';
            caloriesID+='<label for="tab2">1500 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab3" name="tab" value="10">';
            caloriesID+='<label for="tab3">1800 <span>KCAL</span></label>';
        }

        if(mealPlanName == "Keto Light"){ 
            caloriesID+='<input type="radio" id="tab1" name="tab" value="11">';
            caloriesID+='<label for="tab1">1200 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab2" checked="checked" name="tab" value="12">';
            caloriesID+='<label for="tab2">1500 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab3" name="tab" value="13">';
            caloriesID+='<label for="tab3">1800 <span>KCAL</span></label>'
            caloriesID+='<input type="radio" id="tab4" name="tab" value="191">';
            caloriesID+='<label for="tab4">2200 <span>KCAL</span></label>';
        }

        if(mealPlanName == "Optimal Performance"){ 
            caloriesID+='<input type="radio" id="tab1" name="tab" value="14">';
            caloriesID+='<label for="tab1">2200 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab2" checked="checked" name="tab" value="15">';
            caloriesID+='<label for="tab2">2600 <span>KCAL</span></label>';
        }

        if(mealPlanName == "Vegetarian"){ 
            caloriesID+='<input type="radio" id="tab1" name="tab" value="16">';
            caloriesID+='<label for="tab1">1200 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab2" checked="checked" name="tab" value="17">';
            caloriesID+='<label for="tab2">1500 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab3" name="tab" value="18">';
            caloriesID+='<label for="tab3">1800 <span>KCAL</span></label>'
            caloriesID+='<input type="radio" id="tab4" name="tab" value="193">';
            caloriesID+='<label for="tab4">2200 <span>KCAL</span></label>';
        }

        if(mealPlanName == "Vegan"){ 
            caloriesID+='<input type="radio" id="tab1" name="tab" value="19">';
            caloriesID+='<label for="tab1">1200 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab2" checked="checked" name="tab" value="20">';
            caloriesID+='<label for="tab2">1500 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab1" name="tab" value="94">';
            caloriesID+='<label for="tab1">1800 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab3" name="tab" value="195">';
            caloriesID+='<label for="tab3">2200 <span>KCAL</span></label>'
        }

        if(mealPlanName == "Paleo"){ 
            caloriesID+='<input type="radio" id="tab1" name="tab" value="25">';
            caloriesID+='<label for="tab1">1200 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab2" checked="checked" name="tab" value="26">';
            caloriesID+='<label for="tab2">1500 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab3" name="tab" value="27">';
            caloriesID+='<label for="tab3">1800 <span>KCAL</span></label>'
            caloriesID+='<input type="radio" id="tab4" name="tab" value="28">';
            caloriesID+='<label for="tab4">2200 <span>KCAL</span></label>';
        }

        if(mealPlanName == "F45 Challenge"){ 
            caloriesID+='<input type="radio" id="tab1" name="tab" value="157">';
            caloriesID+='<label for="tab1">1200 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab2" checked="checked" name="tab" value="158">';
            caloriesID+='<label for="tab2">1500 <span>KCAL</span></label>';
            caloriesID+='<input type="radio" id="tab3" name="tab" value="159">';
            caloriesID+='<label for="tab3">1800 <span>KCAL</span></label>'
            caloriesID+='<input type="radio" id="tab4" name="tab" value="196">';
            caloriesID+='<label for="tab4">2200 <span>KCAL</span></label>';

        }

        document.getElementById("caloriesCount").innerHTML = caloriesID;

        call_tab_calories();

    }

    /**
        1  ASIAN1200   for Asian
        5  LC1200      for Gluten-free Low Carbs
        8  LD1200      for lighter Delight & Mediterranean
        11 MAD1200    for Ketogetic diet light
        14 OP2200      for Optimal Performance
        16 VEG1200    for Vegetarian
        19 VEGAN1200  for Vegan
        25  PALEO1200  for Paleo
        157 F45 1200    for F45 Challenge
     *
     */

    function meal_plan_name_list(id){

        const meal = new Array();

        meal[1] = "Asian";
        meal[2] = "Asian";
        meal[3] = "Asian";
        meal[192] = "Asian";

        meal[5] = "Low Carbs";
        meal[6] = "Low Carbs";
        meal[7] = "Low Carbs";
        meal[189] = "Low Carbs";

        meal[8] = "Mediterranean";
        meal[9] = "Mediterranean";
        meal[10] = "Mediterranean";

        meal[11] = "Keto Light";
        meal[12] = "Keto Light";
        meal[13] = "Keto Light";
        meal[191] = "Keto Light";

        meal[14] = "Optimal Performance";
        meal[15] = "Optimal Performance";

        meal[16] = "Vegetarian";
        meal[17] = "Vegetarian";
        meal[18] = "Vegetarian";
        meal[193] = "Vegetarian";

        meal[19] = "Vegan";
        meal[20] = "Vegan";
        meal[94] = "Vegan";
        meal[195] = "Vegan";

        meal[25] = "Paleo";
        meal[26] = "Paleo";
        meal[27] = "Paleo";
        meal[28] = "Paleo";

        meal[157] = "F45 Challenge";
        meal[158] = "F45 Challenge";
        meal[159] = "F45 Challenge";
        meal[196] = "F45 Challenge";

        return meal[id];

    }

    function call_tab_calories(){
        
        if (document.querySelector('#tab1')) {
            document.getElementById('tab1').addEventListener('click', function(){
                caloriesMealPlanId = document.getElementById("tab1").value;
                populateMenuDescription();
                document.getElementById("tab1").checked=true;
    
            });
        }

        if (document.querySelector('#tab2')) {
            document.getElementById('tab2').addEventListener('click', function(){
                caloriesMealPlanId = document.getElementById("tab2").value;
                populateMenuDescription();
                document.getElementById("tab2").checked=true;
            });
        }
    
        if (document.querySelector('#tab3')) {
            document.getElementById('tab3').addEventListener('click', function(){

                caloriesMealPlanId = document.getElementById("tab3").value;
                populateMenuDescription();
                document.getElementById("tab3").checked=true;
    
            });
        }
    
        if (document.querySelector('#tab4')) {
            document.getElementById('tab4').addEventListener('click', function(){
            
                caloriesMealPlanId = document.getElementById("tab4").value;
                populateMenuDescription();
                document.getElementById("tab4").checked=true;
    
            });
        }

    }

    document.addEventListener('DOMContentLoaded', populateMenuDescription())
}

export default menuDescriptionPage