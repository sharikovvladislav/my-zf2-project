<?

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use \News\Entity\Category as Category;
use \News\Form\AddCategoryForm as AddCategoryForm;

class CategoryController extends AbstractActionController {
    public function indexAction() {
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $categories = $objectManager
            ->getRepository('\News\Entity\Category')
            ->findAll();
            
        $items = array();
        foreach ($categories as $category) {
            $items[] = $category->getArrayCopy();
        }

        $view = new ViewModel(array(
            'categories' => $items,
        ));

        return $view;
    }

    public function addAction() {
        $form = new AddCategoryForm();
        $form->get('visible')->setCheckedValue(0);
        $form->get('visible')->setUncheckedValue(1);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

                $item = new Category();

                $item->exchangeArray($form->getData());

                $objectManager->persist($item);
                $objectManager->flush();

                // Redirect to list of blogposts
                return $this->redirect()->toRoute('zfcadmin/news/categories');
            }
        }
        return array('form' => $form);
    }

    public function deleteAction() {
        $id = (int)$this->params('id');
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $category = $entityManager
            ->getRepository('\News\Entity\Category')
            ->findOneById($id);
        
        if(isset($category)) {
            $result = array(
                'result' => true,
                'categoryName' => $category->getName(),
            );
            
            $entityManager->remove($category);
            $entityManager->flush();
        } else {
            $result = array(
                'result' => false,
            );
        }
        $view = new ViewModel($result);
        return $view;
    }

    public function editAction(){
        return new ViewModel();
    }
}