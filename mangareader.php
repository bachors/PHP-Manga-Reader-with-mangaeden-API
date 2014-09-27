<?php
/******************************************************
* #### PHP Manga Reader (mangaeden API) ####
* Coded by Ican Bachors 2014.
* http://ibacor.com/labs/php-manga-reader-mangaeden-api/
* Updates will be posted to this site.
******************************************************/

	function bcr_daptar ($p){
		$json_url = 'http://www.mangaeden.com/api/list/0/?p='.$p;
		$json = file_get_contents($json_url);
		$hasil = json_decode($json);
		echo '<table>
				<thead>
					<tr>
						<th>Manga Title</th>
						<th>Views</th>
						<th>Latest Chapter</th>
					</tr>
				</thead>';
		foreach($hasil->manga as $thing)
		{
			if (empty($thing->ld))
			$thing->ld=1372059409.0;
			echo '<tr><td><a href="?i='.$thing->i.'">'.$thing->t.'</a></td><td> '.$thing->h.'</td><td> '.date('Y-m-d H:i:s',$thing->ld).'</td></tr>';
		}
		echo '</table><hr>';
		$pt = $p+1;
		echo '<center><p><a href="?p='.$pt.'">Next</a></p></center>';
	}

	function bcr_inpo ($i){
		$json_url = 'http://www.mangaeden.com/api/manga/'.$i.'/';
		$json = file_get_contents($json_url);
		$hasil = json_decode($json);
		echo '<h4> '.$hasil->title.'</h4><p><strong>Description:</strong> '.$hasil->description.'</p>';
		echo '<p><strong>Category:</strong> <em>';
		foreach($hasil->categories as $ktg)
		{
			echo $ktg.' ';
		}
		echo '</em></p><hr>';
		echo '<table>
				<thead>
					<tr>
						<th>Chapter list</th>
						<th>Date added</th>
					</tr>
				</thead>';
		foreach($hasil->chapters as $thing)
		{
			echo '<tr><td> <a href="?c='.$thing[3].'">Chapter <strong>'.$thing[0].'</strong></a></td><td> '.date('Y-m-d',$thing[1]).'</td></tr>';
		}
		echo '</table>';
	}

	function bcr_capter ($c,$cp){
		$json_url = 'http://www.mangaeden.com/api/chapter/'.$c.'/';
		$json = file_get_contents($json_url);
		$hasil = json_decode($json);
		echo '<center><form method="get" action="">';
		echo '<input type="hidden" name="c" value="'.$c.'"><em>Pages</em> <select name="cp">';
		foreach($hasil->images as $pp)
		{?>

			<option value="<?php echo $pp[0];?>" <?php if($pp[0]==$cp){echo'selected="selected"';} ?>><?php echo $pp[0];?></option>

		<?php
		}
		echo '</select> <input type="submit" value="GO"></form></center><br>';
		foreach($hasil->images as $thing)
		{
			if($thing[0]==$cp){
				echo '<img src="http://cdn.mangaeden.com/mangasimg/'.$thing[1].'" style="width:100%"><br>';
			}
		}
	}
							
	if(empty($_GET['c'])){
		if(empty($_GET['i'])){
			if(empty($_GET['p']))
			$_GET['p']=0;
			bcr_daptar ($_GET['p']);
		}else {
			bcr_inpo ($_GET['i']);
		}
	}else {
		if(empty($_GET['cp']))
		$_GET['cp']=0;
		bcr_capter ($_GET['c'],$_GET['cp']);
	}

?>
