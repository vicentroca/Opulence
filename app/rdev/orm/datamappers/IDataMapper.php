<?php
/**
 * Copyright (C) 2014 David Young
 *
 * Defines the interface for data mappers to implement
 */
namespace RDev\ORM\DataMappers;
use RDev\ORM;

interface IDataMapper
{
    /**
     * Adds an entity to the database
     *
     * @param ORM\IEntity $entity The entity to add
     * @throws ORM\ORMException Thrown if the entity couldn't be added
     */
    public function add(ORM\IEntity &$entity);

    /**
     * Deletes an entity
     *
     * @param ORM\IEntity $entity The entity to delete
     * @throws ORM\ORMException Thrown if the entity couldn't be deleted
     */
    public function delete(ORM\IEntity &$entity);

    /**
     * Gets all the entities
     *
     * @return array The list of all the entities
     */
    public function getAll();

    /**
     * Gets the entity with the input Id
     *
     * @param int|string $id The Id of the entity we're searching for
     * @return ORM\IEntity The entity with the input Id
     * @throws ORM\ORMException Thrown if there was no entity with the input Id
     */
    public function getById($id);

    /**
     * Saves any changes made to an entity
     *
     * @param ORM\IEntity $entity The entity to save
     * @throws ORM\ORMException Thrown if the entity couldn't be saved
     */
    public function update(ORM\IEntity &$entity);
} 