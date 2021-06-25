import { Calendar } from '@fullcalendar/core'
import interactionPlugin, { Draggable } from '@fullcalendar/interaction'
import dayGridPlugin from '@fullcalendar/daygrid'

//home page slider
const myAccountCalendar = () => {
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar')

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
                
        const today = new Date()
        const ninetyDays = today.addDays(90)
        const tomorrow = today.addDays(1)
        const calendarActionWrapper = document.getElementById('calendar-action-wrapper') 
        const calendarAction = document.getElementById('calendar-action') 
        const closeButton = document.getElementById('calendar-action-close') 
        const calendarH2 = document.getElementById('calendar-h2') 
        const calendarIntro = document.getElementById('calendar-intro') 
        const calendarPostponeSlot = document.getElementById('calendar-postpone-slot') 
        const confirmPostpone = document.getElementById('calendar-confirm-postpone') 
        
        if (closeButton) {
            closeButton.addEventListener("click", closeOverlay)
        }
        

        function closeOverlay() {
            calendarActionWrapper.classList.remove("calendar-wrapper-active")
            calendarAction.classList.remove("calendar-action-active")
            if(calendarPostponeSlot.classList.contains("calendar-initial-active")) {
                calendarPostponeSlot.classList.remove("calendar-initial-active")
            }

        }

        
        //const todayEvent = today.toISOString().slice(0,10);
        
        console.log(today)
        console.log(ninetyDays)

        if (calendarEl) {
            var calendar = new Calendar(calendarEl, {
                plugins: [ interactionPlugin, dayGridPlugin ],
                droppable: true,
                firstDay: 1,
                selectable: true,
                editable: true,
                droppable: true,
                select: function(info) {
                    console.log('selected ' + info.startStr + ' to ' + info.endStr)
                    let newEndDate = new Date(info.endStr).minusDays(1)
                    calendarH2.innerHTML = 'Set Delivery Dates'
                    calendarIntro.innerHTML = "You have selected " + info.startStr + ' to ' + newEndDate
                    if(!calendarActionWrapper.classList.contains("calendar-active")) {
                        calendarActionWrapper.classList.add("calendar-wrapper-active")
                        calendarAction.classList.add("calendar-action-active")
                    }

                },                     
                validRange: {
                    start: today,
                    end: ninetyDays
                },  
                headerToolbar: {
                    center: 'prev, title, next',
                    start: '',
                    end: 'today' 
                },
                selectOverlap: function(event) {
                    return event.rendering === 'background';
                },                                  
                events: [
                    {
                      title: 'Deliver to work \n \n 9:00 - 9:30',
                      start: '2020-09-25T09:00',
                      end: '2020-09-25T10:00',
                    },
                    {
                        title: 'Deliver to work \n \n 9:00 - 9:30',
                        start: '2020-09-26T09:00',
                        end: '2020-09-26T10:00',
                    },                    
                    {
                        title: 'Deliver to work \n \n 9:00 - 9:30',
                        start: '2020-09-29T09:00',
                        end: '2020-09-29T10:00',
                    },    
                    {
                        title: 'Deliver to work \n \n 9:00 - 9:30',
                        start: '2020-09-30T09:00',
                        end: '2020-09-30T10:00',
                    },  
                    {
                        title: 'Deliver to home \n \n 9:00 - 9:30',
                        start: '2020-10-01T09:00',
                        end: '2020-09-01T10:00',
                    },       
                    {
                        title: 'Deliver to home \n \n 9:00 - 9:30',
                        start: '2020-10-02T09:00',
                        end: '2020-09-02T10:00',
                    },                                                                                
                    {
                        start: '2020-09-28T09:00',
                        end: '2020-09-28T10:00',
                        display: 'background',
                        allDay: true,
                    }, 
                    {
                        start: today,
                        end: today,
                        display: 'background',
                        allDay: true,
                    },     
                    {
                        start: tomorrow,
                        end: tomorrow,
                        display: 'background',
                        allDay: true,
                    },                                          
                    {
                        daysOfWeek: [ '0' ],
                        display: 'background',
                        allDay: true,
                        color: '#F9F8F9',
                        displayEventTime: true
                    }                                                            
                ],
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // don't let the browser navigate

                    
                    if (info.event.display !== 'background') {
                        calendarH2.innerHTML = 'Confirm postpone?'
                        calendarIntro.innerHTML = "You have selected to postpone meal deliveries on" + info.event.start + ". Confirming will add 1 day to the days available."
                        calendarPostponeSlot.classList.add("calendar-initial-active")

                        if(!calendarActionWrapper.classList.contains("calendar-active")) {
                            calendarActionWrapper.classList.add("calendar-wrapper-active")
                            calendarAction.classList.add("calendar-action-active")
                        }
                        console.log(info.event)
                    }
                },
                eventColor: '#F9F5FB',    
                eventBackgroundColor: '#F9F8F9',   
                eventDrop: function(info) {
                    console.log("info: " + info);
                },                        
            });
            
            calendar.render();

        }
      

      });
}
export default myAccountCalendar



