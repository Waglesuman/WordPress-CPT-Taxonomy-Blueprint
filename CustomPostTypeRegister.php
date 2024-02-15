<?php 
/**
 * Class for registering a custom post type.
 */
class CustomPostType {
    /**
     * The post type identifier.
     *
     * @var string
     */
    private $postType;

    /**
     * Arguments for registering the post type.
     *
     * @var array
     */
    private $args;

    /**
     * Constructor for the CustomPostType class.
     *
     * Initializes the post type and its arguments.
     *
     * @param string $postType The post type identifier.
     * @param string $singularName Singular name for the post type.
     * @param string $pluralName Plural name for the post type.
     * @param string $textDomain Text domain for localization. Defaults to 'default'.
     */
    public function __construct($postType, $singularName, $pluralName, $textDomain = 'default') {
        $this->postType = $postType;
        $this->args = [
            'labels' => $this->generateLabels($singularName, $pluralName, $textDomain),
            'capability_type' => 'post',
            // Other default arguments can be set here as well.
        ];
    }

 /**
     * Generates labels for the custom post type.
     *
     * @param string $singularName Singular name for the post type.
     * @param string $pluralName Plural name for the post type.
     * @param string $textDomain Text domain for localization.
     * @return array Array of labels for the custom post type.
     */
    private function generateLabels($singularName, $pluralName, $textDomain) {
        return [
            'name'                  => _x($pluralName, 'Post type general name', $textDomain),
            'singular_name'         => _x($singularName, 'Post type singular name', $textDomain),
            'menu_name'             => _x($pluralName, 'Admin Menu text', $textDomain),
            'name_admin_bar'        => _x($singularName, 'Add New on Toolbar', $textDomain),
            'add_new'               => __('Add New', $textDomain),
            'add_new_item'          => __('Add New ' . $singularName, $textDomain),
            'new_item'              => __('New ' . $singularName, $textDomain),
            'edit_item'             => __('Edit ' . $singularName, $textDomain),
            'view_item'             => __('View ' . $singularName, $textDomain),
            'all_items'             => __('All ' . $pluralName, $textDomain),
            'search_items'          => __('Search ' . $pluralName, $textDomain),
            'parent_item_colon'     => __('Parent ' . $pluralName . ':', $textDomain),
            'not_found'             => __('No ' . strtolower($pluralName) . ' found.', $textDomain),
            'not_found_in_trash'    => __('No ' . strtolower($pluralName) . ' found in Trash.', $textDomain),
            'featured_image'        => _x($singularName . ' Cover Image', 'Overrides the “Featured Image” phrase', $textDomain),
            'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase', $textDomain),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase', $textDomain),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase', $textDomain),
            'archives'              => _x($singularName . ' archives', 'The post type archive label used in nav menus', $textDomain),
            'insert_into_item'      => _x('Insert into ' . strtolower($singularName), 'Overrides the “Insert into post”/“Insert into page” phrase', $textDomain),
            'uploaded_to_this_item' => _x('Uploaded to this ' . strtolower($singularName), 'Overrides the “Uploaded to this post”/“Uploaded to this page” phrase', $textDomain),
            'filter_items_list'     => _x('Filter ' . strtolower($pluralName) . ' list', 'Screen reader text for the filter links', $textDomain),
            'items_list_navigation' => _x($pluralName . ' list navigation', 'Screen reader text for the pagination', $textDomain),
            'items_list'            => _x($pluralName . ' list', 'Screen reader text for the items list', $textDomain),
        ];
    }

     /**
     * Registers the custom post type.
     *
     * Adds an action to register the post type if it doesn't already exist.
     */
    public function register() {
        if (!post_type_exists($this->postType)) {
            add_action('init', [$this, 'registerPostType']);
        }  else {
            // Optionally handle the case where the post type already exists.
            error_log("The post type {$this->postType} already exists.");
        }
    }

    /**
     * Registers the post type with WordPress.
     *
     * This method is hooked to the 'init' action.
     */
    public function registerPostType() {
         $result = register_post_type($this->postType, $this->args);

        if (is_wp_error($result)) {
            // Handle the error. 
            error_log("Failed to register the post type {$this->postType}: " . $result->get_error_message());
        }
    }

    /**
     * Updates the arguments for the post type registration.
     *
     * Allows for setting or updating registration arguments after instantiation.
     *
     * @param array $args New or updated arguments for post type registration.
     */
    public function setArgs($args) {
        $this->args = array_merge($this->args, $args);
    }
}

// Usage example
$booksPostType = new CustomPostType('books', 'Book', 'Books', 'your_text_domain');
$booksPostType->setArgs([
    'public' => true,
    'has_archive' => true,
    'supports' => ['title', 'editor', 'thumbnail'],
    'rewrite' => ['slug' => 'books'],
]);
$booksPostType->register();