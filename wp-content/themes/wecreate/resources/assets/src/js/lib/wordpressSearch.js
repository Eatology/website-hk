const wordpressSearch = () => {
	const searchIcon = document.querySelector('.icon-icon-search')
	const searchIconMobile = document.querySelector('.search__box-mobile .icon-icon-search')
	const mobileForm = document.querySelector('.search__box-mobile form')
	const closeSearchButton = document.querySelector('.search__fields .icon-icon-close')
	const searchWrapper = document.querySelector('.wrapper .search')
	const searchInput = document.querySelector('.search .search__input')

	searchIcon.addEventListener("click", showSearch)
	closeSearchButton.addEventListener("click", showSearch)

	function showSearch() {
		if (searchWrapper.classList.contains("search-animation")) {
			searchWrapper.classList.remove("search-animation")
			searchInput.blur()

		} else {
			searchWrapper.classList.add("search-animation")
			setTimeout(() => {
				searchInput.focus()
			}, 1000);

		}
	}



	searchIconMobile.addEventListener("click", searchMobile)

	function searchMobile() {

		var search_input = document.forms["mobile_search_form"]["s"].value;

		if (search_input.length == 0) {
			// alert("Enter search keyword");
		} else {
			mobileForm.submit()
		}
	}

}
export default wordpressSearch
