<?php

/**
 * Variables - authorId
 * 			- authorEmail
 *				 - authorHash
 * 			- authorUsername
 **/

/**
 * accessors for variables
 */
class Profile {

	private $authorId;
	private $authorEmail;
	private $authorHash;
	private $authorUsername;

	/**
	 * accessor method for author id
	 * @return int value of author id
	**/
	public function getauthorId() {
		return ($this->authorId);
	}
	/**
	 * accessor method for author email
	 * @return int value of author email
	 **/

	public function getauthorEmail() {
		return ($this ->authorEmail);
	}
	/**
	 * accessor method for author hash
	 * @return int value of author hash
	 **/

	public function getauthorHash() {
		return ($this ->authorHash);
	}
	/**
	 * accessor method for author username
	 * @return int value of author username
	 **/

	public function getauthorUsername() {
		return ($this ->authorUsername);
	}

	/**
	 * mutator method for author id
	 *
	 * @param $newAuthorId new value of author id
	 * @param UnexpectedValueException if $newAuthorID si not an integer
	 */
	public function setauthorId($newAuthorId) {
		//verify the author id is valid
		$newAuthorId = filter_var($newAuthorId, FILTER_VALIDATE_INT);
		if($newAuthorId === false) {
			throw(new UnexpectedValueException("author id is not a valid integer"));
		}
		// convert and store author id
		$this->authorId = intval($newAuthorId);
	}
}
?>