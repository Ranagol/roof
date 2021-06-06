<template>
    <div>
        
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
		}
	},

}
</script>

<style>

</style>