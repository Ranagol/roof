<template>
	<div>

		<!--        HEADER-->
		<ad-list-header
			:saved-filter-id="savedFilterId"
			:saved-filter="savedFilter"
			:ad-type-for-url="adTypeForUrl"
		/>

		<section class="card-area section-padding">
			<div class="container">
				<div class="row">

					<!--                    AD SORTER-->
					<ad-list-sort-ads
						v-model="sortBy"
						:saved-filter-id="savedFilterId"
						:ad-type-for-url="adTypeForUrl"
						:pagination-data="paginationData"
						@change="search(true)"
					/>

					<div class="row">
						<div class="col-lg-8">

							<!--                        RIGHT SIDE: AD LOOPING AND DISPLAYING-->
							<ad-list-ad-looper
								:ads="adsLocal.data"
								:saved-filter-id="savedFilterId"
								foo="bar"
								@ad-starred="adStarred"
								@dismiss="dismiss"
							/>

							<!--                        PAGINATION -->
							<ad-list-pagination
								v-model="paginationData.current_page"
								:total="paginationData.total"
								:last-page="paginationData.last_page"
								@current-change="search(false)"
							/>

						</div>
						<div class="col-lg-4">

							<!--                    LEFT SIDE: SEARCH TERM FILTER-->
							<ad-list-search-bar
								v-model="savedFilterLocal"
								:saved-filter-id="savedFilterId"
								@search="search(true)"
							/>

						</div>
					</div><!-- end row -->
				</div><!-- end row -->
			</div><!-- end container -->
		</section><!-- end card-area -->
	</div>
</template>

<script>
import AdListHeader from './AdListHeader';
import AdListSortAds from './AdListSortAds';
import AdListAdLooper from './AdListAdLooper';
import AdListSearchBar from './AdListSearchBar';
import AdListPagination from './AdListPagination';
import Ad from '../../models/Ad.js';
import _ from 'lodash';

export default {
	name: 'AdListBase',
	components: {
		AdListAdLooper,
		AdListHeader,
		AdListSortAds,
		AdListSearchBar,
		AdListPagination
	},
	props: {
		savedFilterId: {
			required: false,
			type: String,
			default () {
				return '';
			}
		},
		adTypeForUrl: {
			type: String,
			required: false,
			default () {
				return '';
			}
		},
		ads: {
			required: false,
			type: Object,
			default () {
				return {};
			}
		},
		savedFilter: {
			required: false,
			type: [Object, Array],
			default () {
				return {};
			}
		}
	},
	data () {
		return {
			adsLocal: this.ads,
			savedFilterLocal: this.getDefaultSavedFilters(),
			sortBy: '',
			paginationData: this.getPaginationData()
		};
	},
	computed: {
		currentUrl () {

			// 0. CREATE BASE URL
			let url = `/ad-list/${this.adTypeForUrl}?`;

			// 1. ADD SEARCH PARAMETERS TO URL
			const temporaryObject = this.savedFilterLocal;
			delete temporaryObject.name;
			_.forEach(temporaryObject, (filterValue, filterKey) => {
				if (_.isEmpty(filterValue) === false) {
					url += `${filterKey}=${filterValue}&`;
				}
			});

			// 2. ADD SORTING PARAMETERS TO URL
			if (this.sortBy) {
				url += `sortByThis=${this.sortBy}&`;
			}

			// 3. ADD CURRENT PAGE PAGINATION DATA TO THE URL
			url += `page=${this.paginationData.current_page}`;

			return url;
		}
	},

	watch: {
		ads: {
			deep: true,
			handler (value) {
				this.adsLocal = value;
			}
		}
	},

	methods: {
		getDefaultSavedFilters () {
			if (Array.isArray(this.savedFilter) === true) {
				return {};
			}

			return this.savedFilter;
		},

		getPaginationData () {
			const {
				data,
				...pagination
			} = this.adsLocal || this.ads;

			return pagination;
		},

		// This search() can be triggered by many events:
		// 1-the user clicks on the search button (resetPagination = true)
		// 2-the user is on page 1 on the pagination, and clicks page 2 on the pagination (resetPagination = false)
		// 3-the user clicks on starred or dismissed buttons (resetPagination = true)
		// 4-sort  (resetPagination = true)
		async search (resetPagination = true) {
			let page = this.paginationData.current_page;
			if (resetPagination === true) {
				page = 1;
			}

			const response = await Ad.getAds(this.adTypeForUrl, this.savedFilterLocal, this.sortBy, page);

			this.adsLocal = {
				...this.adsLocal,
				...response.data
			};

			this.paginationData = {...this.getPaginationData()};

			this.updateUrl();
		},

		updateUrl () {
			window.history.pushState(
				{},
				'',
				this.currentUrl
			);
		},

		async adStarred (ad) {
			await window.axios.post(
				'/set-dismissed',
				{
					adId: ad.id,
					dismissed: 0
				}
			);

			await this.search(true);
		},

		async dismiss (ad) {
			await window.axios.post(
				'/set-dismissed',
				{
					adId: ad.id,
					dismissed: 1
				}
			);

			await this.search(true);
		}
	}
};
</script>

<style scoped>

</style>
