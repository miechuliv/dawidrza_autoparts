<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 20.09.13
 * Time: 15:25
 * To change this template use File | Settings | File Templates.
 */

include_once(DIR_APPLICATION."controller/common/oneslashseo.php");

class OneSlashSeoTest extends PHPUnit_Framework_TestCase {

    private $_register;

    public function setUp()
    {
        $this->_register =  new Registry();


    }

    /*
     * @dataProvider provider
     */
    public function testOutput($data)
    {

        $db = $this->getMock('db');

        foreach($data['seo_alias'] as $key => $alias)
        {
            $db->expects($this->at($key))
                ->method('query')
                ->with($this->equalTo($data['id']))
                ->will($this->returnValue($alias));
        }


        $this->_register('db',$db);


         $controllerOneSlashSeo  = new ControllerCommonOneSlashSeo($this->_register,'testing');

        $controllerOneSlashSeo->rewrite($data['link']);
    }

    public function provider()
    {
        return array(
            0 => array(

                'link' => 'route=product/product&product_id=1',
                'seo_alias' => (object) array(
                    'id' => 1,
                    'num_rows'=> 1,
                    'row' => array(
                        'keyword' => 'produkcik',
                    )
                ),
            )
        );
    }

}