<?php 

use Stacey\Evangel\EvangelistStatus;

class EvangelistStatusTest extends PHPUnit_Framework_TestCase
{
    public function testStatusJuniorEvangelist()
    {
        $user = new EvangelistStatus('andela-bmosigisi');
        $this->assertEquals($user->getStatus(), "Damn It!!! Please make the world better, Oh Ye Junior Evangelist");
    }

    public function testStatusAssociateEvangelist()
    {
        $user = new EvangelistStatus('andela-anandaa');
        $this->assertEquals($user->getStatus(), "Keep Up The Good Work, I crown you Associate Evangelist");
    }

    public function testStatusMostSeniorEvangelist()
    {
        $user = new EvangelistStatus('kn9ts');
        $this->assertEquals($user->getStatus(), "Yeah, I crown you Most Senior Evangelist. Thanks for making the world a better place");
    }

    public function testStatusNotQualifiedEvangelist()
    {
        $user = new EvangelistStatus('mohini');
        $this->assertEquals($user->getStatus(), "You are not even in the status radar :(");
    }
}
