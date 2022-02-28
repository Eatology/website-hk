// DO NOT REMOVE - NEED FOR HOT FIX
if (module.hot) {
	module.hot.accept();
}
// DO NOT REMOVE - NEED FOR HOT FIX
// ADD YOUR CODE BELOW


import Rellax from 'rellax'
import wordpressSearch from './lib/wordpressSearch'
import mealSlider from './lib/mealSlider'
import customersSlider from './lib/customersSlider'
import corporateSlider from './lib/corporateSlider'
import partnersSlider from './lib/partnersSlider'
import faqAccordion from './lib/faqAccordion'
import myAccount from './lib/myAccount'
import benefitsSlider from './lib/benefitsSlider'
import products from './lib/products'
import mainMenu from './lib/menu'
import myAccountCalendar from './lib/calendar'
import myAccountSubscription from './lib/subscriptions'
import myAccountRatings from './lib/ratings'
import menuPage from './lib/menu-page'
import menuDescriptionPage from './lib/menu-description-page'
import sortFilter from './lib/sort-filter'


//header
wordpressSearch()
mainMenu()

//home
var rellax = document.querySelector('.rellax');
if (rellax) {
	var rellax = new Rellax('.rellax');
}

mealSlider()
customersSlider()

//about us
partnersSlider()

//corporate menu
corporateSlider()

//FAQ
faqAccordion()

// My account
myAccount()

// shop
benefitsSlider()

// products
products()

// My account calendar
myAccountCalendar()
myAccountRatings()

// Subscriptions pages in my account
myAccountSubscription()

//Menu Page
menuPage()

//Menu Description Page
menuDescriptionPage()

//Sort and Filter for Wellness Boutique Page
sortFilter()