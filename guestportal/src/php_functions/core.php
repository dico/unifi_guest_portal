<?php
	
	/**
		Output function for handler
	*/
	function output($result, $redir='')
	{
		global $ajax;

		if ($ajax) {
			header('Content-Type: application/json');
			echo json_encode($result);
		} else {
			if (isset($result['msg_id'])) $msgID = '&msg='.$result['msg_id'];
			else $msgID = '';

			if (!empty($redir)) {
				header('Location: ' . $redir);
			}

			if ($result['status'] == 'success') {
				if (empty($redir)) {
					header('Location: ' . stripMsg($_SERVER['HTTP_REFERER']) . $msgID);
				}
			} else {
				echo '<h1>'. __('An error occured') .'</h1>';
				echo __('Please try again. Report this problem, with the following data if the problem continues') . '.<br /><br />';
				echo '<pre>';
					print_r($result);
				echo '</pre>';
			}
		}

		exit();
	}



	function stripMsg($url) {
		$new_url = preg_replace('/&?msg=[^&]*/', '', $url);
		return $new_url;
	}



	function maskName($name)
	{
		if (!empty($name)) {
			$first_part = substr($name, 0,2);
			return $first_part.'********';
		} else {
			return '';
		}
	}




	function rpc($url)
	{
		$curl_handle=curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
		$query = curl_exec($curl_handle);
		curl_close($curl_handle);

		return $query;
	}
?>