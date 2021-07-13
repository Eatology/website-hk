
//home page slider
const myAccountSubscription = () => {

        const subscriptionActionWrapper = document.getElementById('subscription-action-wrapper') 
        const subscriptionAction = document.getElementById('subscription-action') 
        const cancelURL = document.getElementById('cancel-url')
        const subscriptionCancelButton = document.getElementById('subscription-cancel') 
        const closeButton = document.getElementById('subscription-action-close') 
        const subscriptionConfirmCancelButton = document.getElementById('subscription-confirm-cancel') 
        
        
        if (subscriptionCancelButton) {
            subscriptionCancelButton.addEventListener("click", openOverlay)
        }

        if (closeButton) {
            closeButton.addEventListener("click", closeOverlay)
        }

        if (subscriptionConfirmCancelButton) {
            subscriptionConfirmCancelButton.addEventListener("click", cancelSubscription)
        }
        

        function cancelSubscription() {            
            window.location.href = cancelURL.value ;            
        }


        function openOverlay() {
            subscriptionActionWrapper.classList.add("subscription-wrapper-active")
            subscriptionAction.classList.add("subscription-action-active")
        }
                
        function closeOverlay() {
            subscriptionActionWrapper.classList.remove("subscription-wrapper-active")
            subscriptionAction.classList.remove("subscription-action-active")
        }

        

}
export default myAccountSubscription



