<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name = "posts")
 */
class Post {

	/**
	 * @Id
	 * @Column(type = "integer")
	 * @GeneratedValue(strategy = "AUTO")
	 */
	private $id;
	
	/**
	 * @Column(type = "string", length = 100)
	 */
	private $title;
	
	/**
	 * @Column(type = "text")
	 */
	private $content;
	
	/**
	 * 
	 * @return unknown
	 */
	public function getId() {
		return $this->id;
	}
	

	public function geTitle() {
		return $this->title;
	}
	
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}
	
	public function geContent() {
		return $this->content;
	}
	
	public function setContent($content) {
		$this->content = $content;
		return $this;
	}
	
}