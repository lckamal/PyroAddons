<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* ajax controller
*/

class Ajax extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Load required items
        $this->lang->load('choice');

        // Add initial items
        $this->data = new stdClass();
        // Ensure request was made
        if ( ! $this->input->is_ajax_request() ) { show_404(); }
    }

    /**
     * Update the entries order
     *
     * Accessed via AJAX
     *
     * @return  void
     */
    public function entry_order_update()
    {
        $ids = explode(',', $this->input->post('order'));

        // Set the count by the offset for
        // paginated lists
        $order_count = $this->input->post('offset')+1;

        foreach ($ids as $id)
        {
            $this->db
                    ->where('choice_id', $id)
                    ->update('choices', array('ordering_count' => $order_count));

            ++$order_count;
        }
    }

}
