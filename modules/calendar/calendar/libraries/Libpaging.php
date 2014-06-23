<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Libpaging
*
* Author: Eko muhammad isa
* 		  ekoisa@gmail.com
*         @eko_isa
*
* Location: http://www.eNotes.web.id/
*
* Created:  30 September 2011
*
* Description:  Alternative paging system PyroCMS
*
*/

class Libpaging
{
	/*
	 * 
	 * $param = array('page', // current page
	 * 'maxrow', // total record
	 * 'pagerow', // record per page
	 * 'wide', // wide paging >>>>  2 (1,2,3,4,5); 3 (1,2,3,4,5,6,7), .....
	 * 'url', // http://localhost/path1/path2/%s/path3  --- %s as paging page position
	 * 'name', // name for jquery ajax command
	 * )
	 * 
	 * */
	 
	public function _paging($param = array()){
		if(!isset($param)){
		return;
		}
		if(!isset($param['url'])){
		return;
		}
		
		// jquery ajax need name
		if(isset($param['name'])){
			$ax_name = ' name="'.$param['name'].'" ';
		}else{
			$ax_name = '';
		}
		
		$url_paging = trim($param['url']);
		
		$retpage = array();
		$retpage['curpage'] = $param['page'];
		$max_pages = ceil($param['maxrow'] / $param['pagerow']);
        if($param['page'] <= 0){
            $param['page'] = 1;
        }
		$retpage['start_row'] = ($param['page']*$param['pagerow'])-($param['pagerow']);
		$retpage['end_row'] = $retpage['start_row']+$param['pagerow'];
		$retpage['count_row'] = $param['pagerow'];
		if($retpage['end_row'] > $param['maxrow']){
			$retpage['end_row'] = $param['maxrow'];
		}
		
		$end_link = $retpage['curpage'] + $param['wide'];
		
		
		if($retpage['curpage'] - $param['wide']>0){
			$start_link = $retpage['curpage'] - $param['wide'];
		}else{
			$start_link = 1;
		}
		if($end_link > $max_pages){
			$end_link = $max_pages;
		}
		
		
		$retval = '';
		$retval .= '<p class="pagination">';
		
        
 		$last_k = 0;
 		if($start_link > 1){
 				$retval .= '<span><a '.$ax_name.' href="'.sprintf($url_paging, 1).'">1</a></span>';
 				$last_k = 1;
 		}
 		
 		if($start_link > 10){
 				$retval .= '<span><a '.$ax_name.' href="'.sprintf($url_paging, 10).'">10</a></span>';
 				$last_k = 10;
 		}
 		if($start_link > 20){
 				$retval .= '<span><a '.$ax_name.' href="'.sprintf($url_paging, 20).'">10</a></span>';
 				$last_k = 20;
 		}
		
 		for($k=$start_link; $k < $end_link+1; $k++){
 			if($k != $retpage['curpage']){
 				$retval .= '<span><a '.$ax_name.' href="'.sprintf($url_paging, $k).'">'.$k.'</a></span>';
 			}else{
 				$retval .= '<span class="current"><a href="#" onclick="javascript:return false;">'.$k.'</a></span>';
 			}
 			$last_k = $k;
 		}
		
 		
  		if($max_pages > $end_link + 10){
  			$k=$end_link+10;
            $retval .= '<span><a '.$ax_name.' href="'.sprintf($url_paging, $k).'">'.$k.'</a></span>';
            $last_k = $k;
  		}
        
  		if($max_pages > $last_k + 10){
  			$k=$last_k+10;
            $retval .= '<span><a '.$ax_name.' href="'.sprintf($url_paging, $k).'">'.$k.'</a></span>';
            $last_k = $k;
  		}
		
		if($last_k < $max_pages){
			$retval .= '<span><a '.$ax_name.' href="'.sprintf($url_paging, $max_pages).'">'.$max_pages.'</a></span>';
		}
		
        
		$retval .= '</p>';
		$retpage['links'] = $retval;
		
		$retpage['limit'] = array($param['pagerow'], $retpage['start_row']);
		
		return $retpage;
	}
	
}
