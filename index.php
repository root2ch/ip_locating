'''
	+-----------------------------------+
	|  author  : church		    |
	|  Blog    : evilchurch.cc	    |
	|  Github  : github.com/evilchurch  |
	+-----------------------------------+
'''


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IP定位</title>
    <link rel="shortcut icon" href="image/favi.ico" />
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrapValidator.min.css"/>
    <link rel="stylesheet" href="css/flat-ui.css"/>

    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="js/bootstrapValidator.min.js"></script>
    <script src="js/flat-ui.min.js"></script>
    <script src="js/application.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=申请的AK"></script>
</head>

<body>

    <div class="container" style="border-radius: 5px; border: 1px solid #e2e2e9; margin-top: 100px; width: 80%;">
        <div class="col-lg-6 col-lg-offset-3">
            <div class="page-header" style="text-align: center;">
                <h4 style="color: grey;">IP定位</h4>
            </div><br>

            <form id="defaultForm" method="GET" class="form-horizontal" action="">
                <div class="form-group">                    
                    <div class="col-lg-12">
                        <input type="text" class="form-control input-lg" placeholder="IP地址" name="ip" />
                    </div>
                </div><br>

                <div class="form-group">
                    <div class="col-lg-9 col-lg-offset-4">
                        <button type="submit" class="btn btn-wide btn-success">查询</button>
                    </div>
                </div>
            </form>
        </div>
    </div><br>

<center>
    <?php
        $ak = ''; // 填写自己申请的AK
        if (isset($_GET['ip'])) {
            $req = file_get_contents(
                'https://api.map.baidu.com/highacciploc/v1?qcip='.$_GET['ip'].'&ak='.$ak.'&qterm=pc&extensions=1&coord=bd09ll&callback_type=json');
            if (!empty($req)) {
                $res = json_decode($req, true);
                foreach ($res as $key => $value) {
                    if ($key == 'result') {
                        foreach ($value as $key1 => $value1) {
                            if ($key1 == 'error') {
                                if ($value1 != '161') {
                                    echo '<div class="alert alert-warning" style="width: 70%;">
                                              <a class="close" data-dismiss="alert">&times;</a>
                                              <strong>定位失败！</strong> 请确认IP地址后重新进行定位！
                                          </div>';
                                }
                            }
                        }
                    }

                    foreach ($value as $key1 => $value1) {                        
                        if ($key1 == 'formatted_address') {
                            $loc = $value1;
                            echo '<div class="alert alert-info" style="width: 70%;">
                                      <a class="close" data-dismiss="alert">&times;</a>
                                      <strong>定位成功！</strong> 地址为：'.$value1.'
                                  </div>';
                        }
                        if ($key1 == 'business') {
                            echo '<div class="alert alert-info" style="width: 70%;">
                                      <a class="close" data-dismiss="alert">&times;</a>
                                      详细地址为：'.$loc.$value1.'
                                  </div>';
                        }
                        
                        if ($key1 == 'location') {
                            foreach ($value1 as $key2 => $value2) {
                                if ($key2 == 'lat') {
                                    $lat = $value2;
                                }
                                if ($key2 == 'lng') {
                                    $lng = $value2;
                                }
                            }
                        }
                    }
                }
            }
        }
    ?>

    <div id="map" style="width: 60%; height: 300px;"></div>
</center>


    <script type="text/javascript">
        var map = new BMap.Map("map");
        var lng = <?php echo $lng;?>;
        var lat = <?php echo $lat;?>;
        
        map.centerAndZoom(new BMap.Point(116.331398,39.897445),11);
        map.enableScrollWheelZoom(true);
            
        if (lng != "" && lat != ""){
            map.clearOverlays(); 
            var new_point = new BMap.Point(lng, lat);
            var marker = new BMap.Marker(new_point);  // 创建标注
            map.addOverlay(marker);              // 将标注添加到地图中
            map.panTo(new_point);      
        }
    </script>

    <script type="text/javascript">
        $(function() {
            $('#defaultForm').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    ip: {
                        validators: {
                            notEmpty: {
                                message: 'The ip is required and can\'t be empty'
                            },
                            ip: {
                                message: 'The input is not ip'
                            }
                        }
                    }
                }
            })        
        });
    </script>
    
    <footer><p style="text-align: center; color: grey; padding-top: 100px;">© 2016 by church. All rights reserved.</p></footer>
    
</body>
</html>
