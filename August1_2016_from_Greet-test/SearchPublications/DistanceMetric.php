<?php

class DistanceMetric{
	
	function __construct(){}
	
	/*
	computes Damerau-Levenshtein distance between two words
	*/
	function DL_distance($a,$b)
	{
		return levenshtein($a, $b);//DAVID: for speed tests
		
		$d = array();
		//length of string a
		$len_a = strlen($a);
		//length of string b
		$len_b = strlen($b);
		$cost = 0;
		//Build the matrix of proximity between $a and $b
		for($i = 0;$i<=$len_a;$i++)
		{	
			$d[$i][0] = $i;
		}
		for($j = 0;$j<=$len_b;$j++)
		{
			$d[0][$j] = $j;
		}
		for($i = 1;$i<=$len_a;$i++)
		{
			for($j = 1;$j<=$len_b;$j++)
			{
				if(strcasecmp($a[$i-1],$b[$j-1]) == 0)
				{
					//if the letters are the same cost is set to 0
					$cost = 0;
				}
				else
				{
					$cost = 1;
				}
			
				$d[$i][$j] = min(
					$d[$i-1][$j] + 1,//deletion
					$d[$i][$j-1]+1,//insertion
					$d[$i-1][$j-1]+$cost);//substitution
				if($i > 1 && $j > 1 && (strcasecmp($a[$i],$b[$j-1]) == 0) && (strcasecmp($a[$i-1],$b[$j]) == 0))
				{
					$d[$i][$j] = min(
						$d[$i][$j],
						$d[$i-2][$j-2] + $cost);//transposition
				}
			}
		}
		return $d[$len_a][$len_b];
	}
	
	/*
	Creates the keywords from the title and authors of an article
	*/
	function gather_keywords($a)
	{
		$title = $this->multiexplode(array(":"," and ","for","the"," to ","1","2","3","4","5","6","7","8","9","0"," in ","from","."," of"," a ","through","This","their","And ","/","-","_","?","!","with","The"," at ","&","#"," ",",","(",")"),$a['title']);
		$authors = $this->multiexplode(array("#"," ",",","-","&","A.","B.","C.","D.","E.","F.","G.","H.","I.","J.","K.","L.","M.","N.","O.","P.","Q.","R.","S.","T.","U.","V.","W.","X.","Y.","Z.","A ","B ","C ","D ","E ","F ","G ","H ","I ","J ","K ","L ","M ","N ","O ","P ","Q ","R ","S ","T ","U ","V ","W ","X ","Y ","Z "),$a['author']);
		//$abstract = $this->multiexplode(array(":"," and ","for","the"," to ","1","2","3","4","5","6","7","8","9","0"," in ","from","."," of"," a ","through","This","their","And ","/","-","_","?","!","with","The"," at ","&","#"," ",",","(",")"),$a['abstract']);
		foreach($authors as $author)
		{
			array_push($title,$author);
		}
		$keywords = $title;
		/*foreach($abstract as $abst)
		{
			array_push($keywords,$abst);
		}*/
		$treatment = array();
		foreach($keywords as $keys)
		{
			if($keys != "")
			{
				array_push($treatment,$keys);
			}
		}
		$keywords = $treatment;
		$keywords = implode(";",$keywords);
		//echo "<br/>Keywords->".$keywords;
		return $keywords;
	}
	
	/*
	Works like php explode function with several delimiters
	*/
	function multiexplode($delimiters,$string)
	{
		$ready = str_replace($delimiters, $delimiters[0], $string);//Case-sensitive replacement
		$launch = explode($delimiters[0], $ready);
		return  $launch;
	}
	//added by QZ on Jan. 29, 2016
	function imultiexplode($delimiters,$string)
	{
		$ready = str_ireplace($delimiters, $delimiters[0], $string);//Case-insensitive replacement
		$launch = explode($delimiters[0], $ready);
		return  $launch;
	}
	
	/*
	Generates the keywords from files.xml, and save it to keywords_without_abstract.xml
	*/
	function generate_keywords_xml($a)//takes the xfiles list, use only to generate an xml with the keywords of all articles
	{
		$xmlstr = "<?xml version='1.0' encoding='UTF-8'?><dfiles></dfiles>";
		$dictionary = new SimpleXMLElement($xmlstr);
		
		foreach($a as $xfile)
		{
			//echo "<br/>Article: ".$xfile['title'];
			$dfile = $dictionary->addChild("dfile");
			$keywords = $this->gather_keywords($xfile);
			$dfile->addChild("keys",$xfile['key']);
			$dfile->addChild("keywords",$keywords);
			$dfile->addChild("relevance",0);
		}
		$dictionary->asXML("keywords_new_without_abstract.xml");
		echo "<br/>Keywords regenerated";
	}
	
	/*
	Find the max of a list
	*/
	function find_max($a)
	{
		$ind = 0;
		$a_init = $a[$ind];
		foreach($a_init as $key=> $value)
		{
			$key_relevance = $key;
			$max_relevance = $value;
		}
		foreach($a as $key_a=> $value_a)
		{
			$tab = $a[$key_a];
			foreach($tab as $key=> $value)
			{
				if((double)$value > (double)$max_relevance)
				{
				$max_relevance = $value;
					$key_relevance = $key;
					$ind = $key_a;
				}
			}
		}
		return array((string)$key_relevance,$ind);
	}
	
	/*
	Returns the keys of the most relevant file for the research
	*/
	function sort_by_relevance($a,$_nbr,$max_relevance,$srchstr,$opt)
	{
		$to_sort = array();
		foreach($a->dfiles->dfile as $dfile)
		{
			$inter = array((string)($dfile->keys)=>(double)($dfile->relevance));
			array_push($to_sort, $inter);//creates the array to sort
		}
		$sorted_list = array();
		foreach($to_sort as $key=>$value)
		{
			foreach($value as $key_v=>$value_v)
			{
				if($value_v==$max_relevance)
				{
					array_push($sorted_list,$key_v);//Add to the result all the results with a perfect match
					unset($to_sort[$key_v]);
				}
			}
		}
		if(sizeof($sorted_list)==0)//If there is no perfect match
		{
			//echo "<br/>No perfect match";
			if($_nbr==1)//only one word
			{
				$closer = "";
				foreach($a->dfiles->dfile as $dfile)
				{
					$keyw = $dfile->keywords;
					$words = explode(";",$keyw);
					foreach($words as $key=>$word)
					{
						$lev1 = levenshtein(strtolower($word),strtolower($srchstr)); //added by Qizhi on Dec. 23, 2015
						$lev2 = levenshtein(strtolower($closer),strtolower($srchstr)); //added by Qizhi on Dec. 23, 2015
						if( $lev1 <= 3-$opt && $lev1 < $lev2 ) //modifed by Qizhi on Dec. 23, 2015
						{
							if( strlen($word) >= 3 && strlen($word) >= strlen($srchstr) ) //added by Qizhi on Dec. 23, 2015
								$closer = $word;
							//echo "<br/>Closer: ".$closer;							
						}
					}
				}
				if($closer != "")
				{
					$closer = strtolower($closer);
					$scriptStr = "setVal('searchterm','" . $closer. "');findpubs('".$closer."',".$opt.");";//The script to launch, fill the form with the closest word and submit
					echo "Did you mean: <a href=\"javascript:;\" onclick=\"" .$scriptStr. "\">".strtolower((string)$closer). "</a> ?<br/><br/>";//Launch the script
				}
				else
				{
					echo "<br/>No match found for your search";
				}
			}
			else//more than one word in research
			{
				$closer_tab = array();
				$closer = "";
				$searching = explode(" ",$srchstr);
				foreach($searching as $researched)
				{
					unset($perfect);
					foreach($a->dfiles->dfile as $dfile)
					{
						$keyw = $dfile->keywords;
						$words = explode(";",$keyw);
						foreach($words as $key=>$word)
						{
							if(strcasecmp($word,$researched)==0)
							{
								$perfect = $word;	
							}
							else
							{
								$lev1 = levenshtein(strtolower($word),strtolower($researched)); //added by Qizhi on Dec. 23, 2015
								$lev2 = levenshtein(strtolower($closer),strtolower($researched)); //added by Qizhi on Dec. 23, 2015
								if( $lev1 <= 3-$opt && $lev1 < $lev2 ) //modifed by Qizhi on Dec. 23, 2015
								{
									if( strlen($word) >= 3 && strlen($word) >= strlen($researched) ) //added by Qizhi on Dec. 23, 2015
										$closer = $word;
									//echo "<br/>Closer: ".$closer;							
								}
							}							
						}
					}
					if(isset($perfect))
					{
						array_push($closer_tab,strtolower($perfect));
					}
					else
					{
						array_push($closer_tab,strtolower($closer));
					}
				}
				if(!empty($closer_tab))
				{
					if( !($closer_tab == $searching ) ) //avoid repeated suggesting-added by Qizhi on Dec. 23, 2015
					{
						$suggest = implode(" ",$closer_tab);
						$scriptStr = "setVal('searchterm','" . $suggest. "');findpubs('".$suggest."',".$opt.");";//The script to launch, fill the form with the closest word and submit
						echo "<br/>Did you mean: <a href=\"javascript:;\" onclick=\"" .$scriptStr. "\">".strtolower((string)$suggest). "</a> ?<br/>";//Launch the script
					}
				}
				else
				{
					echo "<br/>No match found for your research";
				}
			}			
			//Add the 3 closest that are not a perfect match
			$top=3;
			for($i = 1;$i<=$top;$i++)
			{
				$key_relevance = $this->find_max($to_sort);
				unset($to_sort[$key_relevance[key($key_relevance)+1]]);
				array_push($sorted_list,(string)$key_relevance[key($key_relevance)]);
			}
		}
		return $sorted_list;
	}
}

?>