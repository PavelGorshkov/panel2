<?php
namespace app\modules\user\forms;

use app\modules\core\interfaces\SaveModelInterface;
use yii\base\Model;


class UserFormModel extends Model implements SaveModelInterface
{
    public $id;

    public $username;

    public $email;

    public $email_confirm;

    public $full_name;

    public $access_level;

    public $status;

    public $about;

    public $phone;


    public function rules() {

        return [
            [],
        ];
    }


    /**
     * @param Model $model
     * @return bool|void
     */
    public function processingData(Model $model) {


    }
}