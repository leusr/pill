<?php

class Swift_contact_form {

	/**
	 * Contain data about sender
	 * @var array
	 */
	private $users;

	/**
	 * Registered forms
	 * @var array
	 */
	private $forms = [ 'contact', 'wedding_request' ];

	/**
	 * Slug of current form
	 * @var string
	 */
	private $form_slug;

	/**
	 * WordPress post ID
	 * @var int
	 */
	private $post_id;

	/**
	 * Respond messages
	 * @var array
	 */
	private $messages;

	/**
	 * Fields of the form
	 * @var array
	 */
	private $fields;

    /**
     * Text and html email templates
     * @var string|null
     */
    private $tpl_mail_txt = null;
    private $tpl_copy_txt = null;
    private $tpl_mail_htm = null;
    private $tpl_copy_htm = null;

	/**
	 * User fingerprint generated from $_SERVER
	 * @var string
	 */
	private $fprint;

	/**
	 * @var Form_helper object
	 */
	private $formhelp;

	/**
	 * Microtime now
	 * @var int
	 */
	private $mtime;

	/**
	 * Browser javascript is enabled or disabled
	 * @var bool
	 */
	private $js = false;

	/**
	 * Sanitized and validated $_POST data
	 * @var array
	 */
	private $post = [];

	/**
	 * Primary to email address and name
	 * @var array
	 */
	private $to = [ 'address' => '', 'name' => '' ];

	/**
	 * From email address and name
	 * @var array
	 */
	private $from = [ 'address' => '', 'name' => '' ];

	/**
	 * Reply to email address and name
	 * @var array
	 */
	private $replyto = [ 'address' => '', 'name' => '' ];

	/**
	 * Email subject
	 * @var string
	 */
	private $subject;

    /**
     * Constructor.
     */
    public function __construct() {
		// Load or create users array
		if ( false === ( $this->users = get_transient( 'scf-users' ) ) ) {
			$this->users = [];
		}

        // Set localization
		$this->load_plugin_textdomain();

		// Save start time
        $this->mtime = microtime( true );

        // Create fingerprint
		$this->fprint = $this->create_fingerprint();

        // General switch between post and form view
        if ( isset( $_POST['form_slug'] ) ) {
            $this->form_post();
		} else {
            $this->hello_user();
		}
	}

	/**
	 * Load the plugin text domain for translation
	 */
	public function load_plugin_textdomain() {
		$domain = 'swift-contact-form';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, plugin_dir_path( __FILE__ ) . 'languages/' . $locale . '.mo' );
	}

    /**
     * Set current form data
	 *
     * @param string $form_slug Directory name of formdata
     * @param int    $post_id   WordPress post ID
     */
    public function set_form( $form_slug, $post_id ) {
		// Invalid call, abort without any output
		if ( ! in_array( $form_slug, $this->forms ) ) {
			$this->abort();
		}

        $formdir = dirname( __FILE__ ) . '/forms/' . $form_slug;
		if ( ! is_dir( $formdir ) ) {
			$this->abort( $form_slug . ' setup error. <strong>' . $form_slug . '</strong> not exists.' );
		}

        $paths = [
            'messages'     => $formdir . '/messages.php',
            'fields'       => $formdir . '/fields.php',
            'tpl_mail_txt' => $formdir . '/templates/mail.txt',
            'tpl_copy_txt' => $formdir . '/templates/copy.txt'
        ];

        foreach ( $paths as $param => $path ) {
            if ( ! is_file( $path ) ) {
                $this->abort( $form_slug . ' setup error. <strong>' . substr( $path, strrpos( $path, '/' ) ) . '</strong> not exists.' );
            } else {
				// Include messages and fields, save template pathes
                $this->$param = ( false === strpos( $param, 'tpl' ) ) ? include $path : $path;
            }
        }

        // Html email templates (optional)
        $paths = [
            'tpl_mail_htm' => $formdir . '/templates/mail.htm',
            'tpl_copy_htm' => $formdir . '/templates/copy.htm',
        ];

        foreach( $paths as $param => $path ) {
            if ( is_file( $path ) ) {
                $this->$param = $path;
            }
		}

        $this->form_slug = $form_slug;
		$this->post_id = $post_id;
    }

    /**
     * Render form html
     */
    public function render_form() {
		if ( ! class_exists( 'Form_helper' ) ) {
			require_once plugin_dir_path( __FILE__ ) . '../pillanart-ms/includes/form-helper.class.php';
		}

		$this->formhelp = new Form_helper( false, 'div' );

		$args = [
			'name'           => $this->form_slug,
			'id'             => $this->form_slug,
			'action'         => '#' . $this->form_slug,
			'method'         => 'post',
			'accept-charset' => 'utf-8'
		];
		$this->formhelp->html_tag( [ 'form', $args ] );
		$this->formhelp->html_tag( [ 'input', [ 'type' => 'hidden', 'name' => 'salad', 'value' => $this->mix_salad() ] ] );
		$this->formhelp->html_tag( [ 'input', [ 'type' => 'hidden', 'name' => 'form_slug', 'value' => $this->form_slug ] ] );

		$noscript = '<noscript><div class="wrap-' . $this->form_slug . ' js-required">%s</div></noscript>' . "\n";
		printf( $noscript, __( '<strong>This form requires JavaScript.</strong><br>Please allow JavaScript on this page! No malicious code here, promise.', 'swift-contact-form' ) );

        foreach( $this->fields as $field ) {
            $this->render_field( $field );
        }

		echo "</form>\n";
    }

    /**
     * Save users array to db
     *
     * Must be public, this method also called from theme functions.php
     */
    public function save_transient() {
        $this->rm_old_normal_users();

        set_transient( 'scf-users', $this->users );
    }

	/**
	 * Render a field html
	 *
	 * @param array $field [0] string: html tag
	 *                     [1] array: field params (type, name, class, label, validate...)
	 */
	private function render_field( $field ) {
		$tag = $field[0];
		$args = $field[1];
		extract( $args );

		if ( isset( $validate ) ) {
			if ( false !== strpos( $validate, 'required' ) ) {
				$args['required'] = true;
				if ( isset( $label ) ) {
					$args['label'] = $label . ' *';
				}
			}
		}

		if ( 'math' === $tag ) {
			$this->render_math_block( $args );
		} else {
			$func = 'render_' . $tag;
			$func .= isset( $type ) ? '_' . $type : '';
			call_user_func( [ $this->formhelp, $func ], $args, $this->form_slug );
		}
	}

	/**
	 * Render fake turing test
	 *
	 * @param array $args
	 */
	private function render_math_block( $args ) {
		$this->formhelp->check_args( $args, [ 'name', 'label' ] );
		extract( $args );

		/**
		 * Extracted variables
		 *
		 * @var $label
		 * @var $name
		 */

		$label .= ' *';  // Actually this is a honeypot trap, but it must to seem real...
		$this->formhelp->open_wrapper( $name, $this->form_slug );
		$this->formhelp->html_tag( [ 'label', [ 'for' => $name ], $label, true ] );

		$math = $this->math_turing_test();

		$op = ( 0 === $math[2] ) ? '+' : '-';
		printf( '%s %s %s =', $math[0], $op, $math[1] );

		$this->formhelp->html_tag( [ 'input', [ 'type' => 'number', 'name' => $name, 'id' => $name ] ] );
		$this->formhelp->html_tag( [ 'input', [ 'type' => 'hidden', 'name' => 'n1', 'value' => $math[0] ] ] );
		$this->formhelp->html_tag( [ 'input', [ 'type' => 'hidden', 'name' => 'n2', 'value' => $math[1] ] ] );
		$this->formhelp->html_tag( [ 'input', [ 'type' => 'hidden', 'name' => 'op', 'value' => $math[2] ] ] );

		$this->formhelp->close_wrapper();

		// Save formula with user
		$this->save_formula_data( $math );
	}

    /**
     * Math Turing test values
	 *
     * @return array  [0] (int) first number
     *                [1] (int) second number
     *                [2] (int) operation: 0 = plus, 1 = minus
     *                [3] (int) result
     */
    private function math_turing_test() {
        $n1 = mt_rand( 1, 9 );
        $n2 = mt_rand( 1, 9 );
        while( $n1 === $n2 ) {
			// Two different number please
            $n2 = mt_rand( 1, 9 );
        }

        switch ( $op = mt_rand( 0, 1 ) ) {
            case 0:
                $res = $n1 + $n2;
                break;
            case 1:
                if ( $n2 > $n1 ) {
					// Replace numbers, so the result will be positive
                    $n0 = $n2;
                    $n2 = $n1;
                    $n1 = $n0;
                }
                $res = $n1 - $n2;
		        break;
	        default:  // Avoid PhpStorm warning
		        $res = 0;
        }

        return [ $n1, $n2, $op, $res ];
    }

	/**
	 * Save formula data
	 *
	 * @param array $math Turing test current values
	 */
	private function save_formula_data( $math ) {
		$user = $this->get_user_data();

		$user['math_num_1']     = $math[0];
		$user['math_num_2']     = $math[1];
		$user['math_operation'] = ( $math[2] === 1 ) ? 'minus' : 'plus';
		$user['math_answer']    = $math[3];

		$this->save_user_data( $user );
	}

    /**
     * Form post
     */
    private function form_post() {
		$this->set_form( $_POST['form_slug'], $this->unmix_salad( $_POST['salad'] ) );
        $this->js = isset( $_POST['potato'] ) ? true : false;

		// Check user was sent from the form
        if ( ! $this->validate_user() ) {
            $this->abort();
        }

		// Check honeypot trap
		$answer = $this->validate_math();
		if ( is_array( $answer) ) {
			$this->register_user_spambot();
            $this->respond( $answer );
		}

		// Validate fields
		if ( true !== ( $errors = $this->validate_fields() ) ) {
			$this->respond( [ 'errors' => $errors ] );
		}

		// Set subject
		if ( 'contact' === $this->form_slug ) {

			if ( isset( $this->post['subject'] ) && ! empty( $this->post['subject'] ) ) {
				$this->subject = $this->post['subject'];
			} else {
				$this->subject = __( 'Pillana(r)t Contact Message', 'swift-contact-form' );
			}

		} else {

			$this->subject = __( 'Pillana(r)t Wedding Request', 'swift-contact-form' );

		}

		// Set from address (This must match with the server or domain.)
		$this->set_address( 'from', get_option( 'admin_email' ), get_option( 'blogname' ) );

		// Set to and replyto addresses
		if ( 'contact' === $this->form_slug ) {

			$this->set_address( 'to', get_option( 'admin_email' ), get_option( 'blogname' ) );
			$this->set_address( 'replyto', $this->to['address'], $this->to['name'] );

		} else {

			$this->set_address( 'to', get_post_meta( $this->post_id, '_member_email', true ), get_the_title( $this->post_id ) );

			if ( 1 == get_post_meta( $this->post_id, '_email_is_public', true ) ) {
				$this->set_address( 'replyto', $this->to['address'], $this->to['name'] );
			} else {
				$this->set_address( 'replyto', $this->from['address'], $this->from['name'] );
			}

		}

		// Send mail
		$respond = $this->send_mail();
		$this->respond( $respond );
    }

	/**
	 * Set an address after email validation
	 *
	 * @param string $addrname
	 * @param string $email
	 * @param string $name
	 */
	private function set_address( $addrname, $email, $name = '' ) {
		if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$this->abort( 'Internal email error.' );
		}

		$address = [ 'address' => $email, 'name' => $name ];
		$this->$addrname = $address;
	}


	/**
	 * Crypt post ID a little bit
	 *
	 * @return float
	 */
	private function mix_salad() {
		return ($this->post_id + 13547) * 3;
	}

	/**
	 * Decrypt post ID
	 *
	 * @param  float $salad
	 * @return float
	 */
	private function unmix_salad( $salad ) {
		return $salad / 3 - 13547;
	}

    /**
     * Respond
	 *
     * @param array $messages
     */
    private function respond( $messages ) {
        if ( true === $this->js ) {
            $this->respond_json( $messages );
        }

		$this->abort();
    }

    /**
     * Respond json
	 *
	 * @param array $messages
     */
    private function respond_json( $messages ) {
		// Allow for cross-domain requests (from the frontend).
		send_origin_headers();

		// Send proper headers
		@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
		@header( 'X-Robots-Tag: noindex' );
		send_nosniff_header();
		nocache_headers();

        echo json_encode( $messages );
    }

	/**
	 * Prepare post data, setup PHPMailer and send it out
	 */
	private function send_mail() {
		// First line
		$formname = ucfirst( str_replace( '_', ' ', $this->form_slug ) );
		$values = [
			'mail_message' => sprintf( __( 'Message sent from %s by %s form.', 'swift-contact-form' ), get_option( 'blogname' ), $formname ),
			'copy_message' => sprintf( __( 'This is a copy of your message sent from %s by %s form.', 'swift-contact-form' ), get_option( 'blogname' ), $formname )
		];

		// Prepare posted data for templates
		$sendcopy = false;
		foreach ( $this->fields as $field ) {
			$tag = $field[0];
			/**
			 * @var $type
			 * @var $label
			 * @var $name
			 */
			extract( $field[1] );

			if ( 'input' === $tag && in_array( $type, [ 'text', 'email' ] ) && isset( $this->post[ $name ] ) ) {
				$values[ $name . '_label' ] = $label;
				$values[ $name ] = $this->post[ $name ];
			} elseif ( 'textarea' === $tag && isset( $this->post[ $name ] ) ) {
				$values[ $name . '_label' ] = $label;
				$values[ $name ] = $this->post[ $name ];
				$values[ $name . '_html' ] = $this->text2html( $this->post[ $name ] );
			} elseif ( 'input' === $tag && 'checkbox' === $type && 'sendcopy' === $name && 1 == $this->post[ $name ] ) {
				$sendcopy = true;
			}
		}
		/**
		 * @var $mail_message
		 * @var $copy_message
		 * @var $from_email
		 * @var $from_name
		 */
		extract( $values );

		// PHPMailer
		if ( ! class_exists( 'PHPMailer' ) ) {
			include_once plugin_dir_path( __FILE__ ) . 'lib/PHPMailer/PHPMailerAutoload.php';
		}

		$mail = new PHPMailer;
		$mail->CharSet = get_bloginfo( 'charset' );
		$mail->Subject = $this->subject;
		$mail->setLanguage( substr( get_locale(), 0, 2 ) );

		if ( isset( $this->tpl_mail_htm ) ) {
			$mail->msgHTML( $this->render_email_template( $this->tpl_mail_htm, $values ) );
			$mail->AltBody = $this->render_email_template( $this->tpl_mail_txt, $values );
		} else {
			$mail->Body = $this->render_email_template( $this->tpl_mail_txt, $values );
		}

		$mail->setFrom( $this->from['address'], $this->from['name'] );
		$mail->addReplyTo( $from_email, $from_name );
		$mail->addAddress( $this->to['address'], $this->to['name'] );

		if ( ! $mail->send() ) {
			$respond = [ 'errors' => [ [ 'field' => 'none', 'error' => $this->messages['errors']['no_server'] . '<br>' . $mail->ErrorInfo ] ] ];
		} else {
			$respond = [ 'success' => $this->messages['success'] ];
		}

		// Send copy
		if ( true === $sendcopy && ! isset( $respond['errors'] ) ) {
			$mail->clearReplyTos();
			$mail->clearAddresses();

			if ( isset( $this->tpl_copy_htm ) ) {
				$mail->msgHTML( $this->render_email_template( $this->tpl_copy_htm, $values ) );
				$mail->AltBody = $this->render_email_template( $this->tpl_copy_txt, $values );
			} else {
				$mail->Body = $this->render_email_template( $this->tpl_copy_txt, $values );
			}

			$mail->addReplyTo( $this->replyto['address'], $this->replyto['name'] );
			$mail->addAddress( $from_email, $from_name );
			$mail->send();
		}

		return $respond;
	}

	/**
	 * Render an email template
	 *
	 * @param string $tpl_path
	 * @param array  $values
	 * @return string
	 */
	private function render_email_template( $tpl_path, $values ) {
		$tpl = file_get_contents( $tpl_path );
		foreach ( $values as $param => $value ) {
			$tpl = str_replace( '{' . $param . '}', $value, $tpl );
		}

		return $tpl;
	}

	/**
	 * Convert textarea to html
	 *
	 * @param string $str
	 * @return string
	 */
	private function text2html( $str ) {
		$str = trim( $str );
		// $str = nl2br( $str, false ); WTF Maxer?
		$str = str_replace( [ "\r\n", "\r", "\n" ], '<br>', $str );
		$str = preg_replace( '/(<br>\s*){2,}/', '</p><p>', $str );

		return "<p>$str</p>";
	}

	/**
	 * Validate math field (honeypot trap)
	 *
	 * @return array|true
	 */
	private function validate_math() {
		if ( isset( $_POST['answer'] ) && ! empty( $_POST['answer'] ) ) {
			// Haha. Check it anyway

			$val = (int) sanitize_text_field( $_POST['answer'] );
			if ( true !== ( $validation = $this->validate_numeric( [ $val ] ) ) ) {
				$this->log_user_fail( 'User filled up math field with non numeric data' );
				return [ 'errors' => [
                    [
                        'field' => 'answer',
                        'error' => $this->messages['errors'][ $validation ]
                    ]
                ] ];
			}

			$user = $this->get_user_data();
			$post_data = [ 'n1', 'n2', 'op', 'answer' ];
			foreach ( $post_data as $i ) {
				if ( $user['math_' . $i ] !== (int) $_POST[ $i ] ) {
					$this->log_user_fail( "User filled up invisible field with wrong math_$i value" );
					return [ 'errors' => [ [ 'field' => 'answer', 'error' => $this->messages['errors']['math'] ] ] ];
				}
			}

			$this->log_user_fail( 'User filled up math field' );

			// Right result. Ok, than give false positive message
			return [ 'success' => $this->messages['success'] ];

		}

		return true;
	}

	/**
	 * Validate fields
	 *
	 * Loop trough all registered field and
	 * validate or just sanitize them
	 *
	 * @uses validate_required
	 * @uses validate_email
	 * @uses validate_numeric
	 * @uses validate_min
	 * @uses validate_max
	 * @return array|true Error messages or success
	 */
	private function validate_fields() {
		foreach ( $this->fields as $args ) {
			$tag = $args[0];
			$params = $args[1];
			$name = $params['name'];
			$post = isset( $_POST[ $name ] ) ? $_POST[ $name ] : null;

			// Filter inputs
			if ( 'textarea' === $tag ) {
				$post = strip_tags( $post );
			} elseif ( 'checkbox' === $tag ) {
				$post = isset( $post ) ? 1 : 0;
			} else {
				$post = sanitize_text_field( $post );
			}

			// Validate inputs
			if ( isset( $params['validate'] ) ) {
				$validators = explode( '|', $params['validate'] );
				foreach ( $validators as $validator ) {
					if ( false !== $pos = strpos( $validator, '=' ) ) {
						$func  = substr( $validator, 0, $pos );
						$data = (int) substr( $validator, $pos + 1 );
					} else {
						$func = $validator;
						$data = null;
					}

					if ( true !== ( $validation = call_user_func( [ $this, 'validate_' . $func ], [ $post, $data ] ) ) ) {
						$this->log_user_fail( 'Validation error: ' . $validation );
						$errors[] = [ 'field' => $name, 'error' => $this->messages['errors'][ $validation ] ];
					}
				}
			}

			$this->post[ $name ] = $post;
		}

		return isset( $errors ) ? $errors : true;
	}

	/**
	 * Validators
	 *
	 * @param  array $args
	 * @return bool|string True if passed or error message index
	 */

	// Required
	private function validate_required( $args ) {
		if ( empty( $args[0] ) ) {
			return 'required';
		}

		return true;
	}

	// Email
	private function validate_email( $args ) {
		if ( ! filter_var( $args[0], FILTER_VALIDATE_EMAIL ) ) {
			return 'no_email';
		}

		return true;
	}

	// Numeric
	private function validate_numeric( $args ) {
		if ( ! filter_var( $args[0], FILTER_VALIDATE_INT ) ) {
			return 'no_number';
		}

		return true;
	}

	// Min
	private function validate_min( $args ) {
		if ( $args[1] > mb_strlen( $args[0] ) ) {
			return 'short';
		}

		return true;
	}

	// Max
	private function validate_max( $args ) {
		if ( $args[1] < mb_strlen( $args[0] ) ) {
			return 'long';
		}

		return true;
	}

    /**
     * Validate user
	 *
     * @return bool
     */
    private function validate_user() {
        if ( ! isset( $this->users[ $this->fprint ] ) ) {

			// User tried to post without viewing the form
            $user = array_merge( $this->get_server_data(), [
				'start_time'        => $this->mtime,
                'first_submit_time' => $this->mtime,
                'last_submit_time'  => $this->mtime,
				'type'              => 'SPAMBOT',
                'fails'             => [ 'User tried to post without viewing the form' ],
            ]);

            $this->save_user_data( $user );
            return false;

        } else {

            $user = $this->get_user_data();

        }

		// Save submit times
        if ( ! isset( $user['first_submit_time'] ) ) {
            $user['first_submit_time'] = $this->mtime;
        }
        $user['last_submit_time'] = $this->mtime;

		$this->save_user_data( $user );


		// Ban spambots for three days
        if ( 'SPAMBOT' === $user['type'] ) {
            if ( 3 * DAY_IN_SECONDS > $this->mtime - $user['last_submit_time'] ) {

				$this->log_user_fail( 'Banned SPAMBOT another attempt' );
				return false;

            }
        }

		// User filled up the form in 1 second
        if ( 1 > $this->mtime - $user['start_time'] ) {

			$this->log_user_fail( 'User filled up the form within 1 second' );
            return false;

        }

        // User failed at least 3 times, and avarage submit time is less than 1 second
		if ( isset( $user['fails'] ) && 3 >= count( $user['fails'] ) ) {

            $avg_submit_time = ( $this->mtime - $user['first_submit_time'] ) / count( $user['fails'] );

            if ( 1 > $avg_submit_time ) {

				$this->log_user_fail( 'User avarage submit time less than 1 second' );
				$this->register_user_spambot();
                return false;

            }

        }

		// Ok
        $user['type'] = 'NORMAL';
        $this->save_user_data( $user );
        return true;
    }

	/**
	 * Register user type as spambot
	 */
	private function register_user_spambot() {
		$user = $this->get_user_data();
		$user['type'] = 'SPAMBOT';
		$this->save_user_data( $user );
	}

    /**
     * Create user fingerprint
     */
    private function create_fingerprint() {
        $uagent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : null;
        $ip     = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : null;
        $fprint = substr( NONCE_SALT, 0, 20 ) . $uagent . substr( NONCE_SALT, 20, 20 ) . $ip . substr( NONCE_SALT, -20 );

        return md5( $fprint );
    }

    /**
     * Hello user
     */
    private function hello_user() {
		$user = $this->get_user_data();
        $this->save_user_data( $user );
    }

    /**
     * Get server data
	 *
     * @return array
     */
    private function get_server_data() {
        $user = [];
        $user['IP']      = isset( $_SERVER['REMOTE_ADDR'] )     ? $_SERVER['REMOTE_ADDR']     : null;
        $user['uagent']  = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : null;
        $user['referer'] = isset( $_SERVER['HTTP_REFERER'] )    ? $_SERVER['HTTP_REFERER']    : null;
        $user['type']    = 'NORMAL';

        return $user;
    }

	/**
	 * Log user fail
	 *
	 * @param string $msg
	 */
	private function log_user_fail( $msg ) {
		$user = $this->get_user_data();

		$fails = isset( $user['fails'] ) ? $user['fails'] : [];
		$fails[] = $msg;

		$this->save_user_data( $user );
	}

    /**
     * Remove old normal users
     */
    private function rm_old_normal_users() {
        foreach ( $this->users as $fprint => $user ) {
	        $age = $this->mtime - $user['start_time'];
	        $expired = $age > 12 * HOUR_IN_SECONDS;

	        if ( $expired && 'NORMAL' === $user['type'] ) {
		        unset( $this->users[ $fprint ] );
	        }
        }
    }

	/**
	 * Save users data and exit
	 *
	 * @param null|string $msg
	 */
	private function abort( $msg = null ) {
        $this->save_transient();

		if ( isset( $msg ) ) {
			exit( $msg );
		} else {
			exit;
		}
	}

	/**
	 * Save user data
	 *
	 * @param array $data
	 */
    private function save_user_data( $data ) {
        $this->users[ $this->fprint ] = $data;
    }

	/**
	 * Get user data
	 */
	private function get_user_data() {
		if ( isset( $this->users[ $this->fprint ] ) ) {
			return $this->users[ $this->fprint ];
		}

		// else
		$user = $this->get_server_data();
        $user['start_time'] = $this->mtime;

		return $user;
	}

}