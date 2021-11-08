import eatologyAPICall, { wpUId } from './apiCall'

const menuPage = () => {
    var mealPlanId = 1;
    var startDate = startDate();
    var endDate = endtDate();

    var elem = document.querySelector('#menu-page');
        if (!elem) {
            return
        }

    let calendarDates = document.querySelectorAll('.calender-link'),
        sideNav = document.querySelector('.js-side-nav'),
        prevArrow = document.querySelectorAll('.js-prev-arrow'),
        nextArrow = document.querySelectorAll('.js-next-arrow'),
        mobileSideNav = document.querySelector('.js-mobile-sidemenu-nav'),
        closeSideNav = document.querySelector('.js-close-side-nav');

    if ( sideNav ) {
        document.getElementById('main-content').style.overflowX = 'unset';

        sideNav.querySelectorAll('a').forEach(elem => {

            elem.addEventListener('click', function(){
                
                sideNav.querySelector('.is-active').classList.remove('is-active');
                this.classList.add('is-active');
                mealPlanId = this.getAttribute('data-link');
                populateMenu(mealPlanId, startDate, endDate);

            });
        });
    }

    prevArrow.forEach(prev => {
        prev.addEventListener('click', function(){
           
            const prevStartDate = new Date(startDate);
            prevStartDate.setDate(prevStartDate.getDate() - 6);
            startDate = formatDate(prevStartDate);

            const prevEndDate = new Date(startDate);
            prevEndDate.setDate(prevEndDate.getDate() + 5);

            endDate = formatDate(prevEndDate);

            populateMenu(mealPlanId, startDate, endDate);
        });
    });

    nextArrow.forEach(next => {
        next.addEventListener('click', function(){
            
            const nextStartDate = new Date(endDate);
            nextStartDate.setDate(nextStartDate.getDate() + 1);

            const nextEndDate = new Date(nextStartDate);
            nextEndDate.setDate(nextEndDate.getDate() + 5);

            startDate = formatDate(nextStartDate);
            endDate = formatDate(nextEndDate);

            populateMenu(mealPlanId, startDate, endDate);

        });
    });

    calendarDates.forEach(nav => {
        nav.addEventListener('click', function(){

            let container = nav.dataset.link,
            containerOffset = document.querySelector(container).offsetTop;

            if ( window.innerWidth < 1024) {
                window.scroll({
                    top: containerOffset - 158,
                    left: 0,
                    behavior: 'smooth'
                });
            } else {
                window.scroll({
                    top: containerOffset - 20,
                    left: 0,
                    behavior: 'smooth'
                });
            }

        });
    });

    mobileSideNav.addEventListener('click', function(){
        document.querySelector('.js-side-nav-bar').classList.toggle('is-show-nav');
        document.body.classList.add('body-lock');
    });
    
    closeSideNav.addEventListener('click', function(){
        document.querySelector('.js-side-nav-bar').classList.toggle('is-show-nav');
        document.body.classList.remove('body-lock');
    })

    window.addEventListener("scroll", event => {
        let calendarHeader = document.querySelectorAll('.js-calendar-header'),
            celendarSection = document.querySelector('.js-menu-section');
        
        // Sticky Calendar
        if ( window.innerWidth > 1024) { // Desktop
            if ( window.scrollY > 130 ) {
                calendarHeader.forEach(calendarHead => {
                    calendarHead.classList.add('sitcky-calendar');
                });
                celendarSection.classList.add('is-scrolled');
            } else {
                calendarHeader.forEach(calendarHead => {
                    calendarHead.classList.remove('sitcky-calendar');
                });
                celendarSection.classList.remove('is-scrolled');
            }
        } else { // Mobile
            if ( window.scrollY > 80 ) {
                calendarHeader.forEach(calendarHead => {
                    calendarHead.classList.add('sitcky-calendar');
                });
                mobileSideNav.classList.add('is-scrolled');
            } else {
                calendarHeader.forEach(calendarHead => {
                    calendarHead.classList.remove('sitcky-calendar');
                });
                mobileSideNav.classList.remove('is-scrolled');
            }
        }
        
        calendarDates.forEach(section => {
            let container = section.dataset.link;

            if (document.querySelector(container)) {
                let containerOffset = document.querySelector(container).offsetTop,
                containerHeight = document.querySelector(container).offsetHeight,
                containerBottom = containerOffset + containerHeight,
                scrollPosition = window.scrollY;

                if ( window.innerWidth > 1024) { // Desktop

                    if (scrollPosition < containerBottom - 20 && scrollPosition >= containerOffset - 20){
                        section.classList.add('is-active');
                    } else {
                        section.classList.remove('is-active');
                    }

                } else { // Mobile

                    if (scrollPosition < containerBottom - 220 && scrollPosition >= containerOffset - 220){
                        section.classList.add('is-active');
                    } else {
                        section.classList.remove('is-active');
                    }

                }
            }

        });


    });
    
    function populateMenu(mealPlanId, startDate, endDate) {
        
        calendarTitle(startDate, endDate);

        const extraGetMenu = {
            method: "GET",
            mealPlanId: mealPlanId,
            startDate: startDate,
            endDate: endDate,
        }

        const getMenuData = eatologyAPICall("extraGetMenu", extraGetMenu).then(data => {
            
            let results = data.sneakPeekData;
            let list = '';  
            let count = 1;        
            for (let [key, value] of Object.entries(results)) {
                let dataValue = value;
                let cardList = '';
                let title = key.replace(",", "");
                title = title.replace(" ", "-");
                let calendarId = title.split("-");

                list += '<div id="'+calendarId[0]+'" class="menu-item-wrap">';
                list += '<span class="menu-item__header">'+key+'</span>';
                list += '<div class="menu-item__row">';

                for (let [key, value] of Object.entries(dataValue)) {
                    cardList += '<a href="/menu-description/?date='+title+'&mealId='+mealPlanId+'&startDate='+startDate+'&endDate='+endDate+'&type='+key+'" class="c-card-menu">';
                    cardList += '<div class="c-card-menu__image">';
                    cardList += '<picture>';
                    cardList += '<img src="'+value.image+'" alt="">';
                    cardList += '</picture>';
                    cardList += (value.tag != "") ? '<span class="best-seller">'+value.tag+'</span>' : "";

                    cardList += '<ul class="star-rating">';

                    for (let i = 0; i < value.rating; i++) {
                        cardList += '<li class="star-full"></li>';
                    }

                    cardList += '</ul>';

                    cardList += '</div>';
                    cardList += '<span class="category">'+key+'</span>';
                    cardList += '<h4>'+value.name+'</h4>';
                    cardList += '<span class="description">'+value.description+'</span>';
                    cardList += '<ul class="allergies">';

                    if(value.allergens.length > 0){

                        for (let i = 0; i < value.allergens.length; i++) {
                            
                            if(value.allergens[i] != ""){
                                cardList += '<li class="allergens-'+value.allergens[i].toLowerCase()+'">';
                                cardList += '<span>'+value.allergens[i]+'</span>';
                                cardList += '</li>';
                            }
                            
                        }
                        
                    }

                    cardList += '</ul>';
                    cardList += '</a>';

                }

                list += cardList;
                list += '</div>';
                list += '</div>';  
            }
            
            document.getElementById("menuList").innerHTML = list;
            
        });
    }

    function startDate(){
        
        const dateObj = new Date();
        const dateObjFormat = formatDate(dateObj);
        return dateObjFormat;

    }

    function endtDate(){

        const dateObj = new Date();
        dateObj.setDate(dateObj.getDate() + 5); 
        const dateObjFormat = formatDate(dateObj);
        return dateObjFormat;
    }

    function formatDate(dateObj){

        const month = dateObj.getMonth()+1;
        const day = String(dateObj.getDate()).padStart(2, '0');
        const year = dateObj.getFullYear();
        const output = year  + '-'+ month  + '-' + day;

        return output;

    }

    function calendarTitle(startDate, endDate){
        let title = "";

        const month = new Array();
        month[1] = "January";
        month[2] = "February";
        month[3] = "March";
        month[4] = "April";
        month[5] = "May";
        month[6] = "June";
        month[7] = "July";
        month[8] = "August";
        month[9] = "September";
        month[10] = "October";
        month[11] = "November";
        month[12] = "December";

        let startDateTitle = startDate.split("-");
        let endDateTitle = endDate.split("-");
        let startDayTitle = startDateTitle[2];
        let endDayTitle = endDateTitle[2];
        let startMonthTitle = month[startDateTitle[1]];
        let endMonthTitle = month[endDateTitle[1]];

        if(startMonthTitle != endMonthTitle){
            title  = startMonthTitle+" "+startDayTitle+" - "+endMonthTitle+" "+endDayTitle;
        }else{
            title  = startMonthTitle+" "+startDayTitle+" - "+endDayTitle;
        }

        var mobile = document.getElementsByClassName("calendar-title1");
        var desktop = document.getElementsByClassName("calendar-title2");
        mobile[0].innerHTML = title;
        desktop[0].innerHTML = title;

    }

    document.addEventListener('DOMContentLoaded', populateMenu(mealPlanId, startDate, endDate));
}

export default menuPage