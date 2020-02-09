<?php

declare(strict_types=1);

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class VersionControlAdapterTest extends TestCase
{
    /**
     * @var string
     */
    private $httpMethod = 'GET';
    /**
     * @var string
     */
    private $fullUrl = 'http://test.com/api/';
    /**
     * @var GuzzleHttp\Client | MockObject
     */
    private $apiClient;
    /**
     * @var VersionControlAdapter | MockObject
     */
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->apiClient = $this->getMockBuilder(GuzzleHttp\Client::class)
            ->setMethods(['request'])
            ->getMock();
        $this->sut = $this->getMockBuilder(VersionControlAdapter::class)
            ->setMethods(['query'])
            ->setConstructorArgs([
                $this->apiClient,
                'http://test.com/api/',
                'testLogin',
                'testRepo',
                'testBranch',
            ])
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    public function testValidResponse() {
        $apiClientValidResponse =  new Response(200, ['X-Foo' => 'Bar'], 'Hello, World');
        $this->apiClient
            ->expects(static::once())
            ->method('request')
            ->with($this->httpMethod, $this->fullUrl)
            ->willReturn($apiClientValidResponse)
        ;

        $this->assertEquals($apiClientValidResponse, $this->sut->query($this->httpMethod, $this->fullUrl));
    }

    public function testInvalidResponse()
    {
        $apiClientInvalidResponse =  new RequestException('Error Communicating with Server', new Request('GET', 'test'));

        $this->apiClient
            ->expects(static::once())
            ->method('request')
            ->with($this->httpMethod, $this->fullUrl)
            ->willReturn($apiClientInvalidResponse)
        ;
        var_dump($this->sut->query($this->httpMethod, $this->fullUrl)); die;
        $this->assertEquals($apiClientInvalidResponse, $this->sut->query($this->httpMethod, $this->fullUrl));
    }
}