<?

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use \News\Entity\NewsItem as NewsItem;
use \News\Form\AddNewsForm as AddNewsForm;

class NewsController extends AbstractActionController {
    public function indexAction() {
        return new ViewModel();
    }

    public function addAction(){
        $form = new AddNewsForm();
        $form->get('submit')->setValue('Add1');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            var_dump($form->isValid());
            if ($form->isValid()) {
                echo "form is valid";
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $blogpost = new NewsItem();

                $blogpost->exchangeArray($form->getData());

                $blogpost->setCreated(time());
                $blogpost->setUserId(0);

                $objectManager->persist($blogpost);
                $objectManager->flush();

                // Redirect to list of blogposts
                return $this->redirect()->toRoute('news');
            }
        }
        return array('form' => $form);
    }

    public function deleteAction() {
        return new ViewModel();
    }

    public function editAction(){
        return new ViewModel();
    }
}