<?

namespace News\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use News\Controller\CategoryController;

class CategoryControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $objectManager = $serviceLocator->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        return new CategoryController($objectManager);
    }
}