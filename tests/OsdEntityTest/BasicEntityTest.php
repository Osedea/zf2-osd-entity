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

    /*
     * @test
     */
    public function test_can_create_an_instance()
    {
        $data = $this->getData();

        $entity = \EntityTest::create($data);

        $entity = $entity->toArray();

        foreach ($data as $key => $value) {
            $this->assertEquals($entity[$key], $value);
        }
    }

    /*
     * @test
     */
    public function test_can_update_an_instance()
    {
        $data = $this->getData();

        $entity = \EntityTest::create($data);

        $updateData = $this->getUpdateData();

        $entity->update($updateData);

        $entity = $entity->toArray();

        foreach ($updateData as $key => $value) {
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

    protected function getUpdateData()
    {
        return array(
            'attribute_one' => 'updatedone',
            'attributeTwo' => 'updatedtwo',
            'attributeThree' => 'updatedthree',
            'attribute_number_four' => 'updatedfour',
            'attributeNumberFive' => 'updatedfive',
        );
    }
}