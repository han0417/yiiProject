<?php

use yii\web\View;

/* @var $this yii\web\View */

$this->title = 'maili';
$script = <<< JS

    $(document).ready(function() {
  
        $('#search').click(function(){
            let address=$('#address').val();
            $.ajax({
                type:'GET',
                data:{address:address},
                url:'site/search',
                dataType:'json',
                success: function(data) {
                    $('#churches').children().remove();
                    for(var x in data){
                        $('#churches').append("<a href='site/" + data[x]['id'] + "'><p>" + data[x]['name'] + "</p></a>");
                    }
                },
                error:function (XMLHttpRequest, textStatus, errorThrown) {
                    alert('error: ' + errorThrown);
                }
            });
        });
    });

JS; 

$this->registerJs($script, View::POS_END);
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>麥力找教堂</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>
        <input class="form-control" id="address">
        <p><a class="btn btn-lg btn-success" id="search">搜尋</a></p>

    </div>
    <div class="row" style="text-align:center;">
        <div class="col-md-12">
            <div class="body-content" id="churches" style="margin:auto;">
            
            </div>
        </div>
    </div>

</div>
