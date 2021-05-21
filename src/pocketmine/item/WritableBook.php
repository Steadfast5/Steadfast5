<?php

namespace pocketmine\item;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;

class WritableBook extends Item {

	const TAG_PAGES = "pages";
	const TAG_PAGE_TEXT = "text";
	const TAG_PAGE_PHOTONAME = "photoname";

	public function __construct() {
		parent::__construct(self::WRITABLE_BOOK, 0, 1, "Book & Quill");
	}

	public function pageExists($pageId) {
		return $this->getPagesTag()->isset($pageId);
	}

	public function getPageText($pageId) {
		$pages = $this->getNamedTag()->getListTag(self::TAG_PAGES);
		if ($pages === null || !$pages->isset($pageId)) {
			return null;
		}

		$page = $pages->get($pageId);
		if ($page instanceof Compound) {
			return $page->getString(self::TAG_PAGE_TEXT, "");
		}
		return null;
	}

	public function setPageText($pageId, $pageText) {
		$created = false;
		if (!$this->pageExists($pageId)) {
			$this->addPage($pageId);
			$created = true;
		}

		$pagesTag = $this->getPagesTag();
		$page = $pagesTag->get($pageId);
		$page->setString(self::TAG_PAGE_TEXT, $pageText);

		$this->setNamedTagEntry($pagesTag);
		return $created;
	}

	public function addPage($pageId) {
		if ($pageId < 0) {
			throw new \InvalidArgumentException("Page number \"$pageId\" is out of range");
		}

		$pagesTag = $this->getPagesTag();

		for ($current = $pagesTag->count(); $current <= $pageId; $current++) {
			$pagesTag->push(new CompoundTag("", [
				new StringTag(self::TAG_PAGE_TEXT, ""),
				new StringTag(self::TAG_PAGE_PHOTONAME, "")
			]));
		}

		$this->setNamedTagEntry($pagesTag);
	}

	public function deletePage($pageId) {
		$pagesTag = $this->getPagesTag();
		$pagesTag->remove($pageId);
		$this->setNamedTagEntry($pagesTag);
		return true;
	}

	public function insertPage($pageId, $pageText = "") {
		$pagesTag = $this->getPagesTag();
		$pagesTag->insert($pageId, new CompoundTag("", [
			new StringTag(self::TAG_PAGE_TEXT, $pageText),
			new StringTag(self::TAG_PAGE_PHOTONAME, "")
		]));

		$this->setNamedTagEntry($pagesTag);
		return true;
	}

	public function swapPages($pageId1, $pageId2) {
		if (!$this->pageExists($pageId1) || !$this->pageExists($pageId2)) {
			return false;
		}

		$pageContents1 = $this->getPageText($pageId1);
		$pageContents2 = $this->getPageText($pageId2);
		$this->setPageText($pageId1, $pageContents2);
		$this->setPageText($pageId2, $pageContents1);
		return true;
	}

	public function getPages() {
		$pages = $this->getPagesTag()->getValue();
		return $pages;
	}

	protected function getPagesTag() {
		$pagesTag = $this->getNamedTag()->getListTag(self::TAG_PAGES);
		if ($pagesTag !== null && $pagesTag->getTagType() === NBT::TAG_Compound) {
			return $pagesTag;
		}
		return new ListTag(self::TAG_PAGES, [], NBT::TAG_Compound);
	}

	public function setPages($pages) {
		$nbt = $this->getNamedTag();
		$nbt->setTag(new ListTag(self::TAG_PAGES, $pages, NBT::TAG_Compound));
		$this->setNamedTag($nbt);
	}

	public function getMaxStackSize() {
		return 1;
	}

}
