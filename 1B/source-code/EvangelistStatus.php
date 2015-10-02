<?php 
namespace Evangel;

 /**
 * A class that retrieves the number of github repositories a user has.
 *
 * An object of this class has to be instantiated  with the github username
 * of the user, which is used to access the repository a user has in github 
 * using the github api
 *
 *@author Stacey Achungo
 */
class EvangelistStatus
{
	/** @var string $username Holds the github username */
 	protected $username;

 	/** @var string $urlRepo Holds the url to access github repositories */
 	public $urlRepo;

 	/** @var string $responseResults Holds Json string with the repository 
 	* information
 	*/
 	public $responseResults;

 	/** @var integer $gitReposCount Holds the decimal number of github 
 	* repositories
 	*/
 	public $gitReposCount;

 	/**
    * A constructor
    *
    * Called on each instantiation of an object, to 
    * initialize the username
    *
    * @param string username The github username declared when instantiating 
    * this class
    */
 	public function __construct($username)
 	{
 		$this->username= $username;
 		$this->urlRepo = "https://api.github.com/users/{$this->username}/repos";
 	}

 	/**
    * A method to make the get request using the github api
    *
    * Creates a user agent as explained in the following url 
    * https://github.com/philsturgeon/codeigniter-oauth2/issues/57
    * the stream_context_create() is used to generate a resource date type
    * required by file_get_contents().
    *
    * @throws Exception If file_get_contents()returns false
    */
 	public function makeRequest()
 	{
 		$options  = array('http' => array('user_agent'=> 'evangelica'));
        $context  = stream_context_create($options);

 		$repoContents = file_get_contents($this->urlRepo, false, $context);

 		if ($repoContents == false) {
 			throw new Exception("The get request failed!");	
 		}

 		 $this->responseResults = $repoContents;
 	}

 	/**
    * A method to decode the json string
    *
    * Gets the returned json string, decodes it and count the result to get
    * the total number of repositories a use has
    *
    */
 	public function repositoryCount()
 	{
 		$responseArray = json_decode($this->responseResults);
 		$this->gitReposCount = count($responseArray);
 	}

 	/**
    * A method to get the user's status
    *
    * Uses the total number of repositories a user has to return their status to 
    * them 
    *
    * @return string The status of the user
    */
 	public function getStatus()
	{
		if (($this->gitReposCount >= 5) && ($this->gitReposCount <= 10)) {
			return "Damn It!!! Please make the world better, Oh Ye Junior Evangelist";
		} elseif (($this->gitReposCount >= 11) && ($this->gitReposCount <= 20)) {
			return  "Keep Up The Good Work, I crown you Associate Evangelist";
		} elseif ($this->gitReposCount >= 21) {
			return  "Yeah, I crown you Most Senior Evangelist. Thanks for making 
			the world a better place";
		} else {
			return  "You are not even in the status radar :(";
		}

	}
}
