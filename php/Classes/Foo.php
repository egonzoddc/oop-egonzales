<?php

/**
 * Variables - authorId
 * - authorEmail
 * - authorHash
 * - authorUsername
 **/

/**
 * accessors for variables
 */
class Profile {

	private $authorId;
	private $authorEmail;
	private $authorHash;
	private $authorUsername;


	public function getauthorId() {
		return ($this->authorId);
	}

	public function getauthorEmail() {
		return ($this ->authorEmail);
	}

	public function getauthorHash() {
		return ($this ->authorHash);
	}

	public function getauthorUsername() {
		return ($this ->authorUsername);
	}
}
?>