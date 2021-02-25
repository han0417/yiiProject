<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\web\View;
//use fedemotta\datatables\DataTables;
use yii\grid\GridView;
use yii\grid\DataColumn;

/* @var $this yii\web\View */

$this->title = $name;

//$script = <<< JS

    // $(document).ready(function() {
    //     //$('#mass_table').DataTable();
    //     getMassData();
    //     function getMassData(){
    //         let url_string = window.location.href;
    //         let id = paramSplit(url_string);
    //         $.ajax({
    //             type:'GET',
    //             data:{id:id},
    //             url:'mass',
    //             dataType:'json',
    //             success: function(data) {
    //                 console.log(data);
    //                 for (let x in data){
    //                     //若該種類彌撒的thead tbody尚未建立 則建立
    //                     if($('#type'+ data[x]['type_id'] +'_thead').length==0){
    //                         $('#mass_table').append("<thead id='type"+ data[x]['type_id'] +"_thead'><td>"+ data[x]['type'] +"</td></thead>");
    //                         $('#mass_table').append("<tbody id='type"+ data[x]['type_id'] +"_tbody'></tbody>");
    //                         $('#type'+data[x]['type_id']+'_tbody').append("<tr><th>日期</th><th>時間</th><th>語言</th>");
    //                     }
    //                     //將彌撒資料塞進對應的tbody當中
    //                     $('#type'+ data[x]['type_id'] +'_tbody').append("<tr><td>"+ data[x]['week'] + data[x]['day'] +"</td><td>" + 
                        
    //                     data[x]['start_time'] + "-" + data[x]['end_time'] + "</td><td>" + data[x]['language'] + "</td>")
    //                 }
    //             },
    //             //拋錯
    //             error:function (XMLHttpRequest, textStatus, errorThrown) {
    //                 alert('error: ' + errorThrown);
    //             }
    //         });
    //     }
    //     //切網址取得參數
    //     function paramSplit(url){
    //         let array=url.split('/');
    //         let length=array.length;
    //         let id=array[length-1];
    //         return id;
    //     }
    // });

//JS; 

//$this->registerJs($script, View::POS_END);
?>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v9.0&appId=1267865780241703&autoLogAppEvents=1" nonce="JiwrspJ3"></script>
    <script src="https://www.line-website.com/social-plugins/js/thirdparty/loader.min.js" async="async" defer="defer"></script>
    <div class="site-index">

    <div class="jumbotron">
        <img src="<?php echo $img; ?>"></img>
        <h1><?= Html::encode($this->title); ?></h1>

        <p class="lead"></p>

    </div>
    <div class="row" style="text-align:center;">
        <div class="col-md-12">
            <div class="body-content" id="churches" style="margin:auto;">
                <div><?php echo $priest;?></div>
                <div><a href="https://www.google.com.tw/maps/place/<?php echo $address; ?>" target="_blank"><?php echo $address; ?></a></div>
                <div><a href="tel:+886-<?php echo $phone; ?>" ><?php echo $phone; ?></a></div>
                <div><a href="<?php echo $url; ?>" ><?php echo $url; ?></a></div>
                
                
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="fb-share-button" data-href="https://mai-li.app/" data-layout="button" data-size="large"><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fmai-li.app%2F&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore"></a></div>
            <div class="line-it-button" data-lang="zh_Hant" data-type="share-c" data-ver="3" data-url="https://mai-li.app/" data-color="default" data-size="small" data-count="false" style="display: none;"></div>
        </div>
    </div>
    <div class="col-md-12">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],

                'week',
                //將開始時間及結束時間組起來
                [
                    'value' => function ($dataProvider){
                        return $dataProvider->start_time.'~'.$dataProvider->end_time;
                    },
                    // 'format' => 'text',
                    // 'label' => '時間',
                ],
                //轉換成中文的星期
                [
                    'attribute' => 'day',
                    'value' => function ($dataProvider){
                        $week=['日', '一', '二', '三', '四', '五', '六'];
                        return '星期'.$week[$dataProvider->day];
                    },
                    //'format' => 'text',
                    //'label' => '星期',
                ],
                'types.name',
                'languages.name',            
                'notes',

                //['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>

</div>
