<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * News Plugin
 *
 * Create lists of posts
 *
 * @package		PyroCMS
 * @author		PyroCMS Dev Team
 * @copyright	Copyright (c) 2008 - 2011, PyroCMS
 *
 */
class Plugin_News extends Plugin
{
	/**
	 * News List
	 *
	 * Creates a list of news posts
	 *
	 * Usage:
	 * {{ news:posts order-by="title" limit="5" }}
	 *		<h2>{{ title }}</h2>
	 *		<p> {{ body }} </p>
	 * {{ /news:posts }}
	 *
	 * @param	array
	 * @return	array
	 */
	public function posts()
	{
		$limit		= $this->attribute('limit', 10);
		$category	= $this->attribute('category');
		$order_by 	= $this->attribute('order-by', 'created_on');
													//deprecated
		$order_dir	= $this->attribute('order-dir', $this->attribute('order', 'ASC'));

		if ($category)
		{
			$this->db->where('news_categories.' . (is_numeric($category) ? 'id' : 'slug'), $category);
		}

		$posts = $this->db
			->select('news.*')
			->select('news_categories.title as category_title, news_categories.slug as category_slug')
			->select('p.display_name as author_name')
			->where('status', 'live')
			->where('created_on <=', now())
			->join('news_categories', 'news.category_id = news_categories.id', 'left')
			->join('profiles p', 'news.author_id = p.user_id', 'left')
			->order_by('news.' . $order_by, $order_dir)
			->limit($limit)
			->get('news')
			->result();

		foreach ($posts as &$post)
		{
			$post->url = site_url('news/'.date('Y', $post->created_on).'/'.date('m', $post->created_on).'/'.$post->slug);
		}
		
		return $posts;
	}
}

/* End of file plugin.php */