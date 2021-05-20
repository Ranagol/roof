<template>
	<div
		class="d-flex flex-row transparent-background justify-content-around align-items-center"
	>
		<a
			v-for="(label, adType) in tabs"
			:key="adType"
			:href="getHref(adType)"
			class="mt-2"
		>
			<p :class="showSelectedClass(adType) ? 'selected' : 'not-selected' ">{{ label }}</p>
		</a>
	</div>
</template>

<script>
export default {
	name: 'AdListAdTypeFilter',
	props: {
		savedFilterId: {
			required: false,
			type: String,
			default () {
				return '';
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
			tabs: {
				'non-processed': 'Pending',
				'starred': 'Starred',
				'dismissed': 'Dismissed'
			}
		};
	},
	methods: {
		showSelectedClass (adType) {
			return adType === this.adTypeForUrl;
		},
		getHref (adType) {
			return `/ad-list/${adType}/${this.savedFilterId ? this.savedFilterId : ''}`;
		}
	}
};
</script>

<style scoped>
.selected {
	color: white;
	font-weight: 900;
	font-size: large;
	padding-bottom: 10px;
}

.selected:hover {
	text-decoration: underline;
}

.not-selected {
	color: grey;
	font-size: large;
	padding-bottom: 10px;
}

.not-selected:hover {
	text-decoration: underline;
}

.transparent-background {
	background-color: rgba(255, 255, 255, 0.1);
}

</style>
