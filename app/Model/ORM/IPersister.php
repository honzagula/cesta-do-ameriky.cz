<?php

namespace App\Model;

interface IPersister
{
    public function flush();

    public function persist($entity);

    public function save($entity);

    public function remove($entity);

    public function delete($entity);
}