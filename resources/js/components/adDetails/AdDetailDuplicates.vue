<template>
	<div>
		<hr>
		<div v-if="duplicates !== undefined && duplicates.length > 0">
			<h2 class="sec__title">Possible duplicates</h2>
			<p>It seems that there are some possbile ad duplicates for this ad on the top. 
				Please confirm if these ads below are 
				duplicates with the ad on the top. If so, click on the 'Duplicate' button, and the now confirmed
				duplicate ad will not be displayed ever again. If this is just a similar, but different ad, please 
				click on the 'Not duplicate' button. This way the app will not display it again as a possible
				duplicate.
			</p>
		</div>
		
		<div v-if="duplicates !== undefined && duplicates.length > 0">
			<ad
				v-for="(ad) in duplicates"
				:key="ad.id"
				:ad="ad"
				:show-duplicate-buttons="showDuplicateButtons"
				v-on="$listeners"
			/>
		</div>
		<div v-else>
			<p class="mb-5">No duplicates so far.</p>
		</div>
	</div>
</template>

<script>
import Ad from '../adList/Ad';
export default {
	name: 'AdDetailDuplicates',
	components: {
		Ad
	},
	props: {
		duplicates: {
			type: Array,
			required: false,
			default () {
				return [];
			}
		}
	},
	data () {
		return {
			showDuplicateButtons: true,
			duplicatesLocal: this.duplicates
		};
	}

};
</script>

<style scoped>

</style>
