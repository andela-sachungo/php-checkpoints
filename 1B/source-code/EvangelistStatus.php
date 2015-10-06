<?php 
namespace Stacey\Evangel;

/**
 * A class that retrieves the number of github repositories a user has 
 * assigns status accordingly.
 *
 * An object of this class has to be instantiated  with the github username
 * of the user, which is used to access the repositories a user has in github 
 * using the github api
 *
 *@author Stacey Achungo
 */
class EvangelistStatus
{
    /** @var string $username Holds the github username */
    protected $username;

    /** @var string $urlRepo Holds the url to access github repositories */
    protected $urlRepo;

    
    /** @var integer $gitReposCount Holds the total number of repositories */
    protected $gitReposCount;

    /** @var string $status Holds the various status */
    protected $status;

    /**
    * A constructor
    *
    * Called on each instantiation of an object, to 
    * initialize the username, retrieve the repositories a user has, count
    * them and return the result.
    *
    * To retrieve data via the github api, create a user agent as explained in 
    * https://github.com/philsturgeon/codeigniter-oauth2/issues/57 .
    * The stream_context_create() is used to generate a resource date type
    * required by file_get_contents().
    *
    * @param string username The github username declared when instantiating 
    * this class
    * @return integer The total number of repositories a user has
    * @throws Exception if the get request to github fails
    */
    public function __construct($username)
    {
        $this->username = $username;
        $this->urlRepo = "https://api.github.com/users/{$this->username}/repos";

        $options  = array('http' => array('user_agent' => 'evangelica'));
        $context  = stream_context_create($options);

        $repoContents = file_get_contents($this->urlRepo, false, $context);

        if ($repoContents == false) {
            throw new Exception("The get request failed!");
        }

        $responseArray = json_decode($repoContents);
        $this->gitReposCount = count($responseArray);

        return $this->gitReposCount;
    }

    /**
    * A method to get the user's status
    *
    * Uses the total number of repositories a user has to return their status to 
    * them 
    * @return string The status of the user
    */
    public function getStatus()
    {
        if (($this->gitReposCount >= 5) && ($this->gitReposCount <= 10)) {
            $this->status = "Damn It!!! Please make the world better, Oh Ye Junior Evangelist";
        } elseif (($this->gitReposCount >= 11) && ($this->gitReposCount <= 20)) {
            $this->status =  "Keep Up The Good Work, I crown you Associate Evangelist";
        } elseif ($this->gitReposCount >= 21) {
            $this->status =  "Yeah, I crown you Most Senior Evangelist. Thanks for making the world a better place";
        } else {
            $this->status =  "You are not even in the status radar :(";
        }

        return $this->status;
    }
}
