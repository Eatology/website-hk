import eatologyAPICall, { wpUId } from './apiCall'

const menuPage = () => {
    var dateInitial = new Date;
    var mealPlanId = 12
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

    var prevClick;
    var nextClick;
    prevArrow.forEach(prev => {
        prevClick = 0;
        prev.addEventListener('click', function(){
            console.log(prevClick);
            
            if (prevClick == 0) {
                event.stopPropagation();
                prevClick = 0;
            } else if (prevClick == 1) {
                // prevClick += 1;
                
                this.setAttribute('data-count-click', prevClick);

                const prevStartDate = new Date(startDate);
                prevStartDate.setDate(prevStartDate.getDate() - 7);
                startDate = formatDate(prevStartDate);

                const prevEndDate = new Date(startDate);
                prevEndDate.setDate(prevEndDate.getDate() + 5);

                endDate = formatDate(prevEndDate);

                populateMenu(mealPlanId, startDate, endDate);

                // if (prevClick == 2) {
                //     this.classList.remove('is-active');
                //     this.parentElement.querySelector('.js-next-arrow').setAttribute('data-count-click', 0);
                //     nextClick = 0;
                // }

                // if (prevClick == 1) {
                    this.parentElement.querySelector('.js-next-arrow').setAttribute('data-count-click', 1);
                    this.parentElement.querySelector('.js-next-arrow').classList.add('is-active');
                    this.classList.remove('is-active');
                    nextClick = 0;
                    prevClick = 0;
                // }

            }
        });
    });

    nextArrow.forEach(next => {
        nextClick = 0;
        next.addEventListener('click', function(){

            if (nextClick != 1) {
                nextClick += 1;
                this.setAttribute('data-count-click', nextClick);

                const nextStartDate = new Date(endDate);
                nextStartDate.setDate(nextStartDate.getDate() + 2);
    
                const nextEndDate = new Date(nextStartDate);
                nextEndDate.setDate(nextEndDate.getDate() + 5);
    
                startDate = formatDate(nextStartDate);
                endDate = formatDate(nextEndDate);
    
                populateMenu(mealPlanId, startDate, endDate);

                if (nextClick == 1) {
                    this.parentElement.querySelector('.js-prev-arrow').setAttribute('data-count-click', 1);
                    this.parentElement.querySelector('.js-prev-arrow').classList.add('is-active');
                    this.classList.remove('is-active');
                    prevClick = 1;
                }

            } else {
                event.stopPropagation();
            }
            
        });
    });

    // Calories Click
    if (document.querySelector('page-template-page-menu-description')) {

        document.getElementById('tab1').addEventListener('click', function(){

        });

        document.getElementById('tab2').addEventListener('click', function(){

        });

        document.getElementById('tab3').addEventListener('click', function(){

        });

        document.getElementById('tab4').addEventListener('click', function(){

        });
    }

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
            let urlOrigin = window.location.origin;
            let tradeDescription = "";
            let tradeName = "";
            
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
                    
                    tradeDescription = value.tradeDescription;
                    tradeName = value.tradeName;

                    cardList += '<a href="'+urlOrigin+'/menu-description/?date='+title+'&mealId='+mealPlanId+'&startDate='+startDate+'&endDate='+endDate+'&type='+key+'" class="c-card-menu">';
                    cardList += '<div class="c-card-menu__image">';
                    cardList += '<picture>';
                    cardList += '<img src="'+value.image+'" alt="">';
                    cardList += '</picture>';
                    cardList += (value.tag != "") ? '<span class="best-seller">'+value.tag+'</span>' : "";

                    cardList += '<ul class="star-rating">';

                    for (let i = 0; i < value.rating; i++) {
                        cardList += '<li class="star-full"></li>';
                    }

                    if(value.rating < 5 && value.rating > 1){
                        cardList += '<li class="star-half"></li>';
                    }

                    cardList += '</ul>';

                    cardList += '</div>';
                    cardList += '<span class="category">'+key+'</span>';
                    cardList += '<h4>'+value.name+'</h4>';
                    cardList += '<span class="description">'+value.shortDescription+'</span>';
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
            
            document.getElementById("tradeName").innerHTML = tradeName;
            document.getElementById("tradeDescription").innerHTML = tradeDescription;
            document.getElementById("menuList").innerHTML = list;
            
        }).catch(error => {
            document.getElementById("menuList").innerHTML = '<div class="no-result"><img src="https://' + window.location.host + '/wp-content/themes/wecreate/resources/assets/images/no-result.jpg" /></div>';
        });;

    }

    function startDate(){

        var nextWeekStart = dateInitial.getDate() - dateInitial.getDay() + 8;
        var nextWeekFrom = new Date(dateInitial.setDate(nextWeekStart));
       
        const dateObjFormat = formatDate(nextWeekFrom);
        return dateObjFormat;

    }

    function endtDate(){

        var nextWeekEnd = dateInitial.getDate() - dateInitial.getDay() + 6;
        var nextWeekTo = new Date(dateInitial.setDate(nextWeekEnd));
        const dateObjFormat = formatDate(nextWeekTo);
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