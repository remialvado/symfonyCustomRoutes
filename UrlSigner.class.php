<?php
class UrlSigner {

  public function redirectIfUrlIsNotSigned($parameters, $context, $secretKey) {
    if (!is_array($parameters) || !array_key_exists("signature_value", $parameters)) {
      error_log(print_r($parameters,true));
      $this->urlIsNotProperlySigned($context);
      return false;
    }
    $signatureValue = $parameters["signature_value"];
    $signature = $this->computeSignatureValue($parameters, $secretKey);
    if ($signatureValue != $signature) {
      $this->urlIsNotProperlySigned($context);
      return false;
    }
    return true;
  }

  public function computeSignatureValue($parameters, $secretKey) {
    $cleanedUpParameterNames = $this->getParameterNamesExceptSignatureValueAndOrderedThemAlphabetically($parameters);
    $string = "";
    foreach ($cleanedUpParameterNames as $parameterName) {
      $string .= $parameters[$parameterName];
    }
    return sha1($string . "@" . $secretKey);
  }

  public function getParameterNamesExceptSignatureValueAndOrderedThemAlphabetically($parameters) {
    unset($parameters["signature_value"]);
    $parameterNames = array_keys($parameters);
    sort($parameterNames,SORT_STRING);
    return $parameterNames;
  }

  public function urlIsNotProperlySigned($context, $message = "Url is not signed") {
    $context->getResponse()->setStatusCode(401,$message);
    $context->getResponse()->setHeaderOnly(true);
    $context->getResponse()->send();    
  }
}
?>
