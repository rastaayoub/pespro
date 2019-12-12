<?
function GetLinkHref($pagenum)
{
	$queryString = $_SERVER['QUERY_STRING'];
	
	$pattern = array('/p=[^&]*&?/', '/&$/');
	$replace = array('', '');
	$queryString = preg_replace($pattern, $replace, $queryString);
	$queryString = str_replace('&', '&amp;', $queryString);
	
	if (!empty($queryString))
	{
		$queryString.= '&amp;';
	}
	
	return '?'.$queryString.'p='.$pagenum;
}

$pagination = '';
$lastpage = ceil($total_pages/$limit);
$lpm1 = $lastpage - 1;
if($lastpage > 1){
	if ($lastpage < 6)
	{	
		for ($counter = 1; $counter <= $lastpage; $counter++)
		{
			if ($counter == $pagina)
				$pagination.= '<a class="paginate_active">'.$counter.'</a>';
			else
				$pagination.= '<a class="paginate_button" href="'.GetLinkHref($counter).'">'.$counter.'</a>';					
		}
	}elseif($lastpage > 6)
	{
		if($pagina < 5)		
		{
			for ($counter = 1; $counter < 5; $counter++)
			{
				if ($counter == $pagina)
					$pagination.= '<a class="paginate_active">'.$counter.'</a>';
				else
					$pagination.= '<a class="paginate_button" href="'.GetLinkHref($counter).'">'.$counter.'</a>';					
			}
			$pagination.= '<a class="paginate_active">...</a>';
			$pagination.= '<a class="paginate_button" href="'.GetLinkHref($lpm1).'">'.$lpm1.'</a>';
			$pagination.= '<a class="paginate_button" href="'.GetLinkHref($lastpage).'">'.$lastpage.'</a>';		
		}
		elseif($lastpage - 5 > $pagina && $pagina > ($adjacents * 2))
		{
			$pagination.= '<a class="paginate_button" href="'.GetLinkHref('1').'">1</a>';
			$pagination.= '<a class="paginate_button" href="'.GetLinkHref('2').'">2</a>';
			$pagination.= '<a class="paginate_active">...</a>';
			for ($counter = $pagina - $adjacents; $counter <= $pagina + $adjacents; $counter++)
			{
				if ($counter == $pagina)
					$pagination.= '<a class="paginate_active">'.$counter.'</a>';
				else
					$pagination.= '<a class="paginate_button" href="'.GetLinkHref($counter).'">'.$counter.'</a>';					
			}
			$pagination.= '<a class="paginate_active">...</a>';
			$pagination.= '<a class="paginate_button" href="'.GetLinkHref($lpm1).'">'.$lpm1.'</a>';
			$pagination.= '<a class="paginate_button" href="'.GetLinkHref($lastpage).'">'.$lastpage.'</a>';		
		}
		else
		{
			$pagination.= '<a class="paginate_button" href="'.GetLinkHref('1').'">1</a>';
			$pagination.= '<a class="paginate_button" href="'.GetLinkHref('2').'">2</a>';
			$pagination.= '<a class="paginate_active">...</a>';
			for ($counter = $lastpage - 5; $counter <= $lastpage; $counter++)
			{
				if ($counter == $pagina)
					$pagination.= '<a class="paginate_active">'.$counter.'</a>';
				else
					$pagination.= '<a class="paginate_button" href="'.GetLinkHref($counter).'">'.$counter.'</a>';					
			}
		}
	}
}
?>