export default class Ad {

	/**
	 * Ide pedig teszunk egy magyarazatot hogy ez mit csinal
	 *
	 * @param adType {string} What type of ads we are getting.
	 * @param filters {Object} These are the filters set by the user.
	 * @param sortByThis
	 * @param page
	 *
	 * @returns {Promise<AxiosResponse<any>>}
	 */
	static getAds (adType, filters, sortByThis, page) {
		return window.axios.get(
			`/ad-list/${adType}`,
			{
				params: {
					...filters, //example: city=Belgrade, minPrice=10000, location=Podbara...
					sortByThis, //example: price.asc
					page//example: 1
				}
			}
		);
	}

	/**
	 * Get the cities or locations searched by the user.
	 * @param type {string} What are we retrieving. City or location.
	 * @param searchString The string that we should search for. Example: n no nov novi...
	 *
	 * @returns {Promise<AxiosResponse<any>>}
	 */
	static getCityOrLocationForAutocomplete (type, searchString) {
		return window.axios.get(
			`/autocomplete/ad/${type}`,
			{
				params: {
					searchString
				}
			}
		);
	}
}

