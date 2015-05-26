<?php
namespace Appeldorff\Newsmanager;

interface DBObjectInterface
{
	public static function withId($id);
	public static function emptyObject();
}
