<?php
/* @var $this \app\modules\core\components\View */
/* @var $info User */
/* @var $profile UserProfile */

use app\modules\user\models\User;
use app\modules\user\models\UserProfile;

$attributes = [
	'user::username'=>false,
	'profile::full_name'=>false,
	'user::email'=>'getEmail',
	'profile::phone'=>false,
	'profile::about'=>false,
	'user::status'=>'getStatus',
	'profile::avatar'=>'getAvatarImage',
];
?>
<table class="table table-hover">
	<tbody>
	<?php foreach ($attributes as $attribute => $method):
			$attr = explode('::', $attribute);
			$model = null;

			switch ($attr[0]) {

				case 'user':
					$model = $info;
					break;

				case 'profile':
					$model = $profile;
					break;
			}

			$attr = $attr[1];
			if ($model === null) continue;
	?>
		<tr>
			<th><?=$model->getAttributeLabel($attr);?></th>
			<td><?=$method?$model->$method():$model->$attr;?></td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>