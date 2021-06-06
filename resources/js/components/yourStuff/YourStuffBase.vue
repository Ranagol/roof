<template>
    <div>
        <section class="category-area bg-gray arrow-down-shape position-relative mt-3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="sec__title">Welcome, {{ user.name }}!</h2>
                        <p>You want to find your future home. We can help you with that.</p>
                        <p>On the <a href="/ad-list/non-processed">Ad list</a> page you can search through flat ads 
                            by your criteria. You can save these searches, and if you do so, this app will find new
                            ads for you every day (of course, according to your search criteria.) It will check multiple
                            web sites specialized in real estate sales. All you need to do is to star the ads that you
                            liked (these will be placed into your Starred ads, for your later review), an dismiss the ads that you don't like
                            (these will be placed into the Dismissed ads section).
                        </p>
                        <br>
                        <div class="d-flex flex-row justify-content-between">
                            <div class="flex-col">
                                <h2 class="sec__title">Currently you have:</h2>
                                <p>Saved ad lists: {{ filters.length}}</p>
                                <p>Starred ads: {{ starredAds }} </p> 
                                <p>Dismissed ads: {{ dismissedAds }}</p>
                            </div>
                            <div class="flex-col">
                                <h2 class="sec__title">Our real estate ad sources:</h2>
                                <ul>
                                    <li><a href="https://www.halooglasi.com/nekretnine/prodaja-stanova">www.halooglasi.com</a></li>
                                    <li><a href="https://www.oglasi.rs/oglasi/nekretnine/prodaja/stanova">www.oglasi.rs</a></li>
                                </ul>
                            </div>
                        </div>
                    </div><!-- end col-lg-12 -->
                </div><!-- end row -->
            </div><!-- end container -->
        </section>

        <!--        AD LIST LOOPER-->
		<home-ad-list-looper
			:filters="filters"
			:filter-count="filterCount"
		/>
    </div>
</template>

<script>
import HomeAdListLooper from '../home/HomeAdListLooper.vue';
export default {
    name: 'YourStuffBase',
    components: {
		HomeAdListLooper
	},
    props: {
		filters: {
			type: Array,
			required: false,
			default () {
				return [];
			}
		},
		filterCount: {
			type: [Object, Array],
			required: false,
			default () {
				return {};
			}
		},
        dismissedAds: {
			type: Number,
			required: false,
			default () {
				return 0;
			}
		},
        starredAds: {
			type: Number,
			required: false,
			default () {
				return 0;
			}
		},
        user: {
			type: Object,
			required: false,
			default () {
				return {};
			}
		},
	},
}
</script>

<style>

</style>