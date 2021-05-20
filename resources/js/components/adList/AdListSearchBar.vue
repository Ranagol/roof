<template>
	<div>
		<div class="sidebar mb-0">
			<div class="sidebar-widget">
				<h3 class="widget-title">
					Search
				</h3>
				<div class="stroke-shape mb-4" />

				<div class="mt-2">
					Location
				</div>
				<div class="d-flex flex-row ad-list-page">

					<!--					CITY -->
					<auto-complete
						v-model="savedFilter.city"
						:name="autocomplete.cityName"
						:placeholder="autocomplete.cityPlaceholder"
					/>

					<!--					LOCATION -->
					<auto-complete
						v-model="savedFilter.location_in_city"
						:name="autocomplete.locationName"
						:placeholder="autocomplete.locationPlaceholder"
					/>
				</div>

				<div class="mt-2">
					Number of rooms
				</div>
				<div class="d-flex flex-row">

					<!--					MIN ROOMS -->
					<input
						id="minRooms"
						v-model="savedFilter.min_rooms"
						type="text"
						class="form-control ad-list-grey-text"
						placeholder="Min."
						name="min_rooms"
						@change="emitInput"
						@input="validateForPositiveNumbers"
					>

					<!--					MAX ROOMS -->
					<input
						id="maxRooms"
						v-model="savedFilter.max_rooms"
						type="text"
						class="form-control ad-list-grey-text"
						placeholder="Max."
						name="max_rooms"
						@change="emitInput"
						@input="validateForPositiveNumbers"
					>
				</div>

				<div class="mt-2">
					Surface
				</div>
				<div class="d-flex flex-row">

					<!--					MIN SURFACE -->
					<input
						id="minSurface"
						v-model="savedFilter.min_surface"
						type="text"
						class="form-control ad-list-grey-text"
						placeholder="Min."
						name="min_surface"
						@change="emitInput"
						@input="validateForPositiveNumbers"
					>

					<!--					MAX SURFACE -->
					<input
						id="maxSurface"
						v-model="savedFilter.max_surface"
						type="text"
						class="form-control ad-list-grey-text"
						placeholder="Max."
						name="max_surface"
						@change="emitInput"
						@input="validateForPositiveNumbers"
					>
				</div>

				<div class="mt-2">
					Floor
				</div>
				<div class="d-flex flex-row">

					<!--					MIN FLOOR -->
					<input
						id="minFloor"
						v-model="savedFilter.min_floor"
						type="text"
						class="form-control ad-list-grey-text"
						placeholder="Min."
						name="min_floor"
						@change="emitInput"
						@input="validateForPositiveNumbers"
					>

					<!--					MAX FLOOR -->
					<input
						id="maxFloor"
						v-model="savedFilter.max_floor"
						type="text"
						class="form-control ad-list-grey-text"
						placeholder="Max."
						name="max_floor"
						@change="emitInput"
						@input="validateForPositiveNumbers"
					>
				</div>

				<div class="mt-2">
					Price
				</div>
				<div class="d-flex flex-row">

					<!--					MIN PRICE -->
					<input
						id="minPrice"
						v-model="savedFilter.min_price"
						type="text"
						class="form-control ad-list-grey-text"
						placeholder="Min."
						name="min_price"
						@change="emitInput"
						@input="validateForPositiveNumbers"
					>

					<!--					MAX PRICE -->
					<input
						id="maxPrice"
						v-model="savedFilter.max_price"
						type="text"
						class="form-control ad-list-grey-text"
						placeholder="Max."
						name="max_price"
						@change="emitInput"
						@input="validateForPositiveNumbers"
					>
				</div>

				<div class="mt-2">
					Price by surface
				</div>
				<div class="d-flex flex-row">

					<!--					MIN PRICE BY SURFACE -->
					<input
						id="minPriceBySurface"
						v-model="savedFilter.min_price_by_surface"
						type="text"
						class="form-control ad-list-grey-text"
						placeholder="Min."
						name="min_price_by_surface"
						@change="emitInput"
						@input="validateForPositiveNumbers"
					>

					<!--					MAX PRICE BY SURFACE -->
					<input
						id="maxPriceBySurface"
						v-model="savedFilter.max_price_by_surface"
						type="text"
						class="form-control ad-list-grey-text"
						placeholder="Max."
						name="max_price_by_surface"
						@change="emitInput"
						@input="validateForPositiveNumbers"
					>
				</div>

				<!--                    VALIDATION ERROR DISPLAY -->
				<div
					v-if="errors.length > 0"
					class="alert alert-danger"
				>
					<div>Please correct the following error(s):</div>
					<ul>
						<li v-for="error in errors">
							{{ error }}
						</li>
					</ul>
				</div>

				<!--                SEARCH BUTTON -->
				<div class="btn-box">
					<button
						class="theme-btn gradient-btn border-0 w-100 mt-3"
						@click="search"
					>
						Search now
					</button>
				</div>

				<!--				SAVE VUE COMPONENT -->
				<div v-if="isAuthenticated">
					<ad-list-save-filter
						:saved-filter="savedFilter"
					/>

					<!--DELETE BUTTON-->
					<ad-list-delete
						v-if="savedFilterId"
						:saved-filter-id="savedFilterId"
					/>
				</div>
			</div><!-- end sidebar-widget -->
		</div><!-- end sidebar -->
	</div>
</template>

<script>
import AdListSaveFilter from './AdListSaveFilter';
import AdListDelete from './AdListDelete';
import AutoComplete from '../home/AutoComplete';

export default {
	name: 'AdListSearchBar',
	components: {
		AdListSaveFilter,
		AdListDelete,
		AutoComplete
	},
	inject: ['isAuthenticated'],
	props: {
		value: {
			required: false,
			type: Object,
			default () {
				return {};
			}
		},
		savedFilterId: {
			required: false,
			type: String,
			default () {
				return '';
			}
		},
		ads: {
			required: false,
			type: Array,
			default () {
				return [];
			}
		}
	},
	data () {
		return {
			savedFilter: this.value,
			searchNowButtonClicked: false,
			errors: [],
			autocomplete: {
				// for city
				cityName: 'city',
				cityPlaceholder: 'Your city',
				// for  location
				locationName: 'location_in_city',
				locationPlaceholder: 'Location'
			}
		};
	},
	computed: {
		emitableFilters () {
			const searchParamsForEmitting = {};
			_.forEach(this.savedFilter, (filterValue, filterKey) => {
				if (filterValue) {
					searchParamsForEmitting[filterKey] = filterValue;
				}
			});

			return searchParamsForEmitting;
		}
	},
	methods: {
		emitInput () {
			this.$emit('input', this.emitableFilters);
		},

		search () {
			if (this.errors.length === 0) {
				this.$emit('search');
				this.searchNowButtonClicked = true;
			}
		},

		validateForPositiveNumbers (event) {
			this.errors = [];
			if (isNaN(event.target.value) || event.target.value < 0) {
				this.errors.push('The entered value must be a positive number');
			}
		}
	}
};
</script>

<style scoped>
.ad-list-grey-text {
	font-size: 16px;
	color: #808996;
	font-weight: 500;
	line-height: 26px;
}

</style>
