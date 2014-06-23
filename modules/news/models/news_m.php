<?php defined('BASEPATH') OR exit('No direct script access allowed');

class News_m extends MY_Model {

	protected $_table = 'news';
    function __construct(){
        parent::__construct();
    }
	function get_all()
	{
		$this->db->select('news.*, news_categories.title AS category_title, news_categories.slug AS category_slug, profiles.display_name')
			->join('news_categories', 'news.category_id = news_categories.id', 'left')
			->join('profiles', 'profiles.user_id = news.author_id');

		$this->db->order_by('created_on', 'DESC');
        //return parent::get_all();
		return $this->db->get('news')->result();
	}

	function get($id)
	{
		return $this->db->select('news.*, profiles.display_name')
					->join('profiles', 'profiles.user_id = news.author_id')
					->where(array('news.id' => $id))
					->get('news')
					->row();
	}
	
	public function get_by($key = null, $value = null)
	{
		$this->db->select('news.*, profiles.display_name')
			->join('profiles', 'profiles.user_id = news.author_id');
			
		if (is_array($key))
		{
			$this->db->where($key);
		}
		else
		{
			$this->db->where($key, $value);
		}
        parent::get_by($key, $value);
		//return $this->db->get($this->_table)->row();
	}

	function get_many_by($params = array())
	{
		$this->load->helper('date');

		if (!empty($params['category']))
		{
			if (is_numeric($params['category']))
				$this->db->where('news_categories.id', $params['category']);
			else
				$this->db->where('news_categories.slug', $params['category']);
		}

		if (!empty($params['month']))
		{
			$this->db->where('MONTH(FROM_UNIXTIME(created_on))', $params['month']);
		}

		if (!empty($params['year']))
		{
			$this->db->where('YEAR(FROM_UNIXTIME(created_on))', $params['year']);
		}

		// Is a status set?
		if (!empty($params['status']))
		{
			// If it's all, then show whatever the status
			if ($params['status'] != 'all')
			{
				// Otherwise, show only the specific status
				$this->db->where('status', $params['status']);
			}
		}

		// Nothing mentioned, show live only (general frontend stuff)
		else
		{
			$this->db->where('status', 'live');
		}

		// By default, dont show future posts
		if (!isset($params['show_future']) || (isset($params['show_future']) && $params['show_future'] == FALSE))
		{
			$this->db->where('created_on <=', now());
		}

		// Limit the results based on 1 number or 2 (2nd is offset)
		if (isset($params['limit']) && is_array($params['limit']))
			$this->db->limit($params['limit'][0], $params['limit'][1]);
		elseif (isset($params['limit']))
			$this->db->limit($params['limit']);

		return $this->get_all();
	}
	
	public function count_tagged_by($tag, $params)
	{
		return $this->select('*')
			->from('news')
			->join('keywords_applied', 'keywords_applied.hash = news.keywords')
			->join('keywords', 'keywords.id = keywords_applied.keyword_id')
			->where('keywords.name', str_replace('-', ' ', $tag))
			->where($params)
			->count_all_results();
	}
	
	public function get_tagged_by($tag, $params)
	{
		return $this->db->select('news.*, news.title title, news.slug slug, news_categories.title category_title, news_categories.slug category_slug')
			->from('news')
			->join('keywords_applied', 'keywords_applied.hash = news.keywords')
			->join('keywords', 'keywords.id = keywords_applied.keyword_id')
			->join('news_categories', 'news_categories.id = news.category_id', 'left')
			->where('keywords.name', str_replace('-', ' ', $tag))
			->where($params)
			->get()
			->result();
	}

	function count_by($params = array())
	{
		$this->db->join('news_categories', 'news.category_id = news_categories.id', 'left');

		if (!empty($params['category']))
		{
			if (is_numeric($params['category']))
				$this->db->where('news_categories.id', $params['category']);
			else
				$this->db->where('news_categories.slug', $params['category']);
		}

		if (!empty($params['month']))
		{
			$this->db->where('MONTH(FROM_UNIXTIME(created_on))', $params['month']);
		}

		if (!empty($params['year']))
		{
			$this->db->where('YEAR(FROM_UNIXTIME(created_on))', $params['year']);
		}

		// Is a status set?
		if (!empty($params['status']))
		{
			// If it's all, then show whatever the status
			if ($params['status'] != 'all')
			{
				// Otherwise, show only the specific status
				$this->db->where('status', $params['status']);
			}
		}

		// Nothing mentioned, show live only (general frontend stuff)
		else
		{
			$this->db->where('status', 'live');
		}

		return $this->db->count_all_results('news');
	}

	function update($primary_value, $data, $skip_validation = false)
	{
	    $id = $primary_value;
		$input['updated_on'] = now();

		return parent::update($id, $data);
	}

	function publish($id = 0)
	{
		return parent::update($id, array('status' => 'live'));
	}

	// -- Archive ---------------------------------------------

	function get_archive_months()
	{
		$this->db->select('UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(t1.created_on), "%Y-%m-02")) AS `date`', FALSE);
		$this->db->from('news t1');
		$this->db->distinct();
		$this->db->select('(SELECT count(id) FROM ' . $this->db->dbprefix('news') . ' t2
							WHERE MONTH(FROM_UNIXTIME(t1.created_on)) = MONTH(FROM_UNIXTIME(t2.created_on))
								AND YEAR(FROM_UNIXTIME(t1.created_on)) = YEAR(FROM_UNIXTIME(t2.created_on))
								AND status = "live"
								AND created_on <= ' . now() . '
						   ) as post_count');

		$this->db->where('status', 'live');
		$this->db->where('created_on <=', now());
		$this->db->having('post_count >', 0);
		$this->db->order_by('t1.created_on DESC');
		$query = $this->db->get();

		return $query->result();
	}

	// DIRTY frontend functions. Move to views
	function get_news_fragment($params = array())
	{
		$this->load->helper('date');

		$this->db->where('status', 'live');
		$this->db->where('created_on <=', now());

		$string = '';
		$this->db->order_by('created_on', 'DESC');
		$this->db->limit(5);
		$query = $this->db->get('news');
		if ($query->num_rows() > 0)
		{
			$this->load->helper('text');
			foreach ($query->result() as $news)
			{
				$string .= '<p>' . anchor('news/' . date('Y/m') . '/' . $news->slug, $news->title) . '<br />' . strip_tags($news->intro) . '</p>';
			}
		}
		return $string;
	}

	function check_exists($field, $value = '', $id = 0)
	{
		if (is_array($field))
		{
			$params = $field;
			$id = $value;
		}
		else
		{
			$params[$field] = $value;
		}
		$params['id !='] = (int) $id;

		return parent::count_by($params) == 0;
	}

	/**
	 * Searches news posts based on supplied data array
	 * @param $data array
	 * @return array
	 */
	public function search($data = array())
	{
		if (array_key_exists('category_id', $data))
		{
			$this->db->where('category_id', $data['category_id']);
		}

		if (array_key_exists('status', $data))
		{
			$this->db->where('status', $data['status']);
		}

		if (array_key_exists('keywords', $data))
		{
			$matches = array();
			if (strstr($data['keywords'], '%'))
			{
				preg_match_all('/%.*?%/i', $data['keywords'], $matches);
			}

			if (!empty($matches[0]))
			{
				foreach ($matches[0] as $match)
				{
					$phrases[] = str_replace('%', '', $match);
				}
			}
			else
			{
				$temp_phrases = explode(' ', $data['keywords']);
				foreach ($temp_phrases as $phrase)
				{
					$phrases[] = str_replace('%', '', $phrase);
				}
			}

			$counter = 0;
			foreach ($phrases as $phrase)
			{
				if ($counter == 0)
				{
					$this->db->like('news.title', $phrase);
				}
				else
				{
					$this->db->or_like('news.title', $phrase);
				}

				$this->db->or_like('news.body', $phrase);
				$this->db->or_like('news.intro', $phrase);
				$this->db->or_like('profiles.display_name', $phrase);
				$counter++;
			}
		}
		return $this->get_all();
	}

}