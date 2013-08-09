<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class News extends Public_Controller
{
    private $data;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('news_m');
		$this->load->model('news_categories_m');
		$this->load->model('comments/comment_m');
		$this->load->library(array('keywords/keywords'));
		$this->lang->load('news');
        
        $this->data = new stdClass;
	}

	// news/page/x also routes here
	public function index()
	{
		$this->data->pagination = create_pagination('news/page', $this->news_m->count_by(array('status' => 'live')), NULL, 3);
		$this->data->news = $this->news_m->limit($this->data->pagination['limit'])->get_many_by(array('status' => 'live'));

		// Set meta description based on post titles
		$meta = $this->_posts_metadata($this->data->news);
		
		foreach ($this->data->news AS &$post)
		{
			$post->keywords = Keywords::get_string($post->keywords, 'news/tagged');
		}

		$this->template
			->title($this->module_details['name'])
			->set_breadcrumb( lang('news_news_title'))
			->set_metadata('description', $meta['description'])
			->set_metadata('keywords', $meta['keywords'])
			->build('index', $this->data);
	}

	public function category($slug = '')
	{
		$slug OR redirect('news');

		// Get category data
		$category = $this->news_categories_m->get_by('slug', $slug) OR show_404();

		// Count total news posts and work out how many pages exist
		$pagination = create_pagination('news/category/'.$slug, $this->news_m->count_by(array(
			'category'=> $slug,
			'status' => 'live'
		)), NULL, 4);

		// Get the current page of news posts
		$news = $this->news_m->limit($pagination['limit'])->get_many_by(array(
			'category'=> $slug,
			'status' => 'live'
		));

		// Set meta description based on post titles
		$meta = $this->_posts_metadata($news);
		
		foreach ($news AS &$post)
		{
			$post->keywords = Keywords::get_string($post->keywords, 'news/tagged');
		}

		// Build the page
		$this->template->title($this->module_details['name'], $category->title )
			->set_metadata('description', $category->title.'. '.$meta['description'] )
			->set_metadata('keywords', $category->title )
			->set_breadcrumb( lang('news_news_title'), 'news')
			->set_breadcrumb( $category->title )
			->set('news', $news)
			->set('category', $category)
			->set('pagination', $pagination)
			->build('category', $this->data );
	}

	public function archive($year = NULL, $month = '01')
	{
		$year OR $year = date('Y');
		$month_date = new DateTime($year.'-'.$month.'-01');
		$this->data->pagination = create_pagination('news/archive/'.$year.'/'.$month, $this->news_m->count_by(array('year'=>$year,'month'=>$month)), NULL, 5);
		$this->data->news = $this->news_m->limit($this->data->pagination['limit'])->get_many_by(array('year'=> $year,'month'=> $month));
		$this->data->month_year = format_date($month_date->format('U'), lang('news_archive_date_format'));

		// Set meta description based on post titles
		$meta = $this->_posts_metadata($this->data->news);
		
		foreach ($this->data->news AS &$post)
		{
			$post->keywords = Keywords::get_string($post->keywords, 'news/tagged');
		}

		$this->template->title( $this->data->month_year, $this->lang->line('news_archive_title'), $this->lang->line('news_news_title'))
			->set_metadata('description', $this->data->month_year.'. '.$meta['description'])
			->set_metadata('keywords', $this->data->month_year.', '.$meta['keywords'])
			->set_breadcrumb($this->lang->line('news_news_title'), 'news')
			->set_breadcrumb($this->lang->line('news_archive_title').': '.format_date($month_date->format('U'), lang('news_archive_date_format')))
			->build('archive', $this->data);
	}

	// Public: View a post
	public function view($slug = '')
	{
		if ( ! $slug or ! $post = $this->news_m->get_by('slug', $slug))
		{
			redirect('news');
		}

		if ($post->status != 'live' && ! $this->ion_auth->is_admin())
		{
			redirect('news');
		}
		
		// if it uses markdown then display the parsed version
		if ($post->type == 'markdown')
		{
			$post->body = $post->parsed;
		}

		// IF this post uses a category, grab it
		if ($post->category_id && ($category = $this->news_categories_m->get($post->category_id)))
		{
			$post->category = $category;
		}

		// Set some defaults
		else
		{
			$post->category->id		= 0;
			$post->category->slug	= '';
			$post->category->title	= '';
		}

		$this->session->set_flashdata(array('referrer' => $this->uri->uri_string));

		$this->template->title($post->title, lang('news_news_title'))
			->set_metadata('description', $post->intro)
			->set_metadata('keywords', implode(', ', Keywords::get_array($post->keywords)))
			->set_breadcrumb(lang('news_news_title'), 'news');

		if ($post->category->id > 0)
		{
			$this->template->set_breadcrumb($post->category->title, 'news/category/'.$post->category->slug);
		}
		
		$post->keywords = Keywords::get_string($post->keywords, 'news/tagged');

		$this->template
			->set_breadcrumb($post->title)
			->set('post', $post)
			->build('view', $this->data);
	}
	
	public function tagged($tag = '')
	{
		$tag OR redirect('news');

		// Count total news posts and work out how many pages exist
		$pagination = create_pagination('news/tagged/'.$tag, $this->news_m->count_tagged_by($tag, array(
			'status' => 'live'
		)), NULL, 4);

		// Get the current page of news posts
		$news = $this->news_m->limit($pagination['limit'])->get_tagged_by($tag, array(
			'status' => 'live'
		));
		
		foreach ($news AS &$post)
		{
			$post->keywords = Keywords::get_string($post->keywords, 'news/tagged');
		}

		// Set meta description based on post titles
		$meta = $this->_posts_metadata($news);
		
		$name = str_replace('-', ' ', $tag);

		// Build the page
		$this->template->title($this->module_details['name'], lang('news_tagged_label').': '.$name )
			->set_metadata('description', lang('news_tagged_label').': '.$name.'. '.$meta['description'] )
			->set_metadata('keywords', $name )
			->set_breadcrumb( lang('news_news_title'), 'news')
			->set_breadcrumb( lang('news_tagged_label').': '.$name )
			->set('news', $news)
			->set('tag', $tag)
			->set('pagination', $pagination)
			->build('tagged', $this->data );
	}

	// Private methods not used for display
	private function _posts_metadata(&$posts = array())
	{
		$keywords = array();
		$description = array();

		// Loop through posts and use titles for meta description
		if(!empty($posts))
		{
			foreach($posts as &$post)
			{
				if($post->category_title)
				{
					$keywords[$post->category_id] = $post->category_title .', '. $post->category_slug;
				}
				$description[] = $post->title;
			}
		}

		return array(
			'keywords' => implode(', ', $keywords),
			'description' => implode(', ', $description)
		);
	}
}
