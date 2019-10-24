<?php

namespace egonzoddc\OOP;
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Exception;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use RangeException;
use TypeError;
use UnexpectedValueException;

/**
 * Cross Section of a Twitter Profile
 *
 * This is a cross section of what is probably stored about a Twitter user. This entity is a top level entity that
 * holds the keys to the other entities in this example (i.e., Favorite and Profile).
 *
class Profile implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * id for this Profile; and this is the primary key
	 * @var Uuid $profileId
	 **/
class Profile  {
	use ValidateUuid;
	/**
	 * id for this Profile; this is the primary key
	 * @var Uuid $profileId
	 **/
	private $profileId;
	/**
	 * at handle for this Profile; this is a unique index
	 * @var string $profileAtHandle
	 **/
	private $profileAtHandle;
	/**
	 * token handed out to verify that the profile is valid and not malicious.
	 *v@var $profileActivationToken
	 **/
	private $profileActivationToken;
	/**
	 * email for this Profile; this is a unique index
	 * @var string $profileEmail
	 **/
	private $profileEmail;
	/**
	 * hash for profile password
	 * @var $profileHash
	 **/
	private $profileHash;
	/**
	 * phone number for this Profile
	 * @var string $profilePhone
	 **/
	private $profilePhone;
	/**
	 * salt for profile password
	 *
	 * @var $profileSalt
	 */
	private $profileSalt;

	/**
	 * constructor for this Profile
	 *
	 * @param string|Uuid $newProfileId id of this Profile or null if a new Profile
	 * @param string $newProfileActivationToken activation token to safe guard against malicious accounts
	 * @param string $newProfileAtHandle string containing newAtHandle
	 * @param string $newProfileEmail string containing email
	 * @param string $newProfileHash string containing password hash
	 * @param string $newProfilePhone string containing phone number
	 * @param string $newProfileSalt string containing passowrd salt
	 * @throws InvalidArgumentException if data types are not valid
	 * @throws RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws TypeError if a data type violates a data hint
	 * @throws Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newProfileId, ?string $newProfileActivationToken, string $newProfileAtHandle, string $newProfileEmail, string $newProfileHash, ?string $newProfilePhone, string $newProfileSalt) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileActivationToken($newProfileActivationToken);
			$this->setProfileAtHandle($newProfileAtHandle);
			$this->setProfileEmail($newProfileEmail);
			$this->setProfileHash($newProfileHash);
			$this->setProfilePhone($newProfilePhone);
			$this->setProfileSalt($newProfileSalt);
		} catch(InvalidArgumentException | RangeException | Exception | TypeError $exception) {
			//determine what exception type was thrown
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for profile id
	 *
	 * @return Uuid value of profile id (or null if new Profile)
	 **/
	public function getProfileId(): Uuid {
		return ($this->profileId);
	}

	/**
	 * mutator method for profile id
	 *
	 * @param Uuid| string $newProfileId value of new profile id
	 * @throws RangeException if $newProfileId is not positive
	 * @throws TypeError if the profile Id is not
	 **/
	public function setProfileId($newProfileId): void {
		try {
			$uuid = self::validateUuid($newProfileId);
		} catch(InvalidArgumentException | RangeException | Exception | TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the profile id
		$this->profileId = $uuid;
	}

	/**
	 * accessor method for account activation token
	 *
	 * @return string value of the activation token
	 */
	public function getProfileActivationToken(): ?string {
		return ($this->profileActivationToken);
	}

	/**
	 * mutator method for account activation token
	 *
	 * @param string $newProfileActivationToken
	 * @throws InvalidArgumentException  if the token is not a string or insecure
	 * @throws RangeException if the token is not exactly 32 characters
	 * @throws TypeError if the activation token is not a string
	 */
	public function setProfileActivationToken(?string $newProfileActivationToken): void {
		if($newProfileActivationToken === null) {
			$this->profileActivationToken = null;
			return;
		}
		$newProfileActivationToken = strtolower(trim($newProfileActivationToken));
		if(ctype_xdigit($newProfileActivationToken) === false) {
			throw(newRangeException("user activation is not valid"));
		}
		//make sure user activation token is only 32 characters
		if(strlen($newProfileActivationToken) !== 32) {
			throw(newRangeException("user activation token has to be 32"));
		}
		$this->profileActivationToken = $newProfileActivationToken;
	}

	/**
	 * accessor method for at handle
	 *
	 * @return string value of at handle
	 **/
	public function getProfileAtHandle(): string {
		return ($this->profileAtHandle);
	}

	/**
	 * mutator method for at handle
	 *
	 * @param string $newProfileAtHandle new value of at handle
	 * @throws InvalidArgumentException if $newAtHandle is not a string or insecure
	 * @throws RangeException if $newAtHandle is > 32 characters
	 * @throws TypeError if $newAtHandle is not a string
	 **/
	public function setProfileAtHandle(string $newProfileAtHandle): void {
		// verify the at handle is secure
		$newProfileAtHandle = trim($newProfileAtHandle);
		$newProfileAtHandle = filter_var($newProfileAtHandle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileAtHandle) === true) {
			throw(new InvalidArgumentException("profile at handle is empty or insecure"));
		}
		// verify the at handle will fit in the database
		if(strlen($newProfileAtHandle) > 32) {
			throw(new RangeException("profile at handle is too large"));
		}
		// store the at handle
		$this->profileAtHandle = $newProfileAtHandle;
	}

	/**
	 * accessor method for email
	 *
	 * @return string value of email
	 **/
	public function getProfileEmail(): string {
		return $this->profileEmail;
	}

	/**
	 * mutator method for email
	 *
	 * @param string $newProfileEmail new value of email
	 * @throws InvalidArgumentException if $newEmail is not a valid email or insecure
	 * @throws RangeException if $newEmail is > 128 characters
	 * @throws TypeError if $newEmail is not a string
	 **/
	public function setProfileEmail(string $newProfileEmail): void {
		// verify the email is secure
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newProfileEmail) === true) {
			throw(new InvalidArgumentException("profile email is empty or insecure"));
		}
		// verify the email will fit in the database
		if(strlen($newProfileEmail) > 128) {
			throw(new RangeException("profile email is too large"));
		}
		// store the email
		$this->profileEmail = $newProfileEmail;
	}

	/**
	 * accessor method for profileHash
	 *
	 * @return string value of hash
	 */
	public function getProfileHash(): string {
		return $this->profileHash;
	}

	/**
	 * mutator method for profile hash password
	 *
	 * @param string $newProfileHash
	 * @throws InvalidArgumentException if the hash is not secure
	 * @throws RangeException if the hash is not 128 characters
	 * @throws TypeError if profile hash is not a string
	 */
	public function setProfileHash(string $newProfileHash): void {
		//enforce that the hash is properly formatted
		$newProfileHash = trim($newProfileHash);
		$newProfileHash = strtolower($newProfileHash);
		if(empty($newProfileHash) === true) {
			throw(new InvalidArgumentException("profile password hash empty or insecure"));
		}
		//enforce that the hash is a string representation of a hexadecimal
		if(!ctype_xdigit($newProfileHash)) {
			throw(new InvalidArgumentException("profile password hash is empty or insecure"));
		}
		//enforce that the hash is exactly 128 characters.
		if(strlen($newProfileHash) !== 128) {
			throw(new RangeException("profile hash must be 128 characters"));
		}
		//store the hash
		$this->profileHash = $newProfileHash;
	}

	/**
	 * accessor method for phone
	 *
	 * @return string value of phone or null
	 **/
	public function getProfilePhone(): ?string {
		return ($this->profilePhone);
	}

	/**
	 * mutator method for phone
	 *
	 * @param string $newProfilePhone new value of phone
	 * @throws InvalidArgumentException if $newPhone is not a string or insecure
	 * @throws RangeException if $newPhone is > 32 characters
	 * @throws TypeError if $newPhone is not a string
	 **/
	public function setProfilePhone(?string $newProfilePhone): void {
		//if $profilePhone is null return it right away
		if($newProfilePhone === null) {
			$this->profilePhone = null;
			return;
		}
		// verify the phone is secure
		$newProfilePhone = trim($newProfilePhone);
		$newProfilePhone = filter_var($newProfilePhone, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfilePhone) === true) {
			throw(new InvalidArgumentException("profile phone is empty or insecure"));
		}
		// verify the phone will fit in the database
		if(strlen($newProfilePhone) > 32) {
			throw(new RangeException("profile phone is too large"));
		}
		// store the phone
		$this->profilePhone = $newProfilePhone;
	}

	/**
	 *accessor method for profile salt
	 *
	 * @return string representation of the salt hexadecimal
	 */
	public function getProfileSalt(): string {
		return $this->profileSalt;
	}

	/**
	 * mutator method for profile salt
	 *
	 * @param string $newProfileSalt
	 * @throws InvalidArgumentException if the salt is not secure
	 * @throws RangeException if the salt is not 64 characters
	 * @throws TypeError if the profile salt is not a string
	 */
	public function setProfileSalt(string $newProfileSalt): void {
		//enforce that the salt is properly formatted
		$newProfileSalt = trim($newProfileSalt);
		$newProfileSalt = strtolower($newProfileSalt);
		//enforce that the salt is a string representation of a hexadecimal
		if(!ctype_xdigit($newProfileSalt)) {
			throw(new InvalidArgumentException("profile password hash is empty or insecure"));
		}
		//enforce that the salt is exactly 64 characters.
		if(strlen($newProfileSalt) !== 64) {
			throw(new RangeException("profile salt must be 128 characters"));
		}
		//store the hash
		$this->profileSalt = $newProfileSalt;
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		$fields["profileId"] = $this->profileId->toString();
		unset($fields["profileHash"]);
		unset($fields["profileSalt"]);
		return ($fields);
	}
}


/**
 * Variables - authorId
 * 			- authorEmail
 *				 - authorHash
 * 			- authorUsername
 **/

/**
 * getter for variables
 */
class Author {

	private $authorId;
	private $authorEmail;
	private $authorHash;
	private $authorUsername;

	/**
	 * getter method for author id
	 * @return int value of author id
	 **/
	public function getauthorId() {
		return ($this->authorId);
	}
	/**
	 * getter method for author email
	 * @return int value of author email
	 **/

	public function getauthorEmail() {
		return ($this ->authorEmail);
	}
	/**
	 * getter method for author hash
	 * @return int value of author hash
	 **/

	public function getauthorHash() {
		return ($this ->authorHash);
	}
	/**
	 * getter method for author username
	 * @return int value of author username
	 **/

	public function getauthorUsername() {
		return ($this ->authorUsername);
	}

	/**
	 * setter method for author id
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