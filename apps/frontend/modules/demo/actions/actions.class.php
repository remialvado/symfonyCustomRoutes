<?php

/**
 * demo actions.
 *
 * @package    symfonyCustomRoutes
 * @subpackage demo
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class demoActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  }

 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeSigned(sfWebRequest $request)
  {
    return $this->renderText("Url is properly signed");
  }
}
