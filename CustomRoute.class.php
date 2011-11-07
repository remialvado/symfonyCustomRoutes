<?php
class CustomRoute extends sfRequestRoute {

  const DEFAULT_SECRET_KEY = "abcdef";

  public function matchesUrl($url, $context = array()) {
    $parameters = parent::matchesUrl($url, $context);
    if (!$parameters) {
      return false;
    }
    $queryParameters = $this->getQueryParameters($context);
    $sfContext = sfContext::getInstance();
    if ($this->shouldThisRouteBeSigned()) {
      $urlSigner = new UrlSigner();
      $urlSigner->redirectIfUrlIsNotSigned($queryParameters, $sfContext, $this->getSecretKey());
      return $parameters;
    }
    return $parameters;
  }

  public function getQueryParameters($context) {
    $queryParametersAsString = parse_url($context["request_uri"],PHP_URL_QUERY);
    parse_str($queryParametersAsString,$queryParameters);
    return $queryParameters;
  }

  public function shouldThisRouteBeSigned() {
    if (array_key_exists('secure', $this->requirements)) {
      $secure = $this->requirements["secure"];
      return array_key_exists('status', $secure) && $secure["status"] === "enabled";
    }
    return false;
  }

  public function getSecretKey() {
    if (array_key_exists('secure', $this->requirements)) {
      $secure = $this->requirements["secure"];
      return array_key_exists('secretKey', $secure) ? $secure["secretKey"] : self::DEFAULT_SECRET_KEY;
    }
    return self::DEFAULT_SECRET_KEY;
  }

}

