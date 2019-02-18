<?php
/**
 * Created by PhpStorm.
 * User: Bautista
 * Date: 17/2/2019
 * Time: 22:37
 */

namespace FrameApi\Controllers;


class CategoriesController extends MainController
{
	public function __construct()
	{
		$this->model = 'FrameApi\Models\Category';
	}
}