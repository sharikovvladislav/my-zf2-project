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

    public function addAction() {
        $form = new AddNewsForm();
        $form->get('submit')->setValue('Add');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        
            $form->setData($request->getPost());
            //var_dump($form->isValid());
            //var_dump($form->getMessages()); //error messages
            //var_dump($form->getInputFilter()->getMessages());
            if ($form->isValid()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $item = new NewsItem();

                $item->exchangeArray($form->getData());

                $item->setCreated(time());
                $item->setUserId(0);
                $item->setCategoryId(0);

                $objectManager->persist($item);
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