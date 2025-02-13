<?php
class Book {
    private $title;
    private $author;
    private $pubyear;
    private $price;

    public function __construct($title, $author, $pubyear, $price) {
        $this->title = $title;
        $this->author = $author;
        $this->pubyear = $pubyear;
        $this->price = $price;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getPubyear() {
        return $this->pubyear;
    }

    public function getPrice() {
        return $this->price;
    }
}
