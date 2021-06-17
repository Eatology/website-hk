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