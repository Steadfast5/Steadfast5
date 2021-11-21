<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____  
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \ 
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/ 
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_| 
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace pocketmine\world;

use pocketmine\math\Vector3;
use pocketmine\utils\WorldException;

class Position extends Vector3{

	/** @var World */
	public $world = null;

	/**
	 * @param int   $x
	 * @param int   $y
	 * @param int   $z
	 * @param World $world
	 */
	public function __construct($x = 0, $y = 0, $z = 0, World $World = null){
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
		$this->world = $world;
	}

	public static function fromObject(Vector3 $pos, World $world = null){
		return new Position($pos->x, $pos->y, $pos->z, $world);
	}

	/**
	 * @return World
	 */
	public function getWorld(){
		return $this->world;
	}

	public function setWorld(World $world){
		$this->world = $world;
		return $this;
	}

	/**
	 * Checks if this object has a valid reference to a World
	 *
	 * @return bool
	 */
	public function isValid(){
		return $this->world !== null;
	}

	/**
	 * Marks the world reference as strong so it won't be collected
	 * by the garbage collector.
	 *
	 * @deprecated
	 *
	 * @return bool
	 */
	public function setStrong(){
		return false;
	}

	/**
	 * Marks the world reference as weak so it won't have effect against
	 * the garbage collector decision.
	 *
	 * @deprecated
	 *
	 * @return bool
	 */
	public function setWeak(){
		return false;
	}

	/**
	 * Returns a side Vector
	 *
	 * @param int $side
	 * @param int $step
	 *
	 * @return Position
	 *
	 * @throws WorldException
	 */
	public function getSide($side, $step = 1){
		if(!$this->isValid()){
			throw new WorldException("Undefined World reference");
		}

		return Position::fromObject(parent::getSide($side, $step), $this->world);
	}

	public function __toString(){
		return "Position(world=" . ($this->isValid() ? $this->getWorld()->getName() : "null") . ",x=" . $this->x . ",y=" . $this->y . ",z=" . $this->z . ")";
	}

	/**
	 * @param $x
	 * @param $y
	 * @param $z
	 *
	 * @return Position
	 */
	public function setComponents($x, $y, $z){
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
		return $this;
	}

}
