<?php

use addons\Gdzb\backend\assets\SpecialAppAsset;

SpecialAppAsset::register($this);
$baseStaticUrl = $this->getAssetManager()->getBundle(SpecialAppAsset::className())->baseUrl;

$this->beginPage()

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
    <script type="text/javascript">
        var baseStaticUrl = '<?= $baseStaticUrl; ?>';
        var attrs = <?= \GuzzleHttp\json_encode($model->data) ?>
    </script>
</head>
<body onselectstart="return false">
<?php $this->beginBody() ?>
<div class="container">
    <div class="top-box clf">
        <div class="top-box-l fl">
            <div class="top-box-l-basics clf">
                <input type="hidden" id="special-id" name="special-id" value="<?= $model->id ?>">
                <div class="basics-name fl clf">
                    <div class="title fl">*专题名称</div>
                    <div class="value fl">
                        <input type="text" id="special-name" value="<?= $model->name ?>">
                    </div>
                </div>
                <div class="basics-url fl clf">
                    <div class="title fl">*URL设置</div>
                    <div class="value fl">
                        <input type="text" id="special-url" value="<?= $model->url ?>">
                    </div>
                </div>
            </div>

            <div class="tdk-box clf">
                <div class="tdk-l fl">
                    <div class="tdk-t clf">
                        <div class="title fl">Title</div>
                        <div class="value fl">
                            <textarea name="" id="title"><?= $model->title ?></textarea>
                        </div>
                    </div>

                    <div class="tdk-k clf">
                        <div class="title fl">Keywords</div>
                        <div class="value fl">
                            <textarea name="" id="keyword"><?= $model->keywords ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="tdk-r fl">
                    <div class="tdk-d clf">
                        <div class="title fl">Description</div>
                        <div class="value fl">
                            <textarea name="" id="description"><?= $model->description ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="top-box-r fr">
            <div class="icon" onclick="openTdk()"></div>
            <div class="preview" onclick="preview()">预览</div>
        </div>
    </div>

    <div class="content clf">
        <div class="content-l fl">
            <div class="material">素材</div>
            <div class="classify">
                <div class="classify-child classify-text active">
                    <div class="icon"></div>
                    <div class="text">文字</div>
                </div>

                <div class="classify-child classify-img">
                    <div class="icon"></div>
                    <div class="text">图片</div>
                </div>

                <div class="classify-child classify-video">
                    <div class="icon"></div>
                    <div class="text">视频</div>
                </div>
            </div>
        </div>

        <div class="content-m fl">
            <!-- <div class="switch clf">
              <div class="bgColor fl">
                <span class="fl switchMobile" onclick="togglePc()">移动</span>
                <span class="fl"> / </span>
                <span class="fl switchPc" onclick="togglePc()">pc</span>
              </div>
            </div> -->

            <div class="middle-layer mobile">
                <!-- 文字模板 -->
                <div class="template-text"></div>
            </div>
        </div>

        <div class="content-r fl">
            <div class="bgColor">
                <div class="content-r-child control-text">
                    <div class="align">
                        <div class="attr">
                            <div class="attr-child clf text-color">
                                <div class="text fl">文字颜色</div>
                                <div class="select fl">
                                    <div class="default clf">
                                        <div class="value fl">
                                            <div class="a fl">A</div>
                                            <div class="colorBlock fl"></div>
                                        </div>
                                        <i class="icon fl" onclick="select(event)"></i>
                                    </div>

                                    <div class="option-box">
                                        <!-- <div class="option">黑</div> -->
                                    </div>
                                </div>
                            </div>

                            <div class="attr-child clf">
                                <div class="text fl"></div>
                                <div class="select fl fontSize">
                                    <input type="text" class="colorIpt" onblur=colorOnBlur(event) maxlength="9">
                                </div>
                            </div>

                            <div class="attr-child clf">
                                <div class="text fl">文字字体</div>
                                <div class="select fl">
                                    <div class="default clf">
                                        <div class="value fl">微软雅黑</div>
                                        <i class="icon fl" onclick="select(event)"></i>
                                    </div>

                                    <div class="option-box">
                                        <div class="option">微软雅黑</div>
                                        <div class="option">黑体</div>
                                    </div>
                                </div>
                            </div>

                            <div class="attr-child clf">
                                <div class="text fl">文字大小</div>
                                <div class="select fl">
                                    <div class="default clf">
                                        <div class="value fl font-size">12</div>
                                        <i class="icon fl" onclick="select(event)"></i>
                                    </div>

                                    <div class="option-box">
                                        <div class="option">12</div>
                                        <div class="option">13</div>
                                        <div class="option">14</div>
                                        <div class="option">16</div>
                                        <div class="option">18</div>
                                        <div class="option">20</div>
                                        <div class="option">25</div>
                                        <div class="option">30</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="attr">
                            <div class="align clf">
                                <div class="attr-2 attr-bold fl">
                                    <div class="icon"></div>
                                    <div class="text">加粗</div>
                                </div>

                                <div class="attr-2 attr-i fl">
                                    <div class="icon"></div>
                                    <div class="text">斜体</div>
                                </div>

                                <div class="attr-2 attr-underline fl">
                                    <div class="icon"></div>
                                    <div class="text">下划线</div>
                                </div>
                            </div>
                        </div>

                        <div class="attr">
                            <div class="align clf">
                                <div class="attr-4 fl align-justify" onclick="alignType(event)"></div>
                                <div class="attr-4 fl align-left" onclick="alignType(event)"></div>
                                <div class="attr-4 fl align-right" onclick="alignType(event)"></div>
                                <div class="attr-4 fl align-center" onclick="alignType(event)"></div>
                            </div>
                        </div>

                        <div class="attr-3 clf">
                            <div class="text fl">链接</div>
                            <input type="text fl" class="ipt">
                        </div>
                    </div>
                </div>


                <div class="content-r-child control-img">
                    <div class="align">
                        <div class="attr clf">
                            <div class="text fl">长</div>
                            <input type="number" class="ipt img-width fl">
                            <div class="px fl">px</div>
                        </div>

                        <div class="attr clf">
                            <div class="text fl">宽</div>
                            <input type="number" class="ipt img-height fl">
                            <div class="px fl">px</div>
                        </div>

                        <div class="attr clf">
                            <div class="text fl">链接</div>
                            <input type="text" class="ipt fl width">
                        </div>
                    </div>
                </div>

                <div class="content-r-child control-video">
                    <div class="align">
                        <div class="attr clf">
                            <div class="text fl">长</div>
                            <input type="number" class="ipt fl">
                            <div class="px fl">px</div>
                        </div>

                        <div class="attr clf">
                            <div class="text fl">宽</div>
                            <input type="number" class="ipt fl">
                            <div class="px fl">px</div>
                        </div>

                        <div class="attr clf">
                            <div class="text fl">链接</div>
                            <input type="text" class="ipt fl width">
                        </div>

                        <div class="attr clf">
                            <div class="text fl width50">视频链接</div>
                            <input type="text" class="ipt fl width">
                        </div>
                    </div>
                </div>
            </div>

            <div class="save" onclick="save()">保存网页</div>
        </div>

    </div>
</div>

<div class="popup">
    <div class="clone">
        <div class="effect">
            <span>预览效果</span>

            <!-- <div class="switch">
              <span class="fl switchPc" onclick="togglePc2()">pc</span>
              <span class="fl"> / </span>
              <span class="fl switchMobile" onclick="togglePc2()">移动</span>
            </div> -->

            <span class="close" onclick="closeClone()">x</span>
        </div>

        <div class="clone-content"></div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>