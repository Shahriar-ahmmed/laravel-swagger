<?php

namespace Mtrajano\LaravelSwagger\Tests\Parameters;

use Mtrajano\LaravelSwagger\Tests\TestCase;
use Mtrajano\LaravelSwagger\Parameters\BodyParameterGenerator;

class BodyParameterGeneratorTest extends TestCase
{
    public function testStructure()
    {
        $bodyParameters = $this->getBodyParameters([]);

        $this->assertArrayHasKey('in', $bodyParameters);
        $this->assertArrayHasKey('name', $bodyParameters);
        $this->assertArrayHasKey('schema', $bodyParameters);
        $this->assertArraySubset(['type' => 'object'], $bodyParameters['schema']);
    }

    public function testRequiredParameters()
    {
        $bodyParameters = $this->getBodyParameters([
            'id'            => 'integer|required',
            'email'         => 'email|required',
            'address'       => 'string|required',
            'dob'           => 'date|required',
            'picture'       => 'file',
            'is_validated'  => 'boolean',
            'score'         => 'numeric',
        ]);

        $this->assertEquals([
            'id',
            'email',
            'address',
            'dob',
        ], $bodyParameters['schema']['required']);

        return $bodyParameters;
    }

    /**
     * @depends testRequiredParameters
     */
    public function testDataTypes($bodyParameters)
    {
        $this->assertEquals([
            'id'            => ['type' => 'integer'],
            'email'         => ['type' => 'string'],
            'address'       => ['type' => 'string'],
            'dob'           => ['type' => 'string'],
            'picture'       => ['type' => 'string'],
            'is_validated'  => ['type' => 'boolean'],
            'score'         => ['type' => 'number'],
        ], $bodyParameters['schema']['properties']);
    }

    public function testNoRequiredParameters()
    {
        $bodyParameters = $this->getBodyParameters([]);

        $this->assertArrayNotHasKey('required', $bodyParameters['schema']);
    }

    private function getBodyParameters(array $rules)
    {
        $bodyParameters = (new BodyParameterGenerator('post', '/', $rules))->getParameters();

        return current($bodyParameters);
    }
}