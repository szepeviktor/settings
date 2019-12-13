<?php
declare(strict_types=1);

use ItalyStrap\HTML\Attributes as ATTR;

return [
	[
		ATTR::ID		=> 'test_mode',
		ATTR::TITLE	=> __( 'Test Mode:', 'italystrap' ),
//		'callback'	=> 'get_field_type',
		// 'page'		=> 'italystrap_options_group',
		// 'section'	=> 'content', // Optional
		'args'		=> [
			'name'			=> __( 'Test Mode:', 'italystrap' ),
			'desc'			=> __( 'If test mode is active the front-end form on submit will return an array with som edefault values.', 'italystrap' ),
			ATTR::ID			=> 'test_mode',
			ATTR::TYPE			=> 'checkbox',
			'value'			=> true,
			'sanitize'		=> 'sanitize_text_field',
		],
	],
	[
		'id'		=> 'max_user_requests',
		'title'		=> __( 'Max user requests:', 'italystrap' ),
//		'callback'	=> 'get_field_type',
		// 'page'		=> 'italystrap_options_group',
		// 'section'	=> 'content',
		'args'		=> [
			'name'			=> __( 'Max user requests:', 'italystrap' ),
			'desc'			=> __( 'Global value for max requests per user.', 'italystrap' ),
			'id'			=> 'max_user_requests',
			'type'			=> 'number',
			'value'			=> 5,
			'sanitize'		=> 'sanitize_text_field',
		],
	],
	[
		'id'		=> 'concurrent_requests_limit',
		'title'		=> __( 'Concurrent request limit:', 'italystrap' ),
//		'callback'	=> 'get_field_type',
		// 'page'		=> 'italystrap_options_group',
		// 'section'	=> 'content',
		'args'		=> [
			'name'			=> __( 'Concurrent request limit:', 'italystrap' ),
			'desc'			=> __( 'How many concurrent requests may be done on ajax submit.', 'italystrap' ),
			'id'			=> 'concurrent_requests_limit',
			'type'			=> 'number',
			'value'			=> 2,
			'sanitize'		=> 'sanitize_text_field',
		],
	],
	[
		'id'		=> 'concurrent_requests_expiration_time',
		'title'		=> __( 'Concurrent request expiration time:', 'italystrap' ),
//		'callback'	=> 'get_field_type',
		// 'page'		=> 'italystrap_options_group',
		// 'section'	=> 'content',
		'args'		=> [
			'name'			=> __( 'Concurrent request expiration time:', 'italystrap' ),
			'desc'			=> __( 'Expiration time for the concurrent request.', 'italystrap' ),
			'id'			=> 'concurrent_requests_expiration_time',
			'type'			=> 'number',
			'value'			=> 5,
			'sanitize'		=> 'sanitize_text_field',
		],
	],
	[
		'id'		=> 'username',
		'title'		=> __( 'User Name:', 'italystrap' ),
//		'callback'	=> 'get_field_type',
		// 'page'		=> 'italystrap_options_group',
		// 'section'	=> 'content',
		'args'		=> [
			'name'			=> __( 'User Name:', 'italystrap' ),
//			'desc'			=> __( '.', 'italystrap' ),
			'id'			=> 'username',
			'type'			=> 'email',
//			'class'			=> 'easy',
//			'default'		=> '',
			'validate'		=> 'is_email',
			'sanitize'		=> 'sanitize_text_field',
			// 'option_type'	=> 'theme_mod',
		],
	],
	[
		'id'		=> 'api_key',
		'title'		=> __( 'API key:', 'italystrap' ),
//		'callback'	=> 'get_field_type',
		// 'page'		=> 'italystrap_options_group',
		// 'section'	=> 'content',
		'args'		=> [
			'name'			=> __( 'API key:', 'italystrap' ),
//			'desc'			=> __( '.', 'italystrap' ),
			'id'			=> 'api_key',
			'type'			=> 'text',
//			'class'			=> 'easy',
//			'default'		=> '',
			// 'validate'		=> 'ctype_alpha',
			'sanitize'		=> 'sanitize_text_field',
			// 'option_type'	=> 'theme_mod',
		],
	],
];
