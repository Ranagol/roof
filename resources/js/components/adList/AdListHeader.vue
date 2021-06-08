<template>
    <div>
        <section
            class="mb-0 pb-0 ad-list-header-background"
            style="height: 300px;"
            :style="{'background-image': 'url(/images/adList/adListHeaderImage.png)', 'background-repeat': 'no-repeat', 'background-size': 'cover'}"
        >
            <div class="container mb-0 pb-0">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
                            <div class="section-heading">
                                <h2 class="sec__title text-white font-size-40 mb-0 mt-5 pt-5 header-width">
                                    <span v-if="savedFilter !== null">{{ savedFilter.name }}</span>
                                </h2>
                            </div>
                        </div><!-- end breadcrumb-content -->
                    </div><!-- end col-lg-12 -->

<!--           AD TYPE FILTERS ON THE TOP (STARRED, DISMISSED...)-->
					<ad-list-ad-type-filter
						v-if="isAuthenticated"
						class="mt-3"
						:saved-filter-id="savedFilterId"
						:ad-type-for-url="adTypeForUrl"
					/>

                </div><!-- end row -->
            </div><!-- end container -->
        </section><!-- end breadcrumb-area -->
    </div>
</template>

<script>
import AdListAdTypeFilter from './AdListAdTypeFilter';
export default {
	name: 'AdListHeader',
	components: {
		AdListAdTypeFilter
	},
	inject: ['isAuthenticated'],
	props: {
		savedFilterId: {
			required: false,
			type: String,
			default () {
				return 0;
			}
		},
		savedFilter: {
			required: false,
			type: [Object, Array],
			default () {
				return {};
			}
		},
		adTypeForUrl: {
			type: String,
			required: false,
			default () {
				return '';
			}
		}
	},
	data () {
		return {
			imageNumber: 1
		};
	},
};
</script>

<style scoped>
	.header-width {
		width: 400px;
	}
</style>
