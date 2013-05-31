<?php
/**
* SDIS 62
*
* @category SDIS62
* @package SDIS62_Model_Mapper_Doctrine_Abstract
*/

 /**
* Abstract class for mapper class.
*
* @category SDIS62
* @package SDIS62_Model_Mapper_Doctrine_Abstract
*/
abstract class SDIS62_Model_Mapper_Doctrine_Abstract
{
	/**
	* Entity manager object
	*
	* @var Doctrine\ORM\EntityManager
	*/
	public static $em;
	
	/**
	* Initialise the entity manager
	*
	* @params string $type_db
	* @params string $type_orm
	* @params Array $connOpt
	* @params Doctrine\ORM\Configuration $connDoc
	*/
	public static function createEntityManager($type_db, $type_orm, $connOpt, $connDoc)
	{
		SDIS62_Model_DAO_Abstract::$type_db = $type_db;
		SDIS62_Model_DAO_Abstract::$type_orm = $type_orm;
		self::$em = Doctrine\ORM\EntityManager::create($connOpt, $connDoc);
	}
	
	/**
	* Make the entity manager for a type of entity
	*
	* @params Array $infos
	* @params string $class_entity
	*/
	public static function makeEm($infos, $class_entity)
	{
		$factory = self::$em->getMetadataFactory();
		$metadata = new Doctrine\ORM\Mapping\ClassMetadata($class_entity);
		$factory->setMetadataFor($class_entity, $metadata);
		
		$metadata = self::$em->getClassMetadata($infos['classe']);
		$metadata->setTableName($infos['table']);
		$metadata->setIdentifier($infos['identifier']);
		foreach($infos['colonnes'] as $col)
		{
			if(!isset($infos['mappingType']))
			{
				$metadata->addInheritedFieldMapping($col);
			}
			else
			{
				$fc = 'map'.$infos['mappingType'];
				$metadata->$fc($col);
			}
			$metadata->reflFields[$col['fieldName']] = new ReflectionProperty(new $class_entity, $col['fieldName']);
		}
		$metadata->setIdGenerator(new Doctrine\ORM\Id\AssignedGenerator);
	}

	public static function afficheDatabase()
	{
		print_r(self::$em->createQuery("SELECT p.primary, p.nom, p.prenom FROM Application_Model_Entity_Personne p")->getResult());
		print_r(self::$em->createQuery("SELECT o.primary, o.label, o.idPersonne FROM Application_Model_Entity_Objet o")->getResult());
	}
}