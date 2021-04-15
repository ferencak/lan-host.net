<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use DB;
class LanguageController extends Controller
{

	private $lang;
	private $page;
	private $file;
	private $x = 0;

	public function __construct()
	{

		return $this;

	}

    /**
    * Set selected language
    *
    * @param string $language
    * @return $this
    */
	public function set($language)
	{

		$this->lang = $language;
		return $this;

	}

	/**
	* Set current page
	*
	* @param string $page
	* @return $this
	*/
	public function page($page)
	{

		if(File::exists('../resources/lang/' . $this->lang . '/' . $page . '.json')){
			$this->page = $page;
			$this->file = '../resources/lang/' . $this->lang . '/' . $this->page . '.json';
			return $this;
		} else {
			$this->page = $page;
			$this->file = '../resources/lang/cs/' . $this->page . '.json';
			return $this;
		}

	}

	/**
	* Get language from another file
	* 
	* @param string $page
	* @param string $path
	* @return value
	*/
	public function fromFile($page, $path)
	{

		if(File::exists('../resources/lang/' . $this->lang . '/' . $page . '.json')){
			return $this->get($path, '../resources/lang/' . $this->lang . '/' . $page . '.json');
		} else {
			return $this->get($path, '../resources/lang/cs/' . $page . '.json');
		}

	}

	/**
	* Get value from language pack using path
	* 
	* @param string $path
	* @param string $remoteFile | false
	* @return value
	*/
	public function get($path, $remoteFile = false)
	{

		if(file_exists((!$remoteFile) ? $this->file : $remoteFile)) {
		$file = file_get_contents( (!$remoteFile) ? $this->file : $remoteFile);
		} else {
			$this->x++;
			if($this->x == 3) {
			echo "<div class='alert alert-danger alert-dismissible fade show' style='background: #fff; border: 1px solid #bf2a2b21; color: #bf2a2b8a; border-left: 1px solid #bf2a2b21!important;'' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong> Nastala chyba!</strong><span style='font-style:italic'> Language pack ". explode('/', $this->file)[4] . " neexistuje </span></div>";
			}
			return;
		}
		$content = json_decode($file, true);

		$levels = explode("->", $path);
		$latestNode = $content;
		for ($level = 0; $level < count($levels); $level++) {

			if(isset($latestNode[$levels[$level]])){

				$latestNode = $latestNode[$levels[$level]];
			}
			else{
				if(isset(explode('/', $remoteFile)[4])){ 
				$this->get($path, '../resources/lang/cs/' . explode('/', $remoteFile)[4]); 
			} else {
				echo 'Slovo <span style="color:#6cc3f5;">'.$path.' ('.explode('/', $this->file)[4].')</span>  nenalezeno';
				return;
			}
			}

		}

    	if(DB::table('settings')->where("property", '=', "lang")->get()[0]->value == "1")
			return $latestNode;
		else
			return "────";

	}

}
