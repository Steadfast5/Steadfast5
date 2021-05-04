<?php

namespace pocketmine\item;

class Book extends Item {

	public function __construct($meta = 0) {
		parent::__construct(self::BOOK, $meta, "Book");
	}

}
