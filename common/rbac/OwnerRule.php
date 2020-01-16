<?php


namespace common\rbac;


use yii\rbac\Rule;

class OwnerRule extends Rule
{
    /**
     * Содержит название моделей, с которыми будет работать правило.
     * @var array
     */
    private $models = [
        'task',
        'activity'
    ];

    /**
     * Название правила
     * @var string
     */
    public $name = 'isActivityOwner';

    /**
     * Executes the rule.
     *
     * @param string|int $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param \yii\rbac\Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[CheckAccessInterface::checkAccess()]].
     * @return bool a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params):bool
    {
        foreach ($this->models as $modelName) {
            if (isset($params[$modelName])) {
                return $params[$modelName]->author_id === $user;
            }
        }
        return false;
    }
}