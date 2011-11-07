<?php
class UrlSignerTest extends PHPUnit_Framework_TestCase
{

  protected $context;

  public function setUp() {
    $debug = true;
    $configuration = ProjectConfiguration::
      getApplicationConfiguration('frontend', 'test', $debug);
    $this->context = sfContext::createInstance($configuration);
  }

  /** @test */
  public function unsignedRouteIsBlocked() {
    $route = new CustomRoute();
    // Define mock and set expectations on it
    $response = $this->getMockBuilder('MockResponse')
                     ->disableOriginalConstructor()
                     ->getMock();
    $response->expects($this->once())
             ->method('setStatusCode')
             ->with(
                $this->equalTo('401'),
                $this->anything()
             );
    $response->expects($this->once())
             ->method('setHeaderOnly')
             ->with(
                $this->equalTo(true)
             );
    $response->expects($this->once())
             ->method('send');
    // load mock
    $this->context->setResponse($response);

    // call method under test
    $secretKey = "abcdef";
    $parameters = array();
    $route->redirectIfUrlIsNotSigned($parameters, $this->context, $secretKey);    
  }

  /** @test */
  public function signedRouteIsAccepted() {
    $route = new CustomRoute();
    // Define mock and set expectations on it
    $response = $this->getMockBuilder('MockResponse')
                     ->disableOriginalConstructor()
                     ->getMock();
    $response->expects($this->never())
             ->method('setStatusCode');
    $response->expects($this->never())
             ->method('setHeaderOnly');
    $response->expects($this->never())
             ->method('send');
    // load mock
    $this->context->setResponse($response);

    // call method under test
    $secretKey = "abcdef";
    $parameters = array(
      "lang"                => "fr",
      "query"               => "htc",
      "signature_timestamp" => "123456",
      "signature_value"     => sha1("fr" . "htc" . "123456" . "@" . $secretKey)
      );
    $route->redirectIfUrlIsNotSigned($parameters, $this->context, $secretKey);
  }

}

interface MockResponse {
  public function setStatusCode($code, $name);
  public function setHeaderOnly($value);
  public function send();
}
