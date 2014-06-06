<?php
namespace News\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\FlashMessenger;

class ShowMessages extends AbstractHelper
{
    public function __invoke()
    {
        $messenger = new FlashMessenger();
        $error_messages = $messenger->getErrorMessages();
        $messages = $messenger->getMessages();
        $warning_messages = $messenger->getWarningMessages();

        if (count($error_messages)) {
            $result = '<div class="alert alert-danger fade in">';
            $result .= '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>';
            if(1 == count($error_messages)) {
                $result .= '<strong>Ой!</strong> Ошибка:';
            } else {
                $result .= '<strong>Ой!</strong> Найдены следующие ошибки:';
            }


            $result .= '<ul class="list-unstyled">';
            foreach ($error_messages as $message) {
                $result .= '<li>' . $message . '</li>';
            }
            $result .= '</ul>';
            $result .= "</div>";
        }

        if (count($warning_messages)) {
            $result = '<div class="alert alert-warning fade in">';
            $result .= '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>';
            $result .= '<ul class="list-unstyled">';
            foreach ($warning_messages as $message) {
                $result .= '<li>' . $message . '</li>';
            }
            $result .= '</ul>';
            $result .= "</div>";
        }

        if (count($messages)) {
            $result = '<div class="alert alert-success fade in">';
            $result .= '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>';
            $result .= '<ul class="list-unstyled">';
            foreach ($messages as $message) {
                $result .= '<li>' . $message . '</li>';
            }
            $result .= '</ul>';
            $result .= "</div>";
        }

        return $result;
    }
}