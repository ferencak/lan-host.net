<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    
	public function list($limit)
	{

		return json_decode(\App\Blog::select(['id', 'data'])->limit($limit)->get(), true);

	}

	public function get($id)
	{

		return json_decode(\App\Blog::select('*')->where('id', '=', $id)->get()[0]);

	}

	public function data($id, $data)
	{

		return json_decode($this->get($id)->data, true)['blog'][$data];

	}

}
