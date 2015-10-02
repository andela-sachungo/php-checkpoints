<?php 

use Evangel\EvangelistStatus;

 class EvangelistStatusTest extends PHPUnit_Framework_TestCase
 {
    /**
    * A class that tests the functionality of the EvangelistStatus class
    * 
    * A test double is created for the class using the getMockBuilder($type)
    * method. The constructor argument is set and a stub method, in this case
    * makeRequest() is declared.In order to remove the dependency introduced by
    * file_get_contents(), a sample Json string is created in the getJson()method
    * called in this method. Also, another function(i.e. decodeJsonCount) that 
    * performs a similar function as repositoryCount() is created
    *
    * Note: when using namespaces, phpunit needs a fully qualified namespace 
    * of the class being mocked
    */
    public function testGitRepositoriesObtained()
    {
        $username = array('andela-bmosigisi');
 
        $getRepository = $this -> getMockBuilder('Evangel\EvangelistStatus')
                               -> setConstructorArgs($username)
                               -> setMethods(array('makeRequest')) 
                               -> getMock();

        $getRepository->responseResults = $this->getJson();

        $getRepository -> expects($this->any())
                       -> method('makeRequest');

        $getRepository->gitReposCount =$this->decodeJsonCount($this->getJson());

        $this->assertEquals(9, $getRepository->gitReposCount);

        $status = $getRepository->getStatus();
        print_r($status);   
    }

    protected function getJson()
    {
        $repoJson = "[{'id': 3752, 'name': 'checkpoints'},
 		{'id': 3753, 'name': 'floorpaneljs'},
 		{'id': 3754, 'name': 'complex-js'},
 		{'id': 3755, 'name': 'bootcamp-kenya'},
 		{'id': 3756, 'name': 'my-code-jam-solutions'},
 		{'id': 3757, 'name': 'Adding-dates'},
 		{'id': 3758, 'name': 'laravel-rest'},
 		{'id': 3759, 'name': 'WebRTC-Experiment'},
 		{'id': 37510, 'name': 'node-supervisor'}]";

        return $repoJson;
    }

    protected function decodeJsonCount($stringJson)
    {
     	$counter =0;
     	for ($i = 0, $n =strlen($stringJson); $i < $n ; $i++) { 
     		if ($stringJson[$i] == '{') {
     			$counter++;
            }
     	}
        return $counter;
    }
}
