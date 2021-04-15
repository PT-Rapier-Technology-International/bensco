<?php class Template{
	


    function rick_auto($view=null,$data=null){
        $ci =& get_instance();
        //$data['recaptcha'] = $ci->recaptcha->recaptcha_get_html();
        
		$ci->load->view('admin/template/bg_header', $data);
		$ci->load->view('admin/template/bg_left', $data);            
        $ci->load->view('admin/'.$view, $data);
        $ci->load->view('admin/template/bg_footer', $data);
    }

	function upload_picture_not_resize($path,$fileHigh,$fileTumb,$lastfile=null){
		$result = "";
		$filename = date('Ymdhis')."_".$this->rand(10).".png";
		
		if(!empty($fileHigh)){
			$imageHigh 		= str_replace('data:image/png;base64,', '', $fileHigh);
			$imageHigh 		= str_replace(' ', '+', $imageHigh);
			$imageHigh 		= base64_decode($imageHigh);
			$filePathHigh 	= $path.$filename;
			$uploadHigh 	= file_put_contents($filePathHigh, $imageHigh);
				
			if($uploadHigh){
				if(!empty($fileTumb)){
					$imageTumb 		= str_replace('data:image/png;base64,', '', $fileTumb);
					$imageTumb 		= str_replace(' ', '+', $imageTumb);
					$imageTumb 		= base64_decode($imageTumb);
					$filePathTumb 	= $path."resize/".$filename;
					$uploadTumb 	= file_put_contents($filePathTumb, $imageTumb);
				}
				
				if($lastfile != null){
					@unlink($path.$lastfile);
					@unlink($path."resize/".$lastfile);
				}
				
				$result = $filename;
			}else{
				$result = 'error';
			}
		}
		
        return $result;
    }

	function rand($length){
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return $key;
    }

    function print2pdf($title='',$content='')
	{
		$ci =& get_instance();
		$ci->load->helper('pdf_helper');
		
		$data['title']  	= $title;
		$data['content']	= $content;
	    $ci->load->view('pdfexport', $data);
	}

	function xTimeAgo ($oldTime, $newTime) {
        $timeCalc = strtotime($newTime) - strtotime($oldTime);       
        $timeCalc = round($timeCalc/60/60);
        return $timeCalc;
    }

    function xTimeAgoDesc ($oldTime, $newTime) {
        $timeCalc = strtotime($newTime)-strtotime($oldTime);
        if($timeCalc > (30 * 24 * 60 * 60)) {$timeCalc = round($timeCalc/30/24/60/60) . " bulan yang lalu";}
        elseif ($timeCalc > (60*60*24)) {$timeCalc = round($timeCalc/60/60/24) . " hari yang lalu";}
        else if ($timeCalc > (60*60)) {$timeCalc = round($timeCalc/60/60) . " jam yang lalu";}
        else if ($timeCalc > 60) {$timeCalc = round($timeCalc/60) . " menit yang lalu";}
        else if ($timeCalc > 0) {$timeCalc .= " detik yang lalu";}

        return $timeCalc;
    }

 		function paging1($pg,$uri,$url,$limit){
        $ci =& get_instance();
        $pg=$pg;
		
		$config['base_url'] = base_url($url);
		$config['total_rows'] = $pg->num_rows();
		$config['per_page']=$limit;
		$config['uri_segment']=$uri;
		$config['full_tag_open']="<ul class='pagination'>";
		$config['full_tag_close']='</ul>';
		
		$config['num_tag_open'] = "<li class='waves-effect'>";
		$config['num_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><a href='javascript:void(0);'>";
		$config['cur_tag_close'] = "</a></li>";
		$config['first_link']="<li class='waves-effect'>First</li>";
		$config['last_link']="<li class='waves-effect'>Last</li>";
		$config['next_link']="<li class='waves-effect'><i class='mdi-navigation-chevron-right'></i></li>";
		$config['prev_link']="<li class='waves-effect'><i class='mdi-navigation-chevron-left'></i></li>";
		
		$ci->pagination->initialize($config);
		return $ci->pagination->create_links();
    }
	
	function paging2($pg,$uri,$url,$limit){
        $ci =& get_instance();
        $pg=$pg;
		
		$config['base_url'] = base_url($url);
		$config['total_rows'] = $pg->num_rows();
		$config['per_page']=$limit;
		$config['uri_segment']=$uri;
		$config['full_tag_open']="<ul class='pagination pagination-sm no-margin pull-right'>";
		$config['full_tag_close']="</ul>";
		$config['uri_segment']		= $uri;
		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><span>";
		$config['cur_tag_lose'] = "</span></li>";
		$config['first_link']		= "First";
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['next_link']		= "&raquo;";
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_link']		= "Last";
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link']		= "&laquo;";
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		
		$ci->pagination->initialize($config);
		return $ci->pagination->create_links();
    }

    function paging4($pg,$uri,$url,$limit){
        $ci =& get_instance();
        $pg=$pg;
		
		$config['base_url'] = base_url($url);
		$config['total_rows'] = $pg->num_rows();
		$config['per_page']=$limit;
		$config['uri_segment']=$uri;
		$config['full_tag_open']="<ul id='pagination-4' class='pagination pagination-sm no-margin pull-right'>";
		$config['full_tag_close']="</ul>";
		$config['uri_segment']		= $uri;
		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li class='active'><span>";
		$config['cur_tag_lose'] = "</span></li>";
		$config['first_link']		= "First";
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['next_link']		= "&raquo;";
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_link']		= "Last";
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['prev_link']		= "&laquo;";
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		
		$ci->pagination->initialize($config);
		return $ci->pagination->create_links();
    }
    
	function paging3($pg,$uri,$url,$limit){
        $ci =& get_instance();
        $pg=$pg;
		
		$config['base_url'] = base_url($url);
		$config['total_rows'] = $pg->num_rows();
		$config['per_page']=$limit;
		$config['uri_segment']=$uri;
		$config['full_tag_open']="<ul class='right'>";
		$config['full_tag_close']="</ul>";
		$config['uri_segment']		= $uri;
		$config['num_tag_open'] = "<li id='paging'>";
		$config['num_tag_close'] = "</li>";
		$config['cur_tag_open'] = "<li id='paging' class='active'><span> ";
		$config['cur_tag_lose'] = "</span></li>";
		$config['first_link']		= "First";
		$config['first_tag_open'] = "<li id='paging'>";
		$config['first_tag_close'] = '</li>';
		$config['next_link']		= "&raquo;";
		$config['next_tag_open'] = "<li id='paging'>";
		$config['next_tag_close'] = '</li>';
		$config['last_link']		= "Last";
		$config['last_tag_open'] = "<li id='paging'>";
		$config['last_tag_close'] = '</li>';
		$config['prev_link']		= "&laquo;";
		$config['prev_tag_open'] = "<li id='paging'>";
		$config['prev_tag_close'] = '</li>';
		
		$ci->pagination->initialize($config);
		return $ci->pagination->create_links();
    } 
}
?>