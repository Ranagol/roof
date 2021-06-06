<template>
	<div class="mt-5">

		<!--                           AD DISPLAYING -->
		<div class="row">
			<div class="col-lg-12">
				<div class="card-item card-item-list">

					<!--                           AD IMAGE-->
					<div class="card-image">
						<img
							:src="ad.image_link"
							data-src="images/img4.jpg"
							class="card__img lazy"
							alt="ad_image"
						>
					</div>
					<div
						id="ad-padding"
						class="card-content"
					>

						<!--                               AD DESCRIPTION -->
						<img
							:src="displayLogo(ad.ad_source)"
							alt="image"
						>

						<!--                        TITLE -->
						
							<h4 class="card-title">
								<!-- note the "``" combination. this is a must.-->
								<el-tooltip class="item" effect="light" content="See ad details." placement="right">
									<a :href="`/ads/${ad.id}`">
										Flat in {{ ad.city | capitalize }},
										for {{ ad.price | formatNumber }} &euro;
									</a>
								</el-tooltip>
							</h4>
						

						<!--                        PRICES -->
						<p class="card-sub">
							Price: {{ ad.price | formatNumber }} &euro;
						</p>
						<p class="card-sub">
							Price by surface: {{ ad.price_by_surface | formatNumber }} &euro;/m <sup>2</sup>
						</p>

						<!--                        CITY AND LOCATION -->
						<p class="card-sub">
							<i class="fas fa-map-marker-alt" />
							{{ ad.city | capitalize  }}, {{ ad.location_in_city | capitalize  }}
						</p>

						<!--                        ROOMS, FLOOR, SURFACE -->
						<ul class="listing-meta d-flex align-items-center">
							<li class="d-flex align-items-center">
								<span class="rate-text">Rooms: {{ ad.number_of_rooms }}</span>
							</li>
							<li>
                        <span
							class="price-range"
							data-placement="top"
							title="Pricey"
						>
                            <div class="font-weight-medium">Floor: {{ ad.floor }}</div>
                        </span>
							</li>
							<li class="d-flex align-items-center">
								<div class="listing-cat-link">
									Surface: {{ ad.surface }} m <sup>2</sup>
								</div>
							</li>
						</ul>

						<!--                        DATE, AD SOURCE AND ADVERTISER -->
						<ul class="info-list">
							<li><i class="far fa-calendar-check" /> {{ ad.ad_date | formatDate }},
								{{ ad.ad_date | timePassedSince }}
							</li>
							<li>
								<i class="fas fa-link" />
								<a
									class="here"
									:href="ad.ad_link"
								>{{ ad.ad_source }}</a>
							</li>
							<li>Advertiser: {{ ad.advertiser }}</li>
						</ul>

						<!--                        POSSIBLE DUPLICATES -->
						<!-- <ul class="info-list" >
							<li>
								<a :href="`/ads/${ad.id}`">
									<strong class="duplicate-color">This ad has possible duplicates.</strong>
								</a>
							</li>
						</ul> -->

						<!--                        STAR AND DISMISS BUTTONS -->
						<div v-loading="loading">
							<div
								v-if="!showDuplicateButtons && isAuthenticated && !isDetailsPage"
								class="d-flex flex-row pt-2 justify-content-around"
							>
								<star-button
									@clicked="starredAd"
								/>
								<dismiss-button
									@dismiss="dismiss"
								/>
							</div>
						</div>
						

						<!--                        DUPLICATE/NOT DUPLICATE BUTTONS -->
						<div
							v-if="showDuplicateButtons"
							class="d-flex flex-row pt-2 justify-content-around"
						>
							<duplicate-button
								:ad="ad"
								v-on="$listeners"
							/>
							<not-duplicate-button
								:ad="ad"
								v-on="$listeners"
							/>
						</div>
					</div>
				</div><!-- end card-item -->
			</div><!-- end col-lg-12 -->
		</div><!-- end row -->
	</div>
</template>

<script>
import StarButton from '../buttons/StarButton';
import DismissButton from '../buttons/DismissButton';
import DuplicateButton from '../buttons/DuplicateButton';
import NotDuplicateButton from '../buttons/NotDuplicateButton';
import moment from 'moment';
import _ from 'lodash';
import Ad from '../../models/Ad.js';
export default {
	name: 'Ad',
	components: {
		StarButton,
		DismissButton,
		DuplicateButton,
		NotDuplicateButton
	},
	filters: {
		// we want instead of 93730 EUR to have 93.730,00 EUR. For this we use this filter.
		formatNumber (value) {
			return value.toLocaleString('de-DE', {minimumFractionDigits: 2});
		},
		// here we want to have '2 day ago displayed' instead of 02.06.2021
		timePassedSince (value) {
			return moment(value, 'YYYYMMDD').fromNow();
		},
		// here we want to format date from 22-03-2021 to 22.apr.2012
		formatDate (value) {
			return moment(value).format('Do MMM YYYY');
		},
		capitalize (value) {
			return _.startCase(value);
		}
	},
	inject: ['isAuthenticated'],
	props: {
		ad: {
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
		showDuplicateButtons: {
			required: false,
			type: Boolean,
			default () {
				return false;
			}
		}
	},
	data () {
		return {
			oglasiLogoPath: '/images/logos/oglasi-logo.png',
			haloOglasiLogoPath: '/images/logos/halooglasi-logo.png',
			hasDuplicates: false,
			loading: false,
		};
	},
	computed: {
		isDetailsPage () {
			return window.location.pathname.slice(1, 4) === 'ads';
		}
	},
	methods: {
		starredAd () {
			this.loading = true;
			this.$emit('ad-starred', this.ad);
			this.loading = false;
		},
		dismiss () {
			this.loading = true;
			this.$emit('dismiss', this.ad);
			this.loading = false;
		},
		displayLogo (adSource) {
			if (adSource === 'www.halooglasi.rs') {
				return this.haloOglasiLogoPath;
			}

			return this.oglasiLogoPath;
		},
		async checkForDuplicates() {
			try {
				const {data} = await Ad.getPossibleDuplicates(this.ad.id);
				console.log('checkForDuplicates() executed, these are the duplicates: ', data);
			} catch (error) {
				console.dir(error);
			}
		}
	},
	mounted() {
		// this.checkForDuplicates();
	},
};

</script>

<style scoped>
.here {
	color: blue;
	text-decoration: underline;
}

#ad-padding {
	padding: 5px 20px 5px 20px;
}

.duplicate-color {
	color: red;
}

.duplicate-color:hover {
	color: red;
	text-decoration: underline;
}
</style>
