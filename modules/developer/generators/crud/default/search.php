<?php

use app\modules\developer\generators\crud\Generator;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $modelAlias = $modelClass . 'Model';
}

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) ?>;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\core\interfaces\SearchModelInterface;
<?php if (StringHelper::dirname($generator->modelClass) !== StringHelper::dirname($generator->searchModelClass)): ?>
use <?= ltrim($generator->modelClass, '\\') . (isset($modelAlias) ? " as $modelAlias" : "") ?>;
<?php endif; ?>
/**
 * <?= $searchModelClass ?> represents the model
 * behind the search form of `<?= $generator->modelClass ?>`.
 *
 * Class <?= $searchModelClass . "\n" ?>
 * @package <?= StringHelper::dirname(ltrim($generator->searchModelClass, '\\')) . "\n" ?>
 */
class <?= $searchModelClass ?> extends Model implements SearchModelInterface
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

        ];
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = <?= isset($modelAlias) ? $modelAlias : $modelClass ?>::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {

            return $dataProvider;
        }

        /*
        $query
            ->andFilterWhere(['like', 'field', $this->field]);
        */

        return $dataProvider;
    }
}
