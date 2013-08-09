<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * @package  	PyroCMS
 * @subpackage  Categories
 * @category  	Module
 */
class Admin extends Admin_Controller {

	/**
	 * The current active section
	 * @access protected
	 * @var string
	 */
	protected $section = 'posts';

    private $data;
	
	/**
	 * The constructor
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->load->model(array('news_m', 'news_categories_m'));
		$this->lang->load(array('news', 'categories'));
		
		$this->load->library(array('keywords/keywords', 'form_validation'));
        
        $this->item_validation_rules = array(
            'title' => array(
                'field' => 'title',
                'label' => 'lang:news_title_label',
                'rules' => 'trim|htmlspecialchars|required|max_length[100]|callback__check_title'
            ),
            'slug' => array(
                'field' => 'slug',
                'label' => 'lang:news_slug_label',
                'rules' => 'trim|required|alpha_dot_dash|max_length[100]|callback__check_slug'
            ),
            array(
                'field' => 'category_id',
                'label' => 'lang:news_category_label',
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'keywords',
                'label' => 'lang:global:keywords',
                'rules' => 'trim'
            ),
            array(
                'field' => 'intro',
                'label' => 'lang:news_intro_label',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'body',
                'label' => 'lang:news_content_label',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'type',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'status',
                'label' => 'lang:news_status_label',
                'rules' => 'trim|alpha'
            ),
            array(
                'field' => 'created_on',
                'label' => 'lang:news_date_label',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'created_on_hour',
                'label' => 'lang:news_created_hour',
                'rules' => 'trim|numeric|required'
            ),
            array(
                'field' => 'created_on_minute',
                'label' => 'lang:news_created_minute',
                'rules' => 'trim|numeric|required'
            ),
            array(
                'field' => 'comments_enabled',
                'label' => 'lang:news_comments_enabled_label',
                'rules' => 'trim|numeric'
            )
        );
    
        $this->data = new stdClass();
		// Date ranges for select boxes
		$this->data->hours = array_combine($hours = range(0, 23), $hours);
		$this->data->minutes = array_combine($minutes = range(0, 59), $minutes);

		$this->data->categories = $this->news_categories_m->dropdown('id', 'title');

		$this->template->set_partial('shortcuts', 'admin/partials/shortcuts');
	}

	/**
	 * Show all created news posts
	 * @access public
	 * @return void
	 */
	public function index()
	{
		//set the base/default where clause
		$base_where = array('show_future' => TRUE, 'status' => 'all');

		//add post values to base_where if f_module is posted
		$base_where = $this->input->post('f_category') ? $base_where + array('category' => $this->input->post('f_category')) : $base_where;

		$base_where['status'] = $this->input->post('f_status') ? $this->input->post('f_status') : $base_where['status'];

		$base_where = $this->input->post('f_keywords') ? $base_where + array('keywords' => $this->input->post('f_keywords')) : $base_where;

		// Create pagination links
		$total_rows = $this->news_m->count_by($base_where);
		$pagination = create_pagination('admin/news/index', $total_rows);

		// Using this data, get the relevant results
		$news = $this->news_m->limit($pagination['limit'])->get_many_by($base_where);

		//do we need to unset the layout because the request is ajax?
		$this->input->is_ajax_request() ? $this->template->set_layout(FALSE) : '';

		$this->template
			->title($this->module_details['name'])
			->append_js('admin/filter.js')
			->set('pagination', $pagination)
			->set('news', $news);

		$this->input->is_ajax_request() ? $this->template->build('admin/tables/posts', $this->data) : $this->template->build('admin/index', $this->data);

	}

	/**
	 * Create new post
	 * @access public
	 * @return void
	 */
	public function create()
	{
        $this->form_validation->set_rules($this->item_validation_rules);
		if ($this->input->post('created_on'))
		{
			$created_on = strtotime(sprintf('%s %s:%s', $this->input->post('created_on'), $this->input->post('created_on_hour'), $this->input->post('created_on_minute')));
		}

		else
		{
			$created_on = now();
		}

		if ($this->form_validation->run())
		{
			// They are trying to put this live
			if ($this->input->post('status') == 'live')
			{
				role_or_die('news', 'put_live');
			}

			$id = $this->news_m->insert(array(
				'title'				=> $this->input->post('title'),
				'slug'				=> $this->input->post('slug'),
				'category_id'		=> $this->input->post('category_id'),
				'keywords'			=> Keywords::process($this->input->post('keywords')),
				'intro'				=> $this->input->post('intro'),
				'body'				=> $this->input->post('body'),
				'status'			=> $this->input->post('status'),
				'created_on'		=> $created_on,
				'comments_enabled'	=> $this->input->post('comments_enabled'),
				'author_id'			=> $this->current_user->id,
				'type'				=> $this->input->post('type'),
				'parsed'			=> ($this->input->post('type') == 'markdown') ? parse_markdown($this->input->post('body')) : ''
			));

			if ($id)
			{
				$this->pyrocache->delete_all('news_m');
				$this->session->set_flashdata('success', sprintf($this->lang->line('news_post_add_success'), $this->input->post('title')));
			}
			else
			{
				$this->session->set_flashdata('error', $this->lang->line('news_post_add_error'));
			}

			// Redirect back to the form or main page
			$this->input->post('btnAction') == 'save_exit' ? redirect('admin/news') : redirect('admin/news/edit/' . $id);
		}
		else
		{
		    $post = new stdClass;
			// Go through all the known fields and get the post values
			foreach ($this->item_validation_rules as $key => $field)
			{
				$post->$field['field'] = set_value($field['field']);
			}
			$post->created_on = $created_on;
			// if it's a fresh new article lets show them the advanced editor
			if ($post->type == '') $post->type = 'wysiwyg-advanced';
		}

		$this->template
			->title($this->module_details['name'], lang('news_create_title'))
			->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
			->append_js('jquery/jquery.tagsinput.min.js')
			->append_js('news_form.js', 'news')
			->append_css('jquery/jquery.tagsinput.css')
			->set('post', $post)
			->build('admin/form');
	}

	/**
	 * Edit news post
	 *
	 * @access public
	 * @param int $id the ID of the news post to edit
	 * @return void
	 */
	public function edit($id = 0)
	{
		$id OR redirect('admin/news');

		$post = $this->news_m->get($id);
		$post->keywords = Keywords::get_string($post->keywords);

		// If we have a useful date, use it
		if ($this->input->post('created_on'))
		{
			$created_on = strtotime(sprintf('%s %s:%s', $this->input->post('created_on'), $this->input->post('created_on_hour'), $this->input->post('created_on_minute')));
		}

		else
		{
			$created_on = $post->created_on;
		}
		
		$this->form_validation->set_rules(array_merge($this->item_validation_rules, array(
			'title' => array(
				'field' => 'title',
				'label' => 'lang:news_title_label',
				'rules' => 'trim|htmlspecialchars|required|max_length[100]|callback__check_title['.$id.']'
			),
			'slug' => array(
				'field' => 'slug',
				'label' => 'lang:news_slug_label',
				'rules' => 'trim|required|alpha_dot_dash|max_length[100]|callback__check_slug['.$id.']'
			),
		)));
		
		if ($this->form_validation->run())
		{
			// They are trying to put this live
			if ($post->status != 'live' and $this->input->post('status') == 'live')
			{
				role_or_die('news', 'put_live');
			}

			$author_id = empty($post->display_name) ? $this->current_user->id : $post->author_id;

			$result = $this->news_m->update($id, array(
				'title'				=> $this->input->post('title'),
				'slug'				=> $this->input->post('slug'),
				'category_id'		=> $this->input->post('category_id'),
				'keywords'			=> Keywords::process($this->input->post('keywords')),
				'intro'				=> $this->input->post('intro'),
				'body'				=> $this->input->post('body'),
				'status'			=> $this->input->post('status'),
				'created_on'		=> $created_on,
				'comments_enabled'	=> (int) $this->input->post('comments_enabled'),
				'author_id'			=> $author_id,
				'type'				=> $this->input->post('type'),
				'parsed'			=> ($this->input->post('type') == 'markdown') ? parse_markdown($this->input->post('body')) : ''
			));
			
			if ($result)
			{
				$this->session->set_flashdata(array('success' => sprintf(lang('news_edit_success'), $this->input->post('title'))));

				// The twitter module is here, and enabled!
//				if ($this->settings->item('twitter_news') == 1 && ($post->status != 'live' && $this->input->post('status') == 'live'))
//				{
//					$url = shorten_url('news/'.$date[2].'/'.str_pad($date[1], 2, '0', STR_PAD_LEFT).'/'.url_title($this->input->post('title')));
//					$this->load->model('twitter/twitter_m');
//					if ( ! $this->twitter_m->update(sprintf($this->lang->line('news_twitter_posted'), $this->input->post('title'), $url)))
//					{
//						$this->session->set_flashdata('error', lang('news_twitter_error') . ": " . $this->twitter->last_error['error']);
//					}
//				}
			}
			
			else
			{
				$this->session->set_flashdata('error', $this->lang->line('news_edit_error'));
			}

			// Redirect back to the form or main page
			$this->input->post('btnAction') == 'save_exit' ? redirect('admin/news') : redirect('admin/news/edit/' . $id);
		}
        
		// Go through all the known fields and get the post values
		foreach ($this->item_validation_rules as $key => $field)
		{
			if (isset($_POST[$field['field']]))
			{
				$post['field'] = set_value($field['field']);
			}
		}

		$post->created_on = $created_on;
		
		$this->template
			->title($this->module_details['name'], sprintf(lang('news_edit_title'), $post->title))
			->append_metadata($this->load->view('fragments/wysiwyg', $this->data, TRUE))
			//->append_js('jquery/jquery.tagsinput.min.js')
			//->append_js('news_form.js')
			->append_css('jquery/jquery.tagsinput.css')
            ->set('categories', $this->data->categories)
			->set('post', $post)
			->build('admin/form');
	}

	/**
	 * Preview news post
	 * @access public
	 * @param int $id the ID of the news post to preview
	 * @return void
	 */
	public function preview($id = 0)
	{
		$post = $this->news_m->get($id);

		$this->template
				->set_layout('modal', 'admin')
				->set('post', $post)
				->build('admin/preview');
	}

	/**
	 * Helper method to determine what to do with selected items from form post
	 * @access public
	 * @return void
	 */
	public function action()
	{
		switch ($this->input->post('btnAction'))
		{
			case 'publish':
				role_or_die('news', 'put_live');
				$this->publish();
				break;
			
			case 'delete':
				role_or_die('news', 'delete_live');
				$this->delete();
				break;
			
			default:
				redirect('admin/news');
				break;
		}
	}

	/**
	 * Publish news post
	 * @access public
	 * @param int $id the ID of the news post to make public
	 * @return void
	 */
	public function publish($id = 0)
	{
		role_or_die('news', 'put_live');

		// Publish one
		$ids = ($id) ? array($id) : $this->input->post('action_to');

		if ( ! empty($ids))
		{
			// Go through the array of slugs to publish
			$post_titles = array();
			foreach ($ids as $id)
			{
				// Get the current page so we can grab the id too
				if ($post = $this->news_m->get($id))
				{
					$this->news_m->publish($id);

					// Wipe cache for this model, the content has changed
					$this->pyrocache->delete('news_m');
					$post_titles[] = $post->title;
				}
			}
		}

		// Some posts have been published
		if ( ! empty($post_titles))
		{
			// Only publishing one post
			if (count($post_titles) == 1)
			{
				$this->session->set_flashdata('success', sprintf($this->lang->line('news_publish_success'), $post_titles[0]));
			}
			// Publishing multiple posts
			else
			{
				$this->session->set_flashdata('success', sprintf($this->lang->line('news_mass_publish_success'), implode('", "', $post_titles)));
			}
		}
		// For some reason, none of them were published
		else
		{
			$this->session->set_flashdata('notice', $this->lang->line('news_publish_error'));
		}

		redirect('admin/news');
	}

	/**
	 * Delete news post
	 * @access public
	 * @param int $id the ID of the news post to delete
	 * @return void
	 */
	public function delete($id = 0)
	{
		// Delete one
		$ids = ($id) ? array($id) : $this->input->post('action_to');

		// Go through the array of slugs to delete
		if ( ! empty($ids))
		{
			$post_titles = array();
			foreach ($ids as $id)
			{
				// Get the current page so we can grab the id too
				if ($post = $this->news_m->get($id))
				{
					$this->news_m->delete($id);

					// Wipe cache for this model, the content has changed
					$this->pyrocache->delete('news_m');
					$post_titles[] = $post->title;
				}
			}
		}

		// Some pages have been deleted
		if ( ! empty($post_titles))
		{
			// Only deleting one page
			if (count($post_titles) == 1)
			{
				$this->session->set_flashdata('success', sprintf($this->lang->line('news_delete_success'), $post_titles[0]));
			}
			// Deleting multiple pages
			else
			{
				$this->session->set_flashdata('success', sprintf($this->lang->line('news_mass_delete_success'), implode('", "', $post_titles)));
			}
		}
		// For some reason, none of them were deleted
		else
		{
			$this->session->set_flashdata('notice', lang('news_delete_error'));
		}

		redirect('admin/news');
	}

	/**
	 * Callback method that checks the title of an post
	 * @access public
	 * @param string title The Title to check
	 * @return bool
	 */
	public function _check_title($title, $id = null)
	{
		$this->form_validation->set_message('_check_title', sprintf(lang('news_already_exist_error'), lang('news_title_label')));
		return $this->news_m->check_exists('title', $title, $id);			
	}
	
	/**
	 * Callback method that checks the slug of an post
	 * @access public
	 * @param string slug The Slug to check
	 * @return bool
	 */
	public function _check_slug($slug, $id = null)
	{
		$this->form_validation->set_message('_check_slug', sprintf(lang('news_already_exist_error'), lang('news_slug_label')));
		return $this->news_m->check_exists('slug', $slug, $id);
	}

	/**
	 * method to fetch filtered results for news list
	 * @access public
	 * @return void
	 */
	public function ajax_filter()
	{
		$category = $this->input->post('f_category');
		$status = $this->input->post('f_status');
		$keywords = $this->input->post('f_keywords');

		$post_data = array();

		if ($status == 'live' OR $status == 'draft')
		{
			$post_data['status'] = $status;
		}

		if ($category != 0)
		{
			$post_data['category_id'] = $category;
		}

		//keywords, lets explode them out if they exist
		if ($keywords)
		{
			$post_data['keywords'] = $keywords;
		}
		$results = $this->news_m->search($post_data);

		//set the layout to false and load the view
		$this->template
			->set_layout(FALSE)
			->set('news', $results)
			->build('admin/tables/posts');
	}
}
