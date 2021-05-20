<template>
	<div>

		<!--        AD LOOPER, LOOPS THE INDIVIDUAL ADS-->
		<div v-if="ads !== undefined && ads.length > 0">
			<ad
				v-for="(ad) in ads"
				:key="ad.id"
				:ad="ad"
				:saved-filter-id="savedFilterId"
				v-on="$listeners"
			/>
		</div>
		<div v-else>
			<p class="ml-2 mb-5">No ads so far.</p>
		</div>
	</div>
</template>

<script>
import Ad from './Ad';

export default {
	name: 'AdListAdLooper',
	components: {
		Ad
	},
	props: {
		ads: {
			required: false,
			type: Array,
			default () {
				return [];
			}
		},
		savedFilterId: {
			required: false,
			type: String,
			default () {
				return '';
			}
		}
	}
};
</script>
