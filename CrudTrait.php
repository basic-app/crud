<?php
/**
 * @author Basic App Dev Team
 * @license MIT
 * @link http://basic-app.com
 */
namespace BasicApp\Crud;

trait CrudTrait
{

    public function index()
    {
        return $this->createCrudAction(IndexAction::class, [
            'view' => $this->indexTemplate ?? 'index',
            'searchModelClass' => $this->searchModelClass ?? null,
            'perPage' => $this->perPage ?? null,
            'orderBy' => $this->orderBy ?? null,
            'parentKey' => $this->parentKey ?? null,
            'beforeFind' => $this->beforeFind ?? null
        ])->run();
    }

    public function create()
    {
        return $this->createCrudAction(CreateAction::class, [
            'view' => 'create',
            'searchModelClass' => property_exists($this, 'searchModelClass') ? $this->searchModelClass : null,
            'parentKey' => property_exists($this, 'parentKey') ? $this->parentKey : null
        ])->run();
    }

    public function update()
    {
        return $this->createCrudAction(UpdateAction::class, [
            'view' => 'update',
            'searchModelClass' => property_exists($this, 'searchModelClass') ? $this->searchModelClass : null,
            'parentKey' => property_exists($this, 'parentKey') ? $this->parentKey : null
        ])->run();
    }

    public function view()
    {
        return $this->createCrudAction(ViewAction::class, [
            'view' => 'view',
            'searchModelClass' => property_exists($this, 'searchModelClass') ? $this->searchModelClass : null,
            'parentKey' => property_exists($this, 'parentKey') ? $this->parentKey : null             
        ])->run();        
    }

    public function delete()
    {
        return $this->createCrudAction(DeleteAction::class, [
            'searchModelClass' => property_exists($this, 'searchModelClass') ? $this->searchModelClass : null,
            'parentKey' => property_exists($this, 'parentKey') ? $this->parentKey : null
        ])->run();
    }

}