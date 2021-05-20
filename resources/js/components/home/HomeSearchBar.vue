<template>
	<section class="hero-wrapper overflow-hidden bread-bg">
		<div class="overlay" />
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="hero-heading text-center">
						<div class="section-heading">
							<h2 class="sec__title">
								Want to have your own roof above your head?
							</h2>
							<p class="sec__desc">
								Find your dream flat. Start here.
							</p>
						</div>
					</div><!-- end hero-heading -->
					<div class="main-search-input">

						<!--  UI ELEMENT AUTO-COMPLETE INPUT FOR CITY  -->
						<div class="main-search-input-item">
							<div class="form-box">
								<div class="form-group mb-0 home-page">
									<auto-complete
										v-model="filters.city"
										:name="autocomplete.name"
										:placeholder="autocomplete.placeholder"
									/>
								</div>
							</div>
						</div>

						<!--                        Max price  -->
						<div class="main-search-input-item">
							<div class="form-box">
								<div class="form-group mb-0">
									<input
										id="max_price"
										v-model="filters.max_price"
										name="max_price"
										class="form-control"
										type="search"
										placeholder="Upper spending limit"
										@input="validateForPositiveNumbers"
									>
								</div>
							</div>
						</div>

						<!--                        Max rooms  -->
						<div class="main-search-input-item">
							<div class="form-box">
								<div class="form-group mb-0">
									<input
										id="max_rooms"
										v-model="filters.max_rooms"
										name="max_rooms"
										class="form-control"
										type="search"
										placeholder="Max number of rooms"
										@input="validateForPositiveNumbers"
									>
								</div>
							</div>
						</div>

						<!--                        Button -->
						<div class="main-search-input-item">
							<button
								class="theme-btn gradient-btn border-0 w-100"
								@click="search"
							>
								Search Now
							</button>
						</div>
					</div>
					<div class="highlighted-categories"></div>

					<!--                    VALIDATION ERROR DISPLAYING PART -->
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

				</div><!-- end col-lg-12 -->
			</div><!-- end row -->
		</div><!-- end container -->
		<div class="svg-bg">
			<svg
				xmlns="http://www.w3.org/2000/svg"
				viewBox="0 0 1000 100"
				preserveAspectRatio="none"
			>
				<path
					fill="#F5F7FC"
					class="elementor-shape-fill"
					d="M500,97C126.7,96.3,0.8,19.8,0,0v100l1000,0V1C1000,19.4,873.3,97.8,500,97z"
				/>
			</svg>
		</div>
	</section>
</template>

<script>
import AutoComplete from './AutoComplete';
export default {
	name: 'HomeSearchBar',
	components: {
		AutoComplete
	},
	data () {
		return {
			filters: {
				city: null,
				max_price: null,
				max_rooms: null
			},
			errors: [],
			autocomplete: {
				name: 'city',
				placeholder: 'Your city'
			}
		};
	},
	methods: {
		search () {
			if (this.errors.length === 0) {
				this.$emit('search', this.filters);
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
.myDistanceBetween {
	width: 4%;
}

</style>
