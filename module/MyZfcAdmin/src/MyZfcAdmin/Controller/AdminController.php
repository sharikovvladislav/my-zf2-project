<?php

namespace MyZfcAdmin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdminController extends \ZfcAdmin\Controller\AdminController
{
    public function indexAction() {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcadmin/login');
        }
        return new ViewModel();
    }
    public function loginAction() {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute('zfcadmin');
        }
        // layout is changed in Module.php
        return new ViewModel();
    }
    
    public function logoutAction() {
        return $this->redirect()->toRoute('zfcuser/logout');
    }
}
