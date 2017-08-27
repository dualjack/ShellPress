<?php
namespace shellpress\v1_0_6\src\Shared\AjaxListTable\_Controllers;

/**
 * @author DualJack
 * Date: 2017-07-25
 * Time: 19:46
 */
use tmc\mailboo\src\App;

/**
 * Class AjaxListTable.
 * This class just extends the modified version of WP_List_Table.
 * It sets up some basic hooks for you.
 *
 * The code style is different in some parts of class, because it extends
 * the core class WP_List_Table - made by WordPress team.
 *
 * @package shellpress\v1_0_6\src\Shared
 */
class WP_Ajax_listTable_Wrapper extends WP_Ajax_List_Table {

    /** @var string */
    public $slug;

    /** @var array */
    public $params;

    /**
     * Table columns headers array.
     *
     * Format:
     * array(
     *      '{slug}'    =>  array(
     *          'isHidden'          =>  false,                  (optional)
     *          'isSortable'        =>  true,                   (optional)
     *          'isAlreadySorted'   =>  false,                  (optional)
     *          'realColumnName'    =>  '{sql column name}'     (optional)
     *          'title'             =>  'Your column title'     (optional)
     *      )
     * )
     *
     * @var array
     */
    public $headers = array();


    /**
     * AjaxListTable constructor.
     *
     * @param string $tableSlug     Unique key
     * @param array $params           Table parameters
     */
    public function __construct( $tableSlug, & $params ) {

        //Set parent defaults
        parent::__construct(
            array(
                'singular'	=> 'item',
                'plural'	=> 'items',
            )
        );

        //  ----------------------------------------
        //  Properties
        //  ----------------------------------------

        $this->slug = sanitize_key( $tableSlug );

        $this->params = & $params;

    }

    //  ================================================================================
    //  LIST TABLE SPECIFIC METHODS
    //  ================================================================================

    /**
     * **** WP_List_Table specific
     */
    public function prepare_items() {

        //  ----------------------------------------
        //  Columns headers hook
        //  ----------------------------------------

        $headers = $this->headers;  //  Get default value ( empty array )

        //  Add bulk actions checkbox column

        if( ! empty( $this->get_bar_actions() ) ){

            $headers['cb'] = array(
                'title'     =>  '<input type="checkbox">'
            );

        }

        //  Add id column

        $headers['id'] = array(
            'title'         =>  'ID'
        );

        /**
         * Apply filter on empty array.
         * Filter tag: `headers_{tableSlug}`
         *
         * @param array $headers
         */
        $headers = apply_filters(
            'headers_' . $this->slug,               //  Filter tag
            $headers                                //  $headers
        );

        //  Apply default properties

        foreach( $headers as $slug => $columnArgs ){

            $defaultColumnArgs = array(
                'isHidden'          =>  false,
                'isSortable'        =>  false,
                'isAlreadySorted'   =>  false,
                'realColumnName'    =>  $slug,
                'title'             =>  $slug
            );

            $headers[ $slug ] = array_merge( $defaultColumnArgs, (array) $columnArgs );

        }

        //  Set headers

        $this->headers = $headers;

        //  ----------------------------------------
        //  Applying columns headers
        //  ----------------------------------------

        $columns    = $this->get_columns();
        $hidden     = $this->get_hidden_columns();
        $sortable   = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable );

        //  ----------------------------------------
        //  Items
        //  ----------------------------------------

        /**
         * Apply filter on empty array.
         * Filter tag: `items_{tableSlug}`
         *
         * @param array $items
         * @param int $itemsPerPage
         * @param int $paged
         * @param string $search
         * @param string $order
         * @param string $orderBy
         * @param string $view
         */
        $this->items = apply_filters( 'items_' . $this->slug, array(),
            $this->getItemsPerPage(),   //  $itemsPerPage
            $this->getPaged(),          //  $paged
            $this->getSearch(),         //  $search
            $this->getOrder(),          //  $order
            $this->getOrderBy(),        //  $orderBy
            $this->getView()            //  $view
        );

        //  ----------------------------------------
        //  Pagination arguments
        //  ----------------------------------------

        $this->set_pagination_args(
            array(
                'total_items'	    =>  $this->getTotalItems(),
                'per_page'	        =>  $this->getItemsPerPage(),
                'total_pages'	    =>  ceil( $this->getTotalItems() / $this->getItemsPerPage() ),
                'orderby'	        =>  $this->getOrderBy(),
                'order'		        =>  $this->getOrder()
            )
        );

    }

    /**
     * Should be called before $this->prepare_items()
     */
    public function process_bulk_action() {

        if( $this->getCurrentcurrentBulkAction() ){

            $itemsIds = (array) $this->getCurrentBulkItems();

            /**
             * Do bulk action.
             * Action tag: `bulk_{tableSlug}_(currentcurrentBulkActionSlug)`
             *
             * @param array $itemsIds
             */
            do_action( 'bulk_' . $this->slug . '_' . $this->getCurrentcurrentBulkAction(), $itemsIds );

        }

    }

    /**
     * **** WP_List_Table specific
     *
     * @return array
     */
    public function get_bar_actions() {

        $barActions = array();

        /**
         * Apply filter on empty array.
         * Filter tag: `bulk_{tableSlug}`
         *
         * @param array $barActions
         */
        $barActions = apply_filters( 'bar_actions_' . $this->slug, $barActions );

        return $barActions;

    }

    /**
     * Should be called before $this->prepare_items()
     */
    public function process_row_action() {

        if( $this->getCurrentRowAction() ){

            /**
             * Do row action.
             * Action tag: `action_{tableSlug}_(currentRowActionSlug)`
             *
             * @param string $itemId
             */
            do_action( 'action_' . $this->slug . '_' . $this->getCurrentRowAction(), $this->getCurrentRowItem() );

        }

    }

    /**
     * **** WP_List_Table specific
     *
     * @return array
     */
    public function get_columns() {

        $columns = array();

        foreach( $this->headers as $slug => $columnArgs ){

            $columns[ $slug ] = $columnArgs['title'];

        }

        return $columns;

    }

    /**
     * **** WP_List_Table specific
     *
     * @return array
     */
    public function get_sortable_columns() {

        $sortableColumns = array();

        foreach( $this->headers as $slug => $columnArgs ){

            if( $columnArgs['isSortable'] === true ){

                $sortableColumns[ $slug ] = array( $columnArgs['realColumnName'], $columnArgs['isAlreadySorted'] );

            }

        }

        return $sortableColumns;

    }

    /**
     * @return array
     */
    public function get_hidden_columns() {

        $hiddenColumns = array();

        foreach( $this->headers as $slug => $columnArgs ){

            if( $columnArgs['isHidden'] === true ){

                $hiddenColumns[] = $slug;

            }

        }

        return $hiddenColumns;

    }

    /**
     * **** WP_List_Table specific
     *
     * @return array
     */
    protected function get_views() {

        $views = array(
            'default'   =>  __( "All" )
        );

        /**
         * Apply filter on array.
         * Filter tag: `views_{tableSlug}`
         *
         * @param array $views
         */
        $views = apply_filters( 'views_' . $this->slug, $views );

        return $views;

    }

    /**
     * **** WP_List_Table specific
     */
    public function views() {

        $views = $this->get_views();

        //  ----------------------------------------
        //  Prepare inside
        //  ----------------------------------------

        if ( ! empty( $views ) ){

            $viewsLinks = array();

            foreach ( $views as $slug => $view ) {

                $viewsLinks[ $slug ] = sprintf( '<li class="%1$s"><a class="%2$s" href="%3$s" data-value="%1$s">%4$s</a></li>',
                    $slug,
                    ( $this->getView() === $slug ) ? 'current' : '',
                    '',
                    $view
                );

            }

            //  ----------------------------------------
            //  Display whole list
            //  ----------------------------------------

            printf( '<ul class="subsubsub">%1$s</ul>',
                implode( " | ", $viewsLinks )
            );

        }

    }

    /**
     * **** WP_List_Table specific
     *
     * @return void
     */
    public function no_items() {

        echo $this->params['noItemsText'];

    }

    /**
     * **** WP_List_Table specific
     *
     * @param mixed $item
     * @param string $column_name
     *
     * @return string
     */
    public function column_default( $item, $column_name ) {

        $html = '';

        /**
         * Apply filter on string.
         * Filter tag: `cell_{tableSlug}_(columnName)`
         *
         * @param string $html
         * @param mixed $item
         */
        $html = apply_filters( 'cell_' . $this->slug . '_' . $column_name, $html, $item );

        return $html;

    }

    /**
     * **** WP_List_Table specific
     *
     * @param mixed $item
     *
     * @return string
     */
    public function column_cb( $item ) {

        $html = '';

        /**
         * Apply filter on string.
         * Filter tag: `cell_{tableSlug}_cb`
         *
         * @param string $html
         * @param mixed $item
         */
        $html = apply_filters( 'cell_' . $this->slug . '_cb', $html, $item );

        return $html;

    }

    /**
     * Display the actions controls on navigation bar.
     */
    protected function bar_actions() {
        
        $actions = $this->get_bar_actions();

        submit_button( __( 'Apply' ), 'action', '', false, array( 'id' => "doaction" ) );

    }

    //  ================================================================================
    //  SIMPLE GETTERS
    //  ================================================================================

    /**
     * Just returns $_REQUEST['order'] or default value.
     *
     * @return string
     */
    public function getOrder() {

        if( isset( $_REQUEST['order'] ) && ! empty( $_REQUEST['order'] ) ){

            return esc_sql( $_REQUEST['order'] );

        } else {

            return esc_sql( $this->params['order'] );

        }

    }

    /**
     * Just returns $_REQUEST['orderby'] or default value.
     *
     * @return string
     */
    public function getOrderBy() {

        if( isset( $_REQUEST['orderBy'] ) && ! empty( $_REQUEST['orderBy'] ) ){

            return esc_sql( $_REQUEST['orderBy'] );

        } else {

            return esc_sql( $this->params['orderBy'] );

        }

    }

    /**
     * Just returns $_REQUEST['paged'] or default value.
     *
     * @return int
     */
    public function getPaged() {

        if( isset( $_REQUEST['paged'] ) && ! empty( $_REQUEST['paged'] ) ){

            return (int) $_REQUEST['paged'];

        } else {

            return (int) $this->params['paged'];

        }

    }

    /**
     * Just returns $_REQUEST['totalitems'] or default value.
     *
     * @return int
     */
    public function getTotalItems() {


        App::logger()->debug( print_r( $this->params, true ) );

        if( isset( $_REQUEST['totalitems'] ) && ! empty( $_REQUEST['totalitems'] ) ){

            return (int) $_REQUEST['totalitems'];

        } else {

            return (int) $this->params['totalItems'];

        }

    }

    /**
     * Just returns $_REQUEST['itemsperpage'] or default value.
     *
     * @return int
     */
    public function getItemsPerPage() {

        if( isset( $_REQUEST['itemsperpage'] ) && ! empty( $_REQUEST['itemsperpage'] ) ){

            return (int) $_REQUEST['itemsperpage'];

        } else {

            return (int) $this->params['itemsPerPage'];

        }

    }

    /**
     * Just returns $_REQUEST['search'] or default value.
     *
     * @return int
     */
    public function getSearch() {

        if( isset( $_REQUEST['search'] ) && ! empty( $_REQUEST['search'] ) ){

            return esc_sql( $_REQUEST['search'] );

        } else {

            return esc_sql( $this->params['search'] );

        }

    }

    /**
     * Just returns $_REQUEST['view'] or default value.
     *
     * @return string
     */
    public function getView() {

        if( isset( $_REQUEST['view'] ) && ! empty( $_REQUEST['view'] ) ){

            return esc_sql( $_REQUEST['view'] );

        } else {

            return esc_sql( $this->params['view'] );

        }

    }

    /**
     * Just returns $_REQUEST['currentBulkAction'] or default value.
     *
     * @return string
     */
    public function getCurrentcurrentBulkAction() {

        if( isset( $_REQUEST['currentBulkAction'] ) && ! empty( $_REQUEST['currentBulkAction'] ) ){

            return esc_sql( $_REQUEST['currentBulkAction'] );

        } else {

            return esc_sql( $this->params['currentBulkAction'] );

        }

    }

    /**
     * Just returns $_REQUEST['bulkItems'] or default value.
     *
     * @return array
     */
    public function getCurrentBulkItems() {

        if( isset( $_REQUEST['bulkItems'] ) && ! empty( $_REQUEST['bulkItems'] ) ){

            return (array) esc_sql( $_REQUEST['bulkItems'] );

        } else {

            return (array) esc_sql( $this->params['currentBulkItems'] );

        }

    }

    /**
     * Just returns $_REQUEST['currentRowAction'] or default value.
     *
     * @return string
     */
    public function getCurrentRowAction() {

        if( isset( $_REQUEST['currentRowAction'] ) && ! empty( $_REQUEST['currentRowAction'] ) ){

            return esc_sql( $_REQUEST['currentRowAction'] );

        } else {

            return esc_sql( $this->params['currentRowAction'] );

        }

    }

    /**
     * Just returns $_REQUEST['currentRowItem'] or default value.
     *
     * @return string
     */
    public function getCurrentRowItem() {

        if( isset( $_REQUEST['currentRowItem'] ) && ! empty( $_REQUEST['currentRowItem'] ) ){

            return esc_sql( $_REQUEST['currentRowItem'] );

        } else {

            return esc_sql( $this->params['currentRowItem'] );

        }

    }

}