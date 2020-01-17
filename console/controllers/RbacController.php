<?php


namespace console\controllers;


use common\rbac\OwnerRule;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $authManager = Yii::$app->authManager;
        $authManager->removeAll();

        // Создаём роль админа
        $role = $authManager->createRole('admin');
        $role->description = 'Администратор';
        try {
            $authManager->add($role);
        } catch (\Exception $e) {
            echo 'Role has not been added';
        }

        // Cоздаём роль простого пользователя
        $role = $authManager->createRole('simple');
        $role->description = 'Пользователь';
        try {
            $authManager->add($role);
        } catch (\Exception $e) {
            echo 'Role has not been added';
        }

        // Правило проверки является ли пользователь владельцем модели
        $ownerRule = new OwnerRule();
        $authManager->add($ownerRule);

        // Разрешение создавать задачи
        $createTask = $authManager->createPermission('create_task');
        $createTask->description = 'Создание задачи';

        // Разрешение просмотра задачи
        $viewTask = $authManager->createPermission('view_task');
        $viewTask->description = 'Просмотр задачи';

        $viewOwnTask = $authManager->createPermission('view_own_task');
        $viewOwnTask->description = 'Просмотр собственной задачи';
        $viewOwnTask->ruleName = $ownerRule->name;

        // Разрешение редактирования задачи
        $editTask = $authManager->createPermission('edit_task');
        $editTask->description = 'Редактирование задачи';

        $editOwnTask = $authManager->createPermission('edit_own_task');
        $editOwnTask->description = 'Редактирование собственнй задачи';
        $editOwnTask->ruleName = $ownerRule->name;

        // Разрешение удалять задачу
        $deleteTask = $authManager->createPermission('delete_task');
        $deleteTask->description = 'Удаление задачи';

        // Разрешение удалять собственную задачу
        $deleteOwnTask = $authManager->createPermission('delete_own_task');
        $deleteOwnTask->description = 'Удаление собственной задачи';
        $deleteOwnTask->ruleName = $ownerRule->name;

        $createUser = $authManager->createPermission('create_user');
        $createUser->description = 'Создание пользователя';

        // Разрешение просмотра пользователя
        $viewUser = $authManager->createPermission('view_user');
        $viewUser->description = 'Просмотр пользователя';

        // Разрешение изменения пользователей
        $editUser = $authManager->createPermission('edit_user');
        $editUser->description = 'Редактирование профиля';

        $deleteUser = $authManager->createPermission('delete_user');
        $deleteUser->description = 'Удаление пользователя';

        $authManager->add($createTask);
        $authManager->add($viewTask);
        $authManager->add($viewOwnTask);
        $authManager->add($editTask);
        $authManager->add($editOwnTask);
        $authManager->add($deleteTask);
        $authManager->add($deleteOwnTask);
        $authManager->add($createUser);
        $authManager->add($viewUser);
        $authManager->add($editUser);
        $authManager->add($deleteUser);

        $authManager->addChild($viewOwnTask, $viewTask);
        $authManager->addChild($editOwnTask, $editTask);
        $authManager->addChild($deleteOwnTask, $deleteTask);

        $role = $authManager->getRole('simple');
        $authManager->addChild($role, $createTask);
        $authManager->addChild($role, $viewOwnTask);
        $authManager->addChild($role, $editOwnTask);
        $authManager->addChild($role, $deleteOwnTask);

        $role = $authManager->getRole('admin');
        $authManager->addChild($role, $viewTask);
        $authManager->addChild($role, $editTask);
        $authManager->addChild($role, $deleteTask);
        $authManager->addChild($role, $createUser);
        $authManager->addChild($role, $viewUser);
        $authManager->addChild($role, $editUser);
        $authManager->addChild($role, $deleteUser);

        // Унаследуем администратору все разрешения простого пользователя
        $authManager->addChild($authManager->getRole('admin'), $authManager->getRole('simple'));

        // Дадим права администратора пользователю с id = 1
        $authManager->assign($authManager->getRole('admin'), 1);
    }
}
