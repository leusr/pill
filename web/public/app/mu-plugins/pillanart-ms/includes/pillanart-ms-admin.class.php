<?php

class Pillanart_MS_Admin {

	/**
	 * Instance of this class.
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * All meta boxes and settings superarray
	 * @var array of objects
	 */
    private $mb = [];

	/**
	 * @var Form_helper object
	 */
	private $formhelp;

	/**
	 * Constructor.
	 */
	private function __construct() {
		// Get a Form helper object
		$this->formhelp = new Form_helper;

		// Load admin style sheet and JavaScript
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ] );

		// Extend user profile
		add_action( 'show_user_profile', [ $this, 'social_fields' ] );
		add_action( 'edit_user_profile', [ $this, 'social_fields' ] );
		add_action( 'personal_options_update', [ $this, 'save_social_fields' ] );
		add_action( 'edit_user_profile_update', [ $this, 'save_social_fields' ] );

		// Add meta boxes
		add_action( 'admin_init', [ $this, 'init_mb_array' ] );
		add_action( 'add_meta_boxes', [ $this, 'add_seo_tags_box' ], 10, 2 );
		add_action( 'add_meta_boxes_member', [ $this, 'add_member_data_box' ], 10, 1 );
		add_action( 'add_meta_boxes_post', [ $this, 'add_related_members_box' ], 10, 1 );

		// Save meta boxes
		add_action( 'save_post', [ $this, 'save_all_meta_boxes' ] );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @static
	 * @staticvar object|null $instance
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Pillanart_MS_Admin ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific stylesheet.
	 */
	public function enqueue_admin_styles() {
        if ( in_array( get_current_screen()->id, [ 'post', 'page', 'member', 'partner' ] ) ) {
	        $min = defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ? "" : '.min';

            wp_enqueue_style( 'pillanart-ms', plugins_url( "assets/pillanart-ms{$min}.css", __DIR__ ) );
        }
	}

	/**
	 * Show social fields
	 *
	 * @param object $user
	 */
	public function social_fields( $user ) { ?>

<h3><?php _e( 'Social Pages', 'pillanart-ms' ); ?></h3>
<table class="form-table">
<tr>
<th><label for="facebook">Facebook</label></th>
<td>
	<input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( get_user_meta( $user->ID, 'facebook', true ) ); ?>" class="regular-text" /><br />
	<span class="description"><?php _e( 'Full url, e.g.: https://facebook.com/pillanart', 'pillanart-ms' ); ?></span>
</td>
</tr>
<tr>
<tr>
<th><label for="gplus">Google+</label></th>
<td>
	<input type="text" name="gplus" id="gplus" value="<?php echo esc_attr( get_user_meta( $user->ID, 'gplus', true ) ); ?>" class="regular-text" /><br />
	<span class="description"><?php _e( 'Full url, e.g.: https://plus.google.com/118103047823502039409/posts', 'pillanart-ms' ); ?></span>
</td>
</tr>
<tr>
<th><label for="twitter">Twitter</label></th>
<td>
	<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_user_meta( $user->ID, 'twitter', true ) ); ?>" class="regular-text" /><br />
	<span class="description"><?php _e( 'Only username starting with @, e.g.: @pillanart', 'pillanart-ms' ); ?></span>
</td>
</tr>
</table>

<?php
	}

	/**
	 * Sanitize and save social fields
	 *
	 * @param int $user_id
	 * @return boolean
	 */
	public function save_social_fields( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		$fields = [ 'facebook', 'gplus', 'twitter' ];
		foreach ( $fields as $field ) {
			$val = sanitize_text_field( $_POST[ $field ] );
			if ( ! empty( $val ) ) {
				update_user_meta( $user_id, $field, $val );
			}
		}

		return true;
	}

	/**
	 * Post ID by $_REQUEST
	 * Getting post id without WordPress hook dependencies
	 *
	 * @return int|null
	 */
	private function get_post_id_by_request() {
		if ( isset( $_GET['post'] ) && is_numeric( $_GET['post'] )) {
			return $_GET['post'];
		}
		if ( isset( $_POST['post_ID'] ) && is_numeric( $_POST['post_ID'] ) ) {
			return $_POST['post_ID'];
		}

		return null;
	}

	/**
	 * Screen ID by $_REQUEST
	 * Getting screen id without WordPress hook
	 * dependencies (earlier than the built in filter)
	 *
	 * @return string
	 */
	private function get_screen_id_by_request() {
		$filename = basename( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) );
		switch ( $filename ) {
			case 'post-new.php':
				if ( isset( $_GET['post_type'] ) ) {
					return $_GET['post_type'];
				}
				return 'post';

			case 'post.php':
				if ( isset( $_POST['action'] ) && 'editpost' === $_POST['action'] ) {
					if ( isset( $_POST['post_type'] ) ) {
						return $_POST['post_type'];
					}
				}
				$post_id = $this->get_post_id_by_request();
				if ( ! is_null( $post_id ) ) {
					$post = get_post( $post_id );
					return $post->post_type;
				}
				break;
			// ...
		}

		return 'undetected';
	}

	/**
	 * Fill up our private $mb array with meta box datas
	 */
	public function init_mb_array() {
		$screen_id = $this->get_screen_id_by_request();

		// SEO
		if ( in_array( $screen_id, [ 'post', 'page', 'member', 'partner' ] ) ) {
			$this->mb['seo-box'] = (object) [
				'title'    => __( 'Search engine optimization', 'pillanart-ms' ),
				'context'  => 'normal',
				'priority' => 'default',
				'fields'   => [
					[ 'input',
						[
							'type'  => 'text',
							'name'  => 'meta_tags_title',
							'class' => 'widefat',
							'label' => __( 'HTML title', 'pillanart-ms' )
						]
					],
					[ 'textarea',
						[
							'name'  => 'meta_tags_description',
							'class' => 'widefat',
							'cols'  => 72,
							'rows'  => 2,
							'label' => __( 'META description', 'pillanart-ms' )
						]
					]
				]
			];
		}

		// Member Data
		if ( 'member' === $screen_id ) {
			$this->mb['member-data'] = (object) [
				'title'    => __( 'Member data', 'pillanart-ms' ),
				'context'  => 'normal',
				'priority' => 'core',
				'fields'   => [
					[ 'input',
						[
							'type'     => 'email',
							'name'     => 'member_email',
							'label'    => __( 'E-mail', 'pillanart-ms' ),
							'desc'     => __( 'Contact form sends emails to this address.', 'pillanart-ms' ),
							'filter'   => 'sanitize_email',
							'required' => true
						]
					],
					[ 'input',
						[
							'type'    => 'checkbox',
							'name'    => 'email_is_public',
							'value'   => 'yes',
							'label'   => __( 'E-mail visible on website', 'pillanart-ms' ),
							'filter'  => [ $this, 'filter_checkbox' ],
							'checked' => true
						]
					],
					[ 'textarea',
						[
							'name'   => 'member_phones',
							'rows'   => 2,
							'label'  => __( 'Phone number(s)', 'pillanart-ms' ),
							'desc'   => __( 'One phone number per line', 'pillanart-ms' ),
							'filter' => [ $this, 'filter_multiline' ]
						]
					],
					[ 'textarea',
						[
							'name'   => 'member_websites',
							'rows'   => 2,
							'label'  => __( 'Website(s)', 'pillanart-ms' ),
							'desc'   => __( 'One url per line', 'pillanart-ms' ),
							'filter' => [ $this, 'filter_multiline' ]
						]
					],
					[ 'input',
						[
							'type'  => 'text',
							'name'  => 'member_facebook',
							'label' => __( 'Facebook profile', 'pillanart-ms' ),
							'desc'  => __( 'Profile (or page) full url, e.g.: https://facebook.com/pillanart', 'pillanart-ms' )
						]
					],
					[ 'input',
						[
							'type'  => 'text',
							'name'  => 'member_gplus',
							'label' => __( 'Google+ profile', 'pillanart-ms' ),
							'desc'  => __( 'Full url, e.g.: https://plus.google.com/118103047823502039409/posts', 'pillanart-ms' )
						]
					],
					[ 'input',
						[
							'type'  => 'text',
							'name'  => 'member_twitter',
							'label' => __( 'Twitter username', 'pillanart-ms' ),
							'desc'  => __( 'Only username starting with @, e.g.: @pillanart', 'pillanart-ms' )
						]
					]
				]
			];
		}

		// Related members
		if ( 'post' === $screen_id ) {
			$members = new WP_Query( [
				'post_type' => 'member',
				'post_status' => 'any',
				'nopaging' => true,
				'orderby' => 'title',
				'order' => 'ASC'
			] );

			$rel_members = get_post_meta( $this->get_post_id_by_request(), '_related_members', true );
			if ( ! empty( $rel_members ) ) {
				if ( strpos( $rel_members, ',' ) ) {
					$rel_members = explode( ',', $rel_members );
				} else {
					$rel_members = [ $rel_members ];
				}
			} else {
				$rel_members = [];
			}

			$fields = [];
			foreach ( $members->posts as $member ) {
				$checked = in_array( $member->ID, $rel_members ) ? true : false;
				$fields[] = [ 'input',
					[
						'type'    => 'checkbox',
						'name'    => 'related-' . $member->ID,
						'value'   => $member->ID,
						'label'   => $member->post_title,
						'filter'  => [ $this, 'filter_checkbox' ],
						'checked' => $checked
					]
				];
			}

			$this->mb['related-members'] = (object) [
				'title'    => __( 'Related Members', 'pillanart-ms' ),
				'context'  => 'normal',
				'priority' => 'default',
				'fields'   => $fields,
				'savefunc' => 'save_related_members'
			];
		}
	}

    /**
     * SEO box
     * Called via 'add_meta_boxes' action
     *
     * @param string $scr_id  Current screen id
     * @param object $post    WP_Post object
     */
    public function add_seo_tags_box( $scr_id, $post ) {
        if ( in_array( $scr_id, [ 'post', 'page', 'member', 'partner' ] ) ) {
			$this->add_mb( 'seo-box', $scr_id );
        }
    }

    /**
     * Member data box
     * Called via 'add_meta_boxes_member' action (so only on member pages)
     *
     * @param object $post WP_Post object
     */
    public function add_member_data_box( $post ) {
        $this->add_mb( 'member-data', 'member' );
    }

    /**
     * Related members box
     * Called via 'add_meta_boxes_post' action
     *
     * @param object $post WP_Post object
     */
    public function add_related_members_box( $post ) {
        $this->add_mb( 'related-members', 'post' );
    }

    /**
     * Add meta box
     *
     * @param string $id     Meta box slug
     * @param string $scr_id Current screen id
     */
    private function add_mb( $id, $scr_id = null ) {
        $mb = $this->mb[ $id ];
        add_meta_box(
            $id,
            $mb->title,
            [ $this, 'render_meta_box' ],
            $scr_id,
            $mb->context,
            $mb->priority
        );
    }

    /**
     * Save post
     *
     * @uses save_meta_box
     * @uses save_related_members
     * @param  int $post_id
     * @return int $post_id
     */
    public function save_all_meta_boxes( $post_id ) {
        // Search for meta box nonce values
        foreach ( $this->mb as $mbid => $mb ) {
            if ( isset( $_POST[ $mbid . '_nonce' ] ) ) {
                $nonce[ $mbid ] = $_POST[ $mbid . '_nonce' ];
            }
        }
		// Check nonce
	    if ( ! isset( $nonce ) || empty( $nonce ) ) {
            return $post_id;
        }
	    // Do save
        foreach ( $nonce as $mbid => $val ) {
			$savefunc = isset( $this->mb[ $mbid ]->savefunc ) ? $this->mb[ $mbid ]->savefunc : 'save_meta_box';

            if ( $post_id === call_user_func( [ $this, $savefunc ], $post_id, $mbid, $val ) ) {
                return $post_id;
            }
        }
	    return $post_id;
    }

	/**
	 * Default meta box save
	 *
	 * @uses filter_multiline
	 * @uses filter_checkbox
	 * @param  int    $pid
	 * @param  string $mbid
	 * @param  string $nonce
	 * @return boolean
	 */
    private function save_meta_box( $pid, $mbid, $nonce ) {
		if ( true !== $this->check_permissions( $pid, $mbid, $nonce ) ) {
			return $pid;
		}

		// Sanitize and update fields
        foreach ( $this->mb[ $mbid ]->fields as $field ) {
            $args = $field[1];
            $name = $args['name'];
            $filter = isset( $args['filter'] ) ? $args['filter'] : 'sanitize_text_field';
			$raw = isset( $_POST[ $name] ) ? $_POST[ $name ] : null;
            $val = call_user_func( $filter, $raw );
            if ( ! empty( $val ) ) {
                update_post_meta( $pid, '_' . $name, $val );
            } else {
				delete_post_meta( $pid, '_' . $name );
			}
        }

        return true;
    }

	/**
	 * Members box save
	 *
	 * @param  int    $post_id
	 * @param  string $mbid
	 * @param  string $nonce
	 * @return bool|int
	 */
	private function save_related_members( $post_id, $mbid, $nonce ) {
		if ( true !== $this->check_permissions( $post_id, $mbid, $nonce ) ) {
			return $post_id;
		}

		// Sanitize, combine and update field
		$member_ids = [];
        foreach ( $this->mb[ $mbid ]->fields as $field ) {
            $args = $field[1];
            $name = $args['name'];
			if ( isset( $_POST[ $name ] ) && is_numeric( $_POST[ $name ] ) ) {
				$member_ids[] = $_POST[ $name ];
			}
        }

		if ( ! empty( $member_ids ) ) {
			update_post_meta( $post_id, '_related_members', implode( ',', $member_ids ) );
		} else {
			delete_post_meta( $post_id, '_related_members' );
		}

		return true;
	}

	/**
	 * Check permissions under saving
	 *
	 * @param  int    $post_id
	 * @param  string $mbid
	 * @param  string $nonce
	 * @return int|true
	 */
	private function check_permissions( $post_id, $mbid, $nonce ) {
		// Validate nonce
        if ( ! wp_verify_nonce( $nonce, $mbid ) ) {
			return $post_id;
        }
        // Skip autosaved posts
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
        // Check the user's permissions
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		// Ok.
		return true;
	}

    /**
     * WordPress callback for rendering a meta box html
     *
     * @param object $post WP_Object
     * @param object $args add_meta_box callback args
     */
    public function render_meta_box( $post, $args ) {
        // Meta box id comes with callback args automatically
        $mbid = $args['id'];

        // Get meta box object from private array
        $mb = $this->mb[ $mbid ];

        wp_nonce_field( $mbid, $mbid . '_nonce' );

        foreach ( $mb->fields as $field ) {
            $tag = $field[0];
            $args = $field[1];
            $name = $args['name'];
            $val = get_post_meta( $post->ID, '_' . $name, true );
            if ( ! empty( $val ) ) {
                if ( isset( $args['filter'] ) ) {
                    $val = call_user_func( $args['filter'], $val );
                }
                $args['value'] = $val;
            }

            $func = 'render_' . $tag;
            $func .= isset( $args['type'] ) ? '_' . $args['type'] : '';
            call_user_func( [ $this->formhelp, $func ], $args, $mbid );
        }
    }

    /**
     * Filter multiline texts
     *
     * @param string $val
     * @return string nl2br
     */
    private function filter_multiline( $val ) {
        if ( false !== strpos( $val, '<br' ) ) {
            return preg_replace( '/\<br(\s*)?\/?\>/i', "\n", $val );
        } else {
            return esc_sql( str_replace( [ "\r\n", "\r", "\n" ], '<br>', $val ) );
        }
    }

    /**
     * Filter checkbox
     *
     * @param mixed $val
     * @return mixed
     */
    private function filter_checkbox( $val ) {
        if ( is_null( $val ) ) {
            return 0;
        } else {
            return $val;
        }
    }

}