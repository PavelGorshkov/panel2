<?php
namespace app\modules\user\forms;

use app\modules\core\interfaces\SaveModelInterface;
use yii\base\Model;
use yii\db\ActiveRecordInterface;


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

    /**
     * @param $model Model|ActiveRecordInterface
     */
    public function processingModel($model) {


    }


    public function rules() {

        return [
            [],
        ];
    }


    /**
     * @param Model $user
     *
     * @return bool|void
     */
    public function processingData(Model $user) {


    }
}