<template>
	<div>
		<div class="d-flex justify-content-center">
			<ad
				:ad="ad"
				style="width: 60%"
			/>
		</div>

		<div class="d-flex justify-content-center">
			<hr>
			<ad-detail-duplicates
				:duplicates="duplicatesLocal"
				style="width: 60%"
				@isDuplicate="isDuplicate"
				@notDuplicate="notDuplicate"
			/>
		</div>
	</div>
</template>

<script>
import AdDetailDuplicates from './AdDetailDuplicates';
import Ad from '../adList/Ad';

export default {
	name: 'AdDetailBase',
	components: {
		AdDetailDuplicates,
		Ad
	},
	props: {
		ad: {
			type: Object,
			required: false,
			default () {
				return {};
			}
		},
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
			duplicatesLocal: this.duplicates
		};
	},
	methods: {
		async isDuplicate (duplicateAdFromButton) {

			// put the duplicate ad into the dismissed ads on the backend
			await window.axios.post(
				'/set-dismissed',
				{
					adId: duplicateAdFromButton.id,
					dismissed: 1
				}
			);

			// remove the duplicate ad from the possible duplicates on the frontend
			this.duplicatesLocal = this.duplicatesLocal.filter((duplicateAd) => duplicateAd.id !== duplicateAdFromButton.id);
		},

		async notDuplicate (adFromButton) {

			// remember this ad as a NOT duplicate ad in the duplicates table
			await window.axios.post(
				'/not-duplicate',
				{
					ad_id_1: this.ad.id,
					ad_id_2: adFromButton.id
				}
			);

			// remove this NOT duplicate ad from the possible duplicate on the frontend
			this.duplicatesLocal = this.duplicatesLocal.filter((duplicateAd) => duplicateAd.id !== adFromButton.id);
		}
	}
};
</script>


