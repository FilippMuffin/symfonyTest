<?php


namespace App\Serializer\Denormalizer;


use App\Entity\Category;
use App\Entity\Product;
use App\Helper\MoneyHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ProductDenormalizer implements DenormalizerInterface
{

    /** @var EntityManagerInterface  */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        foreach ($data as $datum) {
            /** @var Product $entity */
            if ($entity = $this->getEntity($datum, $type)) {

                array_key_exists('name', $datum) ?
                    $entity->setName($datum['name']) :
                    ''
                ;
                array_key_exists('external_id', $datum) ?
                    $entity->setExternalID($datum['external_id']) :
                    ''
                ;
                array_key_exists('price', $datum) ?
                    $entity->setPrice(MoneyHelper::boolToInt($datum['price'])) :
                    ''
                ;

                if (array_key_exists('category_id', $datum)) {
                    $categories = $this->getCategoryRepository()->findBy(['id' => $datum['category_id']]);
                    $entity->setCategories($categories);
                }

                array_key_exists('quantity', $datum) ?
                    $entity->setBalance($datum['quantity']) :
                    ''
                ;

                $this->save($entity);
                $denormalizedEntities[] = $entity;
            }
        }

        return $denormalizedEntities;
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return new $type instanceof Product;
    }

    private function getEntity($data, $class)
    {
        $entity = false;
        if (key_exists('id', $data) && $data['id']) {
            $entity = $this->getRepository()->find($data['id']);
        }
        if (!$entity) {
            $entity = new $class();
        }

        return $entity;
    }

    private function getRepository()
    {
        return $this->em->getRepository(Product::class);
    }

    private function getCategoryRepository()
    {
        return $this->em->getRepository(Category::class);
    }

    private function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }
}