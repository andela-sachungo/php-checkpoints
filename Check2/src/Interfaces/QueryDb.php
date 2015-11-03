<?php

namespace Stacey\Potato\Interfaces;

/**
* An interface for the Model class
*
* @author Stacey Achungo
*/
interface QueryDB 
{
	public static function getAll();
	public static function find($id);
	public function save();
	public static function destroy($id);
}