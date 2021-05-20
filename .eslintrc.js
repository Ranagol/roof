module.exports = {
	globals: {
		_: true,
		axios: true,
		require: true,
		moment: true,
		process: true,
		Vue: true,
		path: true,
		$: true,
		module: true
	},
	extends: [
		'eslint:all',
		'plugin:vue/essential',
		'plugin:vue/recommended',
		'plugin:vue/strongly-recommended'
	],
	rules: {
		'vue/html-indent': 0,
		'vue/max-attributes-per-line': [
			1,
			{
				singleline: 1,
				multiline: {
					max: 1,
					allowFirstLine: false
				}
			}
		],
		'vue/multiline-html-element-content-newline': [1, {allowEmptyLines: true}],
		'max-lines': 'off',
		'no-tabs': 'off',
		'max-len': 'off',
		quotes: [1, 'single', {avoidEscape: true}],
		'max-lines-per-function': 'off',
		'no-mixed-spaces-and-tabs': [1, 'smart-tabs'],
		indent: [1, 'tab', {SwitchCase: 1}],
		'quote-props': [1, 'as-needed'],
		'sort-keys': 'off',
		'padded-blocks': 'off',
		'no-magic-numbers': 'off',
		'no-ternary': 'off',
		'dot-location': 'off',
		'consistent-return': 'off',
		'no-console': 'off',
		'no-use-before-define': 'off',
		'multiline-comment-style': 'off',
		'no-process-env': 'off',
		'max-params': 'off',
		'max-statements': [1, 15],
		'one-var': 'off',
		radix: [1, 'as-needed'],
		'consistent-this': [1, 'env'],
		'function-call-argument-newline': [1, 'consistent'],
		'multiline-ternary': [1, 'always-multiline'],
		'array-element-newline': [1, 'consistent'],
		'no-unused-expressions': [1, {allowTernary: true}],
		'sort-imports': [
			1,
			{
				ignoreCase: true,
				ignoreDeclarationSort: true,
				ignoreMemberSort: false,
				memberSyntaxSortOrder: ['none', 'all', 'multiple', 'single']
			}
		],
		camelcase: [1, {properties: 'never'}],
		'no-underscore-dangle': [1, {allow: ['_d']}],
		'capitalized-comments': 'off',
		'id-length': 'off',
		'line-comment-position': 'off',
		'spaced-comment': 'off',
		'no-inline-comments': 'off',
		'no-unused-vars': [1, {ignoreRestSiblings: true}]
	}
};
