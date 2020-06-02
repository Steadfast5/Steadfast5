<?php

namespace pocketmine\item;

class WrittenBook extends WritableBook {

	const GENERATION_ORIGINAL = 0;
	const GENERATION_COPY = 1;
	const GENERATION_COPY_OF_COPY = 2;
	const GENERATION_TATTERED = 3;
	const TAG_GENERATION = "generation";
	const TAG_AUTHOR = "author";
	const TAG_TITLE = "title";

	public function __construct($meta = 0){
		Item::__construct(self::WRITTEN_BOOK, $meta, "Written Book");
	}

	public function getMaxStackSize() {
		return 16;
	}

	public function getGeneration() {
		return $this->getNamedTag()->getInt(self::TAG_GENERATION, -1);
	}

	public function setGeneration($generation) {
		if ($generation < 0 || $generation > 3) {
			throw new \InvalidArgumentException("Generation \"$generation\" is out of range");
		}
		$namedTag = $this->getNamedTag();
		$namedTag->setInt(self::TAG_GENERATION, $generation);
		$this->setNamedTag($namedTag);
	}

	public function getAuthor() {
		return $this->getNamedTag()->getString(self::TAG_AUTHOR, "");
	}

	public function setAuthor($authorName) {
		$namedTag = $this->getNamedTag();
		$namedTag->setString(self::TAG_AUTHOR, $authorName);
		$this->setNamedTag($namedTag);
	}

	public function getTitle() {
		return $this->getNamedTag()->getString(self::TAG_TITLE, "");
	}

	public function setTitle(string $title) {
		$namedTag = $this->getNamedTag();
		$namedTag->setString(self::TAG_TITLE, $title);
		$this->setNamedTag($namedTag);
	}

}
