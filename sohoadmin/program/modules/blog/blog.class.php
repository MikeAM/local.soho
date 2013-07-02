<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '' || $_REQUEST['_SESSION'] != '') { exit; }

#=====================================================================================
# Soholaunch(R) Site Management Tool
#
# Author:        Chris Neitzer
# Homepage:      http://www.soholaunch.com
# Release Notes: http://wiki.soholaunch.com
#
# This Script: Simple example module to illustrate how to create a new
# module and keep it's look consistent with the rest of the product
#=====================================================================================

error_reporting(E_PARSE);
session_start();

# Include core files
require_once("../../includes/product_gui.php");


class sohoBlog
{
	/*
	 * Format string for database insertion. 
	 *
	 * @params: A string
	 *
	 */
	function db_string_format($string)
	{
		if (!get_magic_quotes_gpc()) {
			return mysql_real_escape_string($string);
		} else {
			return $string;
		}
	}

	/* 
	 * Save a post.
	 *
	 * @params: an array containing a category, title, text, and comma seperated tags
	 */
	function saveEntry($pkg){

		$postCat = $this->db_string_format($pkg['del_cat']);
		$postTitle = $this->db_string_format($pkg['postTitle']);
		$postBody = $this->db_string_format($pkg['postBody']);
		$postTags = $this->db_string_format($pkg['postTags']);
		$postAuthor = $this->db_string_format($pkg['blog_author']);
		$postStatus = $this->db_string_format($pkg['live']);
		$allow_comments = $this->db_string_format($pkg['allow_comments']);
		$tags = $postTags;
		if ($postCat == "NULL")
		{
			throw new Exception('<b>Post Not Saved:</b> Please choose a category!');
		}
		if ($postTitle == "")
		{
			throw new Exception('<b>Post Not Saved:</b> Please enter a title!');
		}
		if ($postBody == "")
		{
			throw new Exception('<b>Post Not Saved:</b> Your post is empty!');
		}
		 
		$qry = "INSERT INTO blog_content (blog_category, blog_title, blog_data, blog_date, blog_tags, blog_author, timestamp, live, allow_comments) VALUES ('".$postCat."', '".$postTitle."', '".$postBody."', '".date("Y-m-d")."', '".$tags."', '".$postAuthor."', '".time()."', '".$postStatus."', '".$allow_comments."')";
		if (!mysql_query($qry))
		{
			throw new Exception("<b>MySQL Error: </b>".mysql_error());
		}
		return mysql_insert_id();
	}
	/* 
	 * Update a post.
	 *
	 * @params: an array containing a category, title, text, and comma seperated tags
	 */
	function updateEntry($pkg)
	{
		$id = $pkg['id'];
		$postCat = $this->db_string_format($pkg['del_cat']);
		$postTitle = $this->db_string_format($pkg['postTitle']);
		$postBody = $this->db_string_format($pkg['postBody']);
		$postTags = $this->db_string_format($pkg['postTags']);
		$postAuthor = $this->db_string_format($pkg['blog_author']);
		$postStatus = $this->db_string_format($pkg['live']);
		$allow_comments = $this->db_string_format($pkg['allow_comments']);
		$tags = $postTags;
		
		if ($postCat == "NULL")
		{
			throw new Exception('<b>Post Not Saved:</b> Please choose a category!');
		}
		if ($postTitle == "")
		{
			throw new Exception('<b>Post Not Saved:</b> Please enter a title!');
		}
		if ($postBody == "")
		{
			throw new Exception('<b>Post Not Saved:</b> Your post is empty!');
		}
			
		$qry = "update blog_content set blog_category='".$postCat."', blog_title='".$postTitle."', blog_data='".$postBody."', blog_tags='".$tags."', blog_author='".$postAuthor."', live='".$postStatus."', allow_comments='".$allow_comments."' WHERE prikey = '".$id."'";
		if (!mysql_query($qry))
		{
			throw new Exception("<b>MySQL Error: </b>".mysql_error());
		}
	}

	function addAuthor($array)
	{
		$firstname = $this->db_string_format($array['firstname']);
		$lastname = $this->db_string_format($array['lastname']);
		$email = $this->db_string_format($array['email']);
		$password = $this->db_string_format($array['password']);
		$passwordCheck = $this->db_string_format($array['passwordCheck']);

		if (!preg_match("/^([a-zA-Z0-9-_]+)$/", $firstname))
		{
			throw new Exception("Please enter a valid first name.");
		}
		if (!preg_match("/^([a-zA-Z0-9-_]+)$/", $lastname))
		{
			throw new Exception("Please enter a valid last name.");
		}
		if ($password != $passwordCheck)
		{
			throw new Exception("Your password does not match the confirmation password.");
		}
		if (!preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).*$/", $password))
		{
			throw new Exception("Your password must be atleast 8 characters long and include a lowercase letter, an uppercase letter, a number and a symbol.");
		}

		echo "EVERYTHING WAS GRWESAT!!!!";
	}
	function saveCategory($string)
	{
		$string = $this->db_string_format($string);
		
		$qry = "INSERT INTO blog_category (category_name) VALUES ('".$string."')";
		if(!mysql_query($qry))
		{
			throw new Exception('Error: '.mysql_error());
		}
	}
	function deleteCategory($id)
	{
		$qry = "DELETE FROM blog_category WHERE prikey = ".$id."";
		if (!mysql_query($qry))
		{
			throw new Exception('Error: '.mysql_error());
		}
	}
	function getCatIds()
	{
		$qry = "SELECT * FROM blog_category";
		$rez = mysql_query($qry);
		while ($result = mysql_fetch_assoc($rez))
		{
			$arr[] = $result;
		}

		return $arr;
		
	}
	function getCatName($id)
	{
		if ($id == "")
		{
			$result = "All Posts";
			return $result;
		}
		$qry = "SELECT category_name FROM blog_category WHERE prikey = '".$id."'";
		$rez = mysql_query($qry);
		$result = mysql_result($rez, 0);

		return $result;
	}

	function getAuthors()
	{
		$authors = array();
		$authors[] = "Webmaster";
		$qry = "SELECT OWNER_NAME, BFIRSTNAME, BLASTNAME, SFIRSTNAME, SLASTNAME FROM sec_users";
		$rez = mysql_query($qry);
		while ($result = mysql_fetch_assoc($rez))
		{
			if ($result['SFIRSTNAME'] != "")
			{
				$authors[] = $result['SFIRSTNAME']." ".$result['SLASTNAME'];
			}
			elseif ($result['BFIRSTNAME'] != "")
			{
				$authors[] = $result['BFIRSTNAME']." ".$result['BLASTNAME'];
			}
			elseif ($result['OWNER_NAME'] != "")
			{
				$authors[] = $result['OWNER_NAME'];
			}
			else
			{
				continue;
			}
		}
		return $authors;
	}
	function categoryCount()
	{
		$qry = "SELECT COUNT(*) FROM blog_category";
		$rez = mysql_query($qry);
		$result = mysql_result($rez, 0);

		return (int)$result;
	}

	function entryIds($id)
	{
		$qry = "SELECT prikey FROM blog_content WHERE blog_category = '".$id."'";
		$rez = mysql_query($qry);
		while ($result = mysql_fetch_assoc($rez))
		{
			$arr[] = $result;
		}

		return $arr;
	}

	function getAllIds()
	{
		$qry = "SELECT prikey FROM blog_content ORDER BY prikey DESC";
		$rez = mysql_query($qry);
		while ($result = mysql_fetch_assoc($rez))
		{
			$arr[] = $result;
		}

		return $arr;
	}
	/*
	 * Returns a select form element or an unordered list.
	 */
	function getCategories($list=FALSE)
	{
		$allCats = $this->getCatIds();
		if ($list == FALSE)
		{
			$selectForm = "<select id=\"del_cat\" class=\"text\" style=\"width: 200px;\" name=\"del_subj\">";
			for ($i = 0; $i < $this->categoryCount(); $i++)
			{
				$selectForm .= "	<option value=\"".$allCats[$i]['prikey']."\">".$allCats[$i]['category_name']."</option>";
			}
			$selectForm .= "</select>";

			return $selectForm;
		} else {
			$list = "<ul>";
			for ($i = 0; $i < $this->categoryCount(); $i++)
			{
				$list .= "	<li class=\"catNames\"><button type=\"button\" class=\"delImage\" />&nbsp;</button><a href=\"blog-entry.php?id=".$allCats[$i]['prikey']."\">".$allCats[$i]['category_name']."</a></li>";
			}
			$list .= "</ul>";

			return $list;
		}
	}

	function updateTitle($id, $title)
	{
		$title = $this->db_string_format($title);
		$qry = "UPDATE blog_content SET blog_title='".$title."' WHERE prikey = '".$id."'";
		if (!mysql_query($qry))
		{
			throw new Exception("ERROR: ".mysql_error());
		}

		return TRUE;
	}
}

class blogEntry extends sohoBlog
{
	public $id, $category, $title, $content, $timestamp, $tags;
	
	function __construct($id)
	{
		$this->id = $id;
		$qry = "SELECT * FROM blog_content WHERE prikey = '".$id."'";
		$rez = mysql_query($qry);
		$result = mysql_fetch_assoc($rez);

		$this->category = $result['blog_category'];
		$this->title = $result['blog_title'];
		$this->content = $result['blog_data'];
		$this->timestamp = $result['timestamp'];
		$this->tags = $result['blog_tags'];
		$this->blog_author = $result['blog_author'];
		$this->status_display = str_replace('publish', 'published', str_replace('hide', 'hidden', $result['live']));
		$this->status = $result['live'];
		$this->allow_comments = $result['allow_comments'];
		
		
		
	}
	function getId()
	{
		return $this->id;
	}
	

	function getCatId()
	{
		return $this->category;
	}

	function getContent()
	{
		return $this->content;
	}

	function getCategory()
	{
		return parent::getCatName($this->category);
	}

	function getTitle()
	{
		return $this->title;
	}

	function getTags()
	{
		return $this->tags;
	}
	
	function addTags($string)
	{
		$oldTags = $this->getTags();
		$newTags = $this->db_string_format($string);
		$tags = $oldTags.", ".$newTags;
		$this->saveTags($tags);
		return 0;
	}

	function deleteTag($tagNum)
	{
		$tmpArr = explode(",", $this->getTags());
		$newArr = array();
		for ($i = 0; $i < count($tmpArr); $i++)
		{
			if ($tagNum == $i)
			{
				continue;
			}
			$newArr[] = trim($tmpArr[$i]);
		}
		$tags = implode(",", $newArr);
		$this->saveTags($tags);
	}

	function saveTags($tags)
	{
		$tags = $this->db_string_format($tags);
		$qry = "UPDATE blog_content SET blog_tags = '".$tags."' WHERE prikey = '".$this->id."'";
		if (!mysql_query($qry))
		{
			echo "Error: ".mysql_error();
		}
	}

	function displayTags()
	{
		$tagsArr = explode(",", $this->getTags());
		if ( (count($tagsArr) == 1) && !preg_match("/[a-zA-Z0-9]+/", $tagsArr[0]))
		{
			return "<p class=\"blog_labels\">No Tags</p>";
		}
		$tags = "<ul>\n";
		for ($i = 0; $i < count($tagsArr); $i++)
		{
			if (!preg_match("/[a-zA-Z0-9]+/", $tagsArr[$i])){
				continue;
			}
			$tags .= "	<li id=\"tag".$i."".$this->id."".$this->getCatId()."\" class=\"tag\"><span class=\"tstart\">&nbsp;</span><span class=\"ttag\">".$tagsArr[$i]."</span><span class=\"tend\">&nbsp;<span class=\"removeTag\" onClick=\"deleteTag(".$i.", ".$this->id.");\">&nbsp;</span></span></li>\n";
		}
		$tags .= "</ul>\n";
		$tags .= "<div class=\"clear\"></div>\n";
		
		return $tags;
	}

	function destroyPost($id)
	{
		$qry = "DELETE FROM blog_content WHERE prikey = '".$id."'";
		if (!mysql_query($qry))
		{
			throw new Exception("Error: ".mysql_error());
		}
	}
	
	/*
	 * Returns a formatted date.
	 * @params $type 
	 * 	MDY - March/25/2009
	 * 	DMY - 25/March/2009
	 * 	YMD - 2009/March/25
	 * 	formal - The 25th of March, 2009
	 * 	semiFormal - March 25th, 2009
	 * 	full - Wed, 25 Mar 2009 00:00:00 -0400
	 */
	function getTimestamp($type=NULL)
	{
		$ts = $this->timestamp;
		switch($type)
		{
			case "MDY":
				return date("m/d/Y", $ts);
				break;
			case "DMY":
				return date("d/m/Y", $ts);
				break;
			case "YMD":
				return date("Y/m/d", $ts);
				break;
			case "formal":
				return "The ".date(jS, $ts)." of ".date("F, Y", $ts);
				break;
			case "semiFormal":
				return date("F jS, Y", $ts);
				break;
			case "full":
				return date("r", $ts);
				break;
			default:
				return date("m/d/Y", $ts);
		}
	}
}

?>
