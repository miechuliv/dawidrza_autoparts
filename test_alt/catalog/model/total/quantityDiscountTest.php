<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 07.04.14
 * Time: 10:09
 * To change this template use File | Settings | File Templates.
 */

class quantityDiscountTest extends ModelTest{

        public function setUp()
        {
            $this->tableName = 'product_quantity_discount';

            $this->basePath = __DIR__;

            include_once(DIR_APPLICATION.'model/total/quantity_discount.php');



            /*$currency = $this->getMock('currency',array('format'));
            $currency->expects($this->Any())
                ->method('format')
                ->will($this->returnValue(100));*/

            $this->addLibraryMocks(array('currency' => $currency));


        }

        public function testFound()
        {

            $cart = $this->getMock('cart',array('getProducts'));
            $cart->expects($this->Any())
                ->method('getProducts')
                ->will($this->returnValue(array(
                    array(
                        'product_id' => 1,
                        'price' => 80,
                        'quantity' => 300
                    ),
                    array(
                        'product_id' => 2,
                        'price' => 15.5,
                        'quantity' => 350
                    )
                )));

            $this->addLibraryMocks(array('cart' => $cart));

            $this->target = new ModelTotalQuantityDiscount($this->registry);

            $this->cleanUpDB();
            $this->loadFixtureArray($this->basePath.'/fixtures/array/product_quantity_discount.php','product_quantity_discount');

            $this->resetQueryCount();

            $totals = array();
            $total = 1000;
            $taxes = array();
            $this->target->getTotal($totals,$total,$taxes);

            //18000 + 4611,25 + 1000
            $this->assertEquals(23611.25,$total);
            $this->assertEquals(1,count($totals));
        }
}