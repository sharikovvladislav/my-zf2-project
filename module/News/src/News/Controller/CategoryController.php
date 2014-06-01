<?

namespace News\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use \News\Entity\Category as Category;
use \News\Form\CategoryForm as CategoryForm;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator as ZendPaginator;

class CategoryController extends AbstractActionController {
    protected $objectManager;

    public function __construct($objectManager = null) {
        if($objectManager) {
            $this->objectManager = $objectManager;
        }
    }

    public function indexAction() {
        $page = (int)$this->params('page');

        $categories = $this->objectManager
            ->getRepository('\News\Entity\Category');

        $query = $categories->createQueryBuilder('c');

        $paginator = new ZendPaginator(new PaginatorAdapter(new ORMPaginator($query)));
        $paginator->setCurrentPageNumber($page);
        $paginator->setDefaultItemCountPerPage(20);

        return new ViewModel(array(
            'categories' => $paginator,
        ));
    }

    public function addAction() {
        $form = new CategoryForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $item = new Category();

                $item->exchangeArray($form->getData());

                $this->objectManager->persist($item);
                $this->objectManager->flush();

                // Redirect to list of blogposts
                return $this->redirect()->toRoute('zfcadmin/news/categories');
            }
        }
        return array('form' => $form);
    }

    public function deleteAction() {
        $id = (int)$this->params('id');

        if(!$id) {
            return $this->redirect()->toRoute('zfcadmin/news/categories');
        }

        $category = $this->objectManager
            ->getRepository('\News\Entity\Category')
            ->findOneById($id);
        
        if (!$category) {
            $this->flashMessenger()->addErrorMessage(sprintf('Категория с идентификатором "%s" не найдена', $id));
            return $this->redirect()->toRoute('zfcadmin/news/categories');
        }
        
        $result = array(
            'result' => true,
            'categoryName' => $category->getName(),
        );
        
        $this->objectManager->remove($category);
        $this->objectManager->flush();
            
        $view = new ViewModel($result);
        return $view;
    }

    public function editAction() {
        $form = new CategoryForm();
        
        $form->get('submit')->setValue('Изменить');
        
        //handling request
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                
                $categoryId = $data['id'];
                
                try {
                    $item = $this->objectManager->find('\News\Entity\Category', $categoryId);
                }
                catch (\Exception $ex) {
                    return $this->redirect()->toRoute('zfcadmin/news/categories');
                }

                $item->exchangeArray($form->getData());
                
                $this->objectManager->persist($item);
                $this->objectManager->flush();

                $message = 'Категория изменена';
                $this->flashMessenger()->addMessage($message);
                // Redirect to list of items
                return $this->redirect()->toRoute('zfcadmin/news/categories');
            } else {
                $message = 'Ошибка при изменении категории';
                $this->flashMessenger()->addErrorMessage($message);
            }
        } else {
            $id = (int)$this->params('id');
            if(!$id) {
                return $this->redirect()->toRoute('zfcadmin/news/categories');
            }

            $item = $this->objectManager
                ->getRepository('\News\Entity\Category')
                ->findOneBy(array('id' => $id));

            if (!$item) {
                $this->flashMessenger()->addErrorMessage(sprintf('Категория с идентификатором "%s" не найдена', $id));
                return $this->redirect()->toRoute('zfcadmin/news/categories');
            }

            // Fill form data.
            $form->bind($item);
            return array('form' => $form);
        }
        
        return array(
            'form' => $form
        );
    }
}