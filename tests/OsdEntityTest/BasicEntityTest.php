<?php

namespace OsdEntityTest;

class BasicEntityTest extends \PHPUnit_Framework_TestCase
{
    /*
     * @test
     */
    public function test_can_instantiate_a_class()
    {
        $entity = new \EntityTest();
        $this->assertNotNull($entity);
    }

    /*
     * @test
     */
    public function test_can_fill_an_instance()
    {
        $entity = new \EntityTest();

        $data = $this->getData();

        $entity->fill($data);

        $entity = $entity->toArray();

        foreach ($data as $key => $value) {
            $this->assertEquals($entity[$key], $value);
        }
    }

    protected function getData()
    {
        return array(
            'attribute_one' => 'testone',
            'attributeTwo' => 'testtwo',
            'attributeThree' => 'testthree',
            'attribute_number_four' => 'testfour',
            'attributeNumberFive' => 'testFive',
        );
    }
}