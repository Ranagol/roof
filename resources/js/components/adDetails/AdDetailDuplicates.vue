<template>
	<div>
		<hr>
		<h2
			v-if="duplicates !== undefined && duplicates.length > 0"
			class="sec__title"
		>
			Possible duplicates
		</h2>
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
