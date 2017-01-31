<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name = "categories")
 */
class Category {

	/**
	 * @Id
	 * @Column(type = "integer")
	 * @GeneratedValue(strategy = "AUTO")
	 */
	private $id;
	
	/**
	 * @Column(type = "string", length = 100)
	 */
	private $nome;
	
	/**
	 * 
	 * @return unknown
	 */
	public function getId() {
		return $this->id;
	}
	

	public function getNome() {
		return $this->nome;
	}
	
	public function setNome($nome) {
		$this->id = $nome;
		return $this;
	}
	
}