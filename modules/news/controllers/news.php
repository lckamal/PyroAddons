<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class News extends Public_Controller
{
	public $data;
    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
		$this->data = new stdClass();
        $this->lang->load('news');
        $this->load->driver('Streams');
		$this->load->model(array('news/news_m', 'news/news_category_m'));
        $this->template->append_css('module::news.css');
    }
     /**
     * remap news to show singular, categorised or all news
     */

     public function _remap()
	 {
	 	$args = func_get_args();
		
		if($args[0] == 'index'){
			return $this->index();
		}
		if($args[0] == 'cat'){
			return $this->cat($args[1][0]);
		}
		else{
			return $this->index($args[0]);
		}
	 }
	/**
	 * show result listings with or without cat
	 */
    public function index($id = NULL)
    {
        $params = array(
            'stream' => 'newss',
            'namespace' => 'news',
            'paginate' => 'yes',
            'pag_segment' => 4
        );
		
    	$cat = $this->news_category_m->get($id);
		if($cat){
			$params['where'] = 'news_category_id = '.$cat->id;
			$template_title = $this->module_details['name']." &raquo; ".$cat->news_category_title;
    	}
		elseif(!empty($slug)){
    		return $this->view($slug);
		}
		if($id == NULL){
		$this->data->newss = $this->streams->entries->get_entries($params);
		$this->template->title(isset($template_title) ? $template_title : $this->module_details['name'])
                ->build('index', $this->data);
		}
		else{
        $this->data->newss = $this->news_m->get_single_news($id);
        $this->template->title(isset($template_title) ? $template_title : $this->module_details['name'])
                ->build('single_notice', $this->data);
        }
        //var_dump($this->data->newss);die();
        // Build the page
        
    }
     public function cat($id = NULL)
    {
        $params = array(
            'stream' => 'newss',
            'namespace' => 'news',
            'paginate' => 'yes',
            'pag_segment' => 4
        );
		
    	$cat = $this->news_category_m->get($id);
		if($cat){
			$params['where'] = 'news_category_id = '.$cat->id;
			$template_title = $this->module_details['name']." &raquo; ".$cat->news_category_title;
    	}

		$this->data->newss = $this->streams->entries->get_entries($params);
		$this->template->title(isset($template_title) ? $template_title : $this->module_details['name'])
                ->build('index', $this->data);
        
    }
	public function viewall($slug = NULL){
		
		//$this->data->newss = $this->streams->entries->get_entries($params);
		$this->data->newss = $this->news_m->get_all_updates();
		var_dump($this->data->newss);die();
		$this->template->title('News')
        	->build('index', $this->data);
	}
	/**
	 * show a news
	 */
	public function view($slug = NULL)
	{
		if($slug === NULL) show_404();
		
		$this->data->news = $this->news_m->where('status', 1)->get_by('slug', $slug);
		
		$this->template->title('News :: '.$this->data->news->name)
        	->build('single', $this->data);
	}
	

}

/* End of file news.php */
