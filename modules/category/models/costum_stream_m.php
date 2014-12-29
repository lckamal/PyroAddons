<?php defined('BASEPATH') or exit('No direct script access allowed');

class Costum_stream_m extends MY_Model
{
public function my_get_stream_data_2_count($stream, $stream_fields, $filter_data = array(),$myid)
	{

		$real_id = $myid;
		$this->load->config('streams');

		// -------------------------------------
		// Set Ordering
		// -------------------------------------

		// Query string API overrides all
		// Check if there is one now
		if ($this->input->get('order-'.$stream->stream_slug))
		{
			$this->db->order_by($this->input->get('order-'.$stream->stream_slug), $this->input->get('order-'.$stream->stream_slug) ? $this->input->get('order-'.$stream->stream_slug) : 'ASC');
		}
		elseif ($stream->sorting == 'title' and ($stream->title_column != '' and $this->db->field_exists($stream->title_column, $stream->stream_prefix.$stream->stream_slug)))
		{
			if ($stream->title_column != '' and $this->db->field_exists($stream->title_column, $stream->stream_prefix.$stream->stream_slug))
			{
				$this->db->order_by($stream->title_column, 'ASC');
			}
		}
		elseif ($stream->sorting == 'custom')
		{
			$this->db->order_by('ordering_count', 'ASC');
		}	
		else
		{
			$this->db->order_by('created', 'DESC');
		}

		// -------------------------------------
		// Filter results
		// -------------------------------------

		foreach ($filter_data as $filter)
		{
			$this->db->where($filter, null, false);
		}
		
		// -------------------------------------
		// Optional Limit
		// -------------------------------------
		$this->db->where('category_parent_id',$real_id);

		// -------------------------------------
		// Created By
		// -------------------------------------

		$this->db->select($stream->stream_prefix.$stream->stream_slug.'.*, '.$this->db->dbprefix('users').'.username as created_by_username, '.$this->db->dbprefix('users').'.id as created_by_user_id, '.$this->db->dbprefix('users').'.email as created_by_email');
		$this->db->join('users', 'users.id = '.$stream->stream_prefix.$stream->stream_slug.'.created_by', 'left');

		// -------------------------------------
		// Get Data
		// -------------------------------------
		
		$items = $this->db->get($stream->stream_prefix.$stream->stream_slug)->result();
		// -------------------------------------
		// Get Format Profile
		// -------------------------------------

		$stream_fields = $this->streams_m->get_stream_fields($stream->id);

		// -------------------------------------
		// Run formatting
		// -------------------------------------
		
		if (count($items) != 0)
		{
			$fields = new stdClass;
	
			foreach ($items as $id => $item)
			{
				$fields->$id = $this->row_m->format_row($item, $stream_fields, $stream);
			}
		}
		else
		{
			$fields = false;
		}
		
		return $fields;
	}


	// --------------------------------------------------------------------------
	
	/**
	 * Get data from a stream.
	 *
	 * Only really shown on the back end.
	 *
	 * @access	public
	 * @param	obj
	 * @param	obj
	 * @param	int
	 * @param	int
	 * @return 	obj
	 */
	public function my_get_stream_data_2($stream, $stream_fields, $limit = null, $offset = 0, $filter_data = array(),$myid)
	{
		$real_id = $myid;
		$this->load->config('streams');

		// -------------------------------------
		// Set Ordering
		// -------------------------------------

		// Query string API overrides all
		// Check if there is one now
		if ($this->input->get('order-'.$stream->stream_slug))
		{
			$this->db->order_by($this->input->get('order-'.$stream->stream_slug), $this->input->get('order-'.$stream->stream_slug) ? $this->input->get('order-'.$stream->stream_slug) : 'ASC');
		}
		elseif ($stream->sorting == 'title' and ($stream->title_column != '' and $this->db->field_exists($stream->title_column, $stream->stream_prefix.$stream->stream_slug)))
		{
			if ($stream->title_column != '' and $this->db->field_exists($stream->title_column, $stream->stream_prefix.$stream->stream_slug))
			{
				$this->db->order_by($stream->title_column, 'ASC');
			}
		}
		elseif ($stream->sorting == 'custom')
		{
			$this->db->order_by('ordering_count', 'ASC');
		}	
		else
		{
			$this->db->order_by('created', 'DESC');
		}

		// -------------------------------------
		// Filter results
		// -------------------------------------

		foreach ($filter_data as $filter)
		{
			$this->db->where($filter, null, false);
		}
		
		// -------------------------------------
		// Optional Limit
		// -------------------------------------
		$this->db->where('category_parent_id',$real_id);
		if (is_numeric($limit))
		{
			$this->db->limit($limit, $offset);
		}

		// -------------------------------------
		// Created By
		// -------------------------------------

		$this->db->select($stream->stream_prefix.$stream->stream_slug.'.*, '.$this->db->dbprefix('users').'.username as created_by_username, '.$this->db->dbprefix('users').'.id as created_by_user_id, '.$this->db->dbprefix('users').'.email as created_by_email');
		$this->db->join('users', 'users.id = '.$stream->stream_prefix.$stream->stream_slug.'.created_by', 'left');

		// -------------------------------------
		// Get Data
		// -------------------------------------
		
		$items = $this->db->get($stream->stream_prefix.$stream->stream_slug)->result();
		// -------------------------------------
		// Get Format Profile
		// -------------------------------------

		$stream_fields = $this->streams_m->get_stream_fields($stream->id);

		// -------------------------------------
		// Run formatting
		// -------------------------------------
		
		if (count($items) != 0)
		{
			$fields = new stdClass;
	
			foreach ($items as $id => $item)
			{
				$fields->$id = $this->row_m->format_row($item, $stream_fields, $stream);
			}
		}
		else
		{
			$fields = false;
		}
		
		return $fields;
	}



	public function my_get_stream_data($stream, $stream_fields, $limit = null, $offset = 0, $filter_data = array())
	{
		$this->load->config('streams');

		// -------------------------------------
		// Set Ordering
		// -------------------------------------

		// Query string API overrides all
		// Check if there is one now
		if ($this->input->get('order-'.$stream->stream_slug))
		{
			$this->db->order_by($this->input->get('order-'.$stream->stream_slug), $this->input->get('order-'.$stream->stream_slug) ? $this->input->get('order-'.$stream->stream_slug) : 'ASC');
		}
		elseif ($stream->sorting == 'title' and ($stream->title_column != '' and $this->db->field_exists($stream->title_column, $stream->stream_prefix.$stream->stream_slug)))
		{
			if ($stream->title_column != '' and $this->db->field_exists($stream->title_column, $stream->stream_prefix.$stream->stream_slug))
			{
				$this->db->order_by($stream->title_column, 'ASC');
			}
		}
		elseif ($stream->sorting == 'custom')
		{
			$this->db->order_by('ordering_count', 'ASC');
		}	
		else
		{
			$this->db->order_by('created', 'DESC');
		}

		// -------------------------------------
		// Filter results
		// -------------------------------------

		foreach ($filter_data as $filter)
		{
			$this->db->where($filter, null, false);
		}
		
		// -------------------------------------
		// Optional Limit
		// -------------------------------------
		$this->db->where('category_parent_id',NULL);
		if (is_numeric($limit))
		{
			$this->db->limit($limit, $offset);
		}

		// -------------------------------------
		// Created By
		// -------------------------------------

		$this->db->select($stream->stream_prefix.$stream->stream_slug.'.*, '.$this->db->dbprefix('users').'.username as created_by_username, '.$this->db->dbprefix('users').'.id as created_by_user_id, '.$this->db->dbprefix('users').'.email as created_by_email');
		$this->db->join('users', 'users.id = '.$stream->stream_prefix.$stream->stream_slug.'.created_by', 'left');

		// -------------------------------------
		// Get Data
		// -------------------------------------
		
		$items = $this->db->get($stream->stream_prefix.$stream->stream_slug)->result();
		// -------------------------------------
		// Get Format Profile
		// -------------------------------------

		$stream_fields = $this->streams_m->get_stream_fields($stream->id);

		// -------------------------------------
		// Run formatting
		// -------------------------------------
		
		if (count($items) != 0)
		{
			$fields = new stdClass;
	
			foreach ($items as $id => $item)
			{
				$fields->$id = $this->row_m->format_row($item, $stream_fields, $stream);
			}
		}
		else
		{
			$fields = false;
		}
		
		return $fields;
	}

}