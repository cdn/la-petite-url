<?php

class le_petite_url {

	var $base_url = "http://your.website/";

	var $post_table = "win_games";
	var $post_id_field = "id";
	var $petite_table = "le_petite_urls";
	
	var $url_length = "5";
	var $use_lowercase = "yes";
	var $use_uppercase = "no";
	var $use_numbers = "no";
	
	function check_has_url($id)
	{
		$post_query = mysql_query("SELECT * FROM ".$this->petite_table." WHERE item_id = '".$id."'");
		
		//echo "SELECT * FROM ".$this->petite_table." WHERE id = '".$id."'";
	
		if(mysql_num_rows($post_query) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function le_petite_url_do_redirect()
	{
		
		$request = $_SERVER['REQUEST_URI'];
		$the_petite = trim($request);
		$the_petite = trim($the_petite,"/");
		
		$le_petite_url_split = spliti('/',$the_petite);
		
		$le_petite_url_use = count($le_petite_url_split) - 1;
		
		if(check_has_url($le_petite_url_split[$le_petite_url_use]))
		{
			$post_id_query = mysql_query("SELECT post_id FROM ".$this->petite_table." WHERE petite_url = '".$le_petite_url_split[$le_petite_url_use]."'");
			
			$post_id_array = mysql_fetch_array($post_id_query);
			
			$expires = date('D, d M Y G:i:s T',strtotime("+1 week"));
	
			header("Expires: ".$expires);
			header('Location: '.$base_url.$post_ud, true, 302);
		}
		else
		{
			// do stuff like normal
		}
	}

	function generate_url()
	{
		$n = $this->url_length;
		$le_petite_url_chars = "";
	
		if($this->use_lowercase == "yes")
		{
			$le_petite_url_chars = $le_petite_url_chars . "abcdefghijklmnopqrstuvwxyz";
		}
		if($this->use_uppercase == "yes")
		{
			$le_petite_url_chars = $le_petite_url_chars . "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		}
		if($this->use_numbers == "yes")
		{
			$le_petite_url_chars = $le_petite_url_chars . "0123456789";
		}
		
		for ($s = '', $i = 0, $z = strlen($a = $le_petite_url_chars)-1; $i != $n; $x = rand(0,$z), $s .= $a{$x}, $i++);
		return $s;
	}
	
	function make_url($item_id)
	{
		$good_url = "no";
		
		if($this->check_has_url($item_id) == false)
		{
			while($good_url == "no")
			{
				$string = $this->generate_url();
				$post_query = mysql_query("SELECT * FROM ".$this->petite_table." WHERE petite_url = ".$string."");
				if(!is_array($post_query))
				{
					$good_url = "yes";
					try {
						mysql_query("INSERT INTO ". $this->petite_table ." VALUES($item_id,'".mysql_real_escape_string($string)."')");
					}
					catch(Exception $e)
					{
						echo 'Caught exception: ',  $e->getMessage(), "\n";
					}
				}
			}
		}
	}

}

?>