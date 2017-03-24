<?php return [

	[
		'input',
		[
			'type'     => 'text',
			'name'     => 'from_name',
			'class'    => false,
			'label'    => __( 'Name', 'swift-contact-form' ),
			'validate' => 'required|min=6|max=255',
		],
	],
	[
		'input',
		[
			'type'     => 'email',
			'name'     => 'from_email',
			'class'    => false,
			'label'    => __( 'E-mail', 'swift-contact-form' ),
			'validate' => 'required|email',
		],
	],
	[
		'input',
		[
			'type'  => 'text',
			'name'  => 'phone',
			'class' => false,
			'label' => __( 'Phone', 'swift-contact-form' ),
		],
	],
	[
		'input',
		[
			'type'     => 'text',
			'name'     => 'location',
			'class'    => false,
			'label'    => __( 'Location', 'swift-contact-form' ),
			'validate' => 'required',
		],
	],
	[
		'input',
		[
			'type'  => 'text',
			'name'  => 'duration',
			'class' => false,
			'label' => __( 'Expected event duration', 'swift-contact-form' ),
			'validate' => 'required',
		],
	],
	[
		'input',
		[
			'type'  => 'text',
			'name'  => 'subject',
			'class' => false,
			'label' => __( 'Subject', 'swift-contact-form' ),
		],
	],
	[
		'textarea',
		[
			'name'     => 'message',
			'class'    => false,
			'label'    => __( 'Message', 'swift-contact-form' ),
			'validate' => 'required|min=10|max=3000',
		],
	],
	[
		'input',
		[
			'type'    => 'checkbox',
			'name'    => 'sendcopy',
			'label'   => __( 'Send a copy to my e-mail address', 'swift-contact-form' ),
			'value'   => 1,
			'checked' => true,
		],
	],
	[
		'math',
		[
			'name'  => 'answer',
			'class' => false,
			'label' => __( 'Please answer the question!', 'swift-contact-form' ),
		],
	],
	[
		'button',
		[
			'type'     => 'submit',
			'name'     => 'submit',
			'text'     => __( 'Send', 'swift-contact-form' ),
			'disabled' => true,
		],
	],
];