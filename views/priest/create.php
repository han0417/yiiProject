<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Priests */

$this->title = 'Create Priests';
$this->params['breadcrumbs'][] = ['label' => 'Priests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="priests-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
