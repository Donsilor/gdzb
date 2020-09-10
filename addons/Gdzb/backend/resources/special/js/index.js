// 颜色
var color = ['#333333','#ffffff','#ff0000','#cccccc','#ffff00'];
for(var c=0; c<color.length; c++){
  var div = document.createElement('div');
  div.className = 'option';
  div.style.backgroundColor = color[c]; 
  $('.text-color .option-box').append(div)
}

$('.colorIpt').val(color[0])

// 字体
// var typeface = [''];
// for(var c=0; t<typeface.length; c++){
//   var div = document.createElement('div');
//   div.className = 'option';
//   div.style.backgroundColor = color[c]; 
//   $('.text-color .option-box').append(div)
// }

// 下拉框选项
function select(e){
  var option = $(e.target).parent().next();
  option.css('display') == 'none' ? option.slideDown().show() : option.slideUp().hide()
}

// 选择下拉选项
$('.option-box .option').not('.text-color .option').click(function() {
  $(this).parent().prev().children('.value').text($(this).text())
  $(this).parent().slideUp().hide()
})

// 选择颜色
$('.text-color .option').click(function() {
  var index = $(this).index();
  $(this).parent().prev().children('.value').children('.colorBlock').css('backgroundColor', color[index])
  $(this).parent().slideUp().hide()
  $('.colorIpt').val(color[index])
})

// 颜色输入框
function colorOnBlur(e) {
  var val = $(e.target).val();
  var reg = /^#([a-fA-F\d]{6}|[a-fA-F\d]{6}[0-9]{2}|[a-fA-F\d]{3})$/;
  if(val && !reg.test(val)){
    // $(e.target).select()
    alert('输入有误，请输入正确颜色格式（如：#000000）')
    $(e.target).val('');
    $(e.target).focus();
  }
}

// 加粗、斜体、下划线
$('.control-text .attr-2').click(function() {
  $(this).toggleClass('active')
})

// 对齐方式
function alignType(e) {
  $(e.target).addClass('active').siblings().removeClass('active')
}

// 要添加的位置
var content = $('.middle-layer'),
contentWidth = parseInt(content.innerWidth()),
contentHeight = parseInt(content.innerHeight()),

// 添加新元素的属性
attr = {
  type: '',
  width: '100px',
  height: '20px',
  top: '',
  left: '',
  color: '',
  'font-face': '',
  'font-size': '',
  'font-weight': '',
  'font-style': '',
  'text-decoration': '',
  'text-align': '',
  'z-index': 0,
  content: '',
  url: ''
},
// 鼠标位置
mouseX = '',
mouseY = '',
idNum = 0,
divStyle = '',
// 返回数据集合
data = {
  'special-name' : '',
  'special=url': '',
  'tdk': {'title': '','description': '','keywords':''},
  'attrs': []
},
timer;

// 添加文字
$('.classify-text').on('mousedown', function() {
  $(this).addClass('active').siblings().removeClass('active')
  $('.control-text').show().siblings().hide();
  
  var tem = $('.template-text');
  attr.width = '160px';
  attr.height = '24px';
  tem.css({'width': attr.width, 'height': attr.height})

  $('.content').on('mousemove', function(e) {
    mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
    mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标
    
    attr.left=mouseX - parseInt(attr.width)/2;
    attr.top=mouseY - parseInt(attr.height)/2;

    if(attr.left < 0){
      attr.left = 0
    }
    if(attr.left > (parseInt(contentWidth) - parseInt(attr.width))){
      attr.left = parseInt(contentWidth) - parseInt(attr.width)
    }
    if(attr.top < 0){
      attr.top = 0
    }
    if(attr.top > (parseInt(contentHeight) - parseInt(attr.height))){
      attr.top = parseInt(contentHeight) - parseInt(attr.height)
    }
    if(mouseX > 0){
      tem.show()
    }

    attr.left = (attr.left/contentWidth*100).toFixed(2) + '%';
    attr.top = attr.top + 'px';

    tem.css({'left': attr.left, 'top': attr.top})
  })

  $('.content').on('mouseup', function(e) {
    $('.content').off('mousemove')
    $('.template-text').hide()
    idNum++;
    attr['z-index']++;
    attr.type = 'text';

    divStyle ='top:'+attr.top
            +';left:'+attr.left
            +';width:'+attr.width
            +';height:'+attr.height
            +';background-color:#fff'
            +';z-index:'+attr['z-index'];

    div = `<div style='${divStyle}' class='text-box text-box-${idNum}' onclick='addMove(event)' ondblclick='edit(event)'>
              <div class='direction-box' onmousedown='moveImg(event)'>
                <div class='direction top' onmousedown='move(event, "top")'></div>
                <div class='direction down' onmousedown='move(event, "down")'></div>
                <div class='direction left' onmousedown='move(event, "left")'></div>
                <div class='direction right' onmousedown='move(event, "right")'></div>
                <div class='direction topLeft' onmousedown='move(event, "topLeft")'></div>
                <div class='direction topRight' onmousedown='move(event, "topRight")'></div>
                <div class='direction downLeft' onmousedown='move(event, "downLeft")'></div>
                <div class='direction downRight' onmousedown='move(event, "downRight")'></div>
              </div>
              <textarea type='text' class='ipt-text' onInput='inputText(event)' onfocus='onFocus(event)' onblur='onBlur(event)'></textarea>
          </div>`;
    $('.middle-layer').append(div)

    $('.content').off('mouseup')
    var obj = {};
    for(var at in attr){
      obj[at] = attr[at]
    }
    data.attrs.push(obj)
    return false
  })
})

// 编辑文本
function edit(e) {
  e.stopPropagation();
  clearTimeout(timer);

  $(e.target).children('.ipt-text').show().focus();
}

// 获取光标
function onFocus(e) {}

// 输入文本
function inputText(e) {
  // var index = $(e.target).parent().css('z-index');
  var align = $('.attr-4').hasClass('active');
  if(align){
    var index = $('.attr-4.active').index(),
        aligns = ['justify','left','right','center'];
    
    attr['text-align'] = aligns[index];
    console.log(454545,attr['text-align'])
  }

  attr['color'] = $('.colorBlock').css('backgroundColor');
  attr['font-size'] = $('.font-size').text()+'px';
  attr['font-weight'] = $('.attr-bold').hasClass('active') ? 'bold' : '';
  attr['font-style'] = $('.attr-i').hasClass('active') ? 'italic' : '';
  attr['text-decoration'] = $('.attr-underline').hasClass('active') ? 'underline' : '';

  $(e.target).parent().css({'font-size': attr['font-size'],'font-weight': attr['font-weight'],'font-style': attr['font-style'],'text-decoration': attr['text-decoration'],'text-align': attr['text-align'],'color': attr['color'],});
}

// 结束编辑
function onBlur(e) {
  var index = $(e.target).parent().css('z-index')-1;
  var text = $(e.target).val();
  data.attrs[index].content = text;
  data.attrs[index].color = attr['color'];
  data.attrs[index]['font-size'] = attr['font-size'];
  data.attrs[index]['font-weight'] = attr['font-weight'];
  data.attrs[index]['text-decoration'] = attr['text-decoration'];

  $(e.target).parent().html('<pre>'+text+'</pre>')
  if(text){
    console.log(888,$(e.target).parent())
    $(e.target).parent().children('pre').css('border', '2px solid red')
  }
}


// 增加图片
$('.classify-img').on('mousedown', function() {
  $(this).addClass('active').siblings().removeClass('active')
  $('.control-img').show().siblings().hide();

  var tem = $('.template-text');
  attr.width = '140px';
  attr.height = '140px';

  var imgWidth = $('.img-width').val(),imgHeight = $('.img-height').val();
  if(imgWidth && imgWidth>0){
    attr.width = parseInt(imgWidth)+'px';
  }

  if(imgHeight && imgHeight>0){
    attr.height = parseInt(imgHeight)+'px';
  }

  tem.css({'width': attr.width, 'height': attr.height})

  $('.content').on('mousemove', function(e) {
    mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
    mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

    attr.left=mouseX - parseInt(attr.width)/2;
    attr.top=mouseY - parseInt(attr.height)/2;
    
    if(attr.left < 0){
      attr.left = 0
    }
    if(attr.left > (parseInt(contentWidth) - parseInt(attr.width))){
      attr.left = parseInt(contentWidth) - parseInt(attr.width)
    }
    if(attr.top < 0){
      attr.top = 0
    }
    if(attr.top > (parseInt(contentHeight) - parseInt(attr.height))){
      attr.top = parseInt(contentHeight) - parseInt(attr.height)
    }
    if(mouseX > 0){
      tem.show()
    }

    attr.left = (attr.left/contentWidth*100).toFixed(2) + '%';
    attr.top = attr.top + 'px';
    
    tem.css({'left': attr.left, 'top': attr.top})

  })

  $('.content').on('mouseup', function(e) {
    $('.content').off('mousemove')
    $('.template-text').hide()

    idNum++;
    attr['z-index']++;
    attr.type = 'img';

    divStyle =
            'top:'+attr.top
            +';left:'+attr.left
            +';width:'+attr.width
            +';height:'+attr.height
            +';background:#fff url('+baseStaticUrl+'/img/icon/load.png) no-repeat center'
            +';background-size:60% 60%'
            +';z-index:'+attr['z-index']
            +';cursor:pointer'
            +';border:2px solid #999';

    div = `<div style='${divStyle}' class='img-box img-box-${idNum}' onclick='addMove(event)' ondblclick='loadImg(event)'>
          <div class='direction-box' onmousedown='moveImg(event)'>
            <div class='direction top' onmousedown='move(event, "top")'></div>
            <div class='direction down' onmousedown='move(event, "down")'></div>
            <div class='direction left' onmousedown='move(event, "left")'></div>
            <div class='direction right' onmousedown='move(event, "right")'></div>
            <div class='direction topLeft' onmousedown='move(event, "topLeft")'></div>
            <div class='direction topRight' onmousedown='move(event, "topRight")'></div>
            <div class='direction downLeft' onmousedown='move(event, "downLeft")'></div>
            <div class='direction downRight' onmousedown='move(event, "downRight")'></div>
          </div>
        </div>`;
    $('.middle-layer').append(div)

    $('.content').off('mouseup')
    var obj = {};
    for(var at in attr){
      obj[at] = attr[at]
    }
    data.attrs.push(obj)
    return false
  })
})

// 单击显示移动缩放工具
function addMove(e) {
  clearTimeout(timer);
  timer = setTimeout(function () {
    
    // if($(e.target).hasClass('img-box')){
      $('.direction-box').hide()
      $(e.target).children('.direction-box').show();
    // }
      
    if($(e.target).hasClass('direction-box')){
      $(e.target).hide();
    }
    
    e.stopPropagation()
  }, 300);
}

// 拖拽移动
function moveImg(e) {
  if($(e.target).hasClass('direction-box')){
    var box = $(e.target).parent(),
        index = box.css('z-index'),
        clientX = parseInt(e.pageX - box.offset().left);
        clientY = parseInt(e.pageY - box.offset().top);
        
    $(e.target).mousemove(function (e) {
      mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
      mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

      attr.left=mouseX - clientX;
      attr.top=mouseY - clientY;

      if(attr.left < 0){
        attr.left = 0
      }
      if(attr.left > (parseInt(contentWidth) - parseInt(attr.width))){
        attr.left = parseInt(contentWidth) - parseInt(attr.width)
      }
      if(attr.top < 0){
        attr.top = 0
      }
      if(attr.top > (parseInt(contentHeight) - parseInt(attr.height))){
        attr.top = parseInt(contentHeight) - parseInt(attr.height)
      }

      attr.left = (attr.left/contentWidth*100).toFixed(2) + '%';
      attr.top = attr.top + 'px';

      box.css({'left':attr.left, 'top':attr.top})
    })

    $(e.target).mouseup(function () {
      $(e.target).off('mousemove')
    })
  }

}

// 拖拽缩放
function move(e,direction) {
  var box = $(e.target).parents('.img-box').length ? $(e.target).parents('.img-box') : $(e.target).parents('.text-box')
      posX = parseInt(box.css('left')),
      posY = parseInt(box.css('top')),
      posW = parseInt(box.css('width')),
      posH = parseInt(box.css('height')),
      index = box.css('z-index'),
      posYH = posY + posH,
      posXW = posX + posW;
      
  switch (direction) {
    case 'top':
      $('.content').mousemove(function(e) {
        mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

        attr.top=mouseY;
        
        if(attr.top < 0){
          attr.top = 0
        }
        if(attr.top+4 > posYH){
          attr.top = posYH-4
        }

        var height = (posYH-attr.top)+'px';
        attr.top = attr.top + 'px';

        box.css({'top':attr.top,'height':height})
      })

      $('.content').mouseup(function () {
        $('.content').off('mousemove')
      })

      break;

    case 'down':
      $('.content').mousemove(function(e) {
        mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标
        var height = (mouseY-parseInt(attr.top))+'px';

        box.css({'height':height})
      })

      $('.content').mouseup(function () {
        $('.content').off('mousemove')
      })

      break;

    case 'left':
      $('.content').mousemove(function(e) {
        mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标

        attr.left = mouseX;

        if(attr.left < 0){
          attr.left = 0
        }
        if(attr.left+4 > posXW){
          attr.left = posXW -4
        }

        var width = (posXW-parseInt(attr.left))+'px';
        attr.left = attr.left + 'px';

        box.css({'left':attr.left,'width':width})
      })

      $('.content').mouseup(function () {
        $('.content').off('mousemove')
      })

      break;

      case 'right':
      $('.content').mousemove(function(e) {
        mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标

        var width = (mouseX-parseInt(attr.left));
        if(width > (contentWidth - parseInt(attr.left))){
          width = (contentWidth - attr.left) + 'px'
        }

        box.css({'width':width})
      })

      $('.content').mouseup(function () {
        $('.content').off('mousemove')
      })

      break;

    case 'topLeft':
      $('.content').mousemove(function(e) {
        mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
        mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

        attr.left = mouseX;
        attr.top=mouseY;
        
        if(attr.top < 0){
          attr.top = 0
        }
        if(attr.top+4 > posYH){
          attr.top = posYH-4
        }

        if(attr.left < 0){
          attr.left = 0
        }
        if(attr.left+4 > posXW){
          attr.left = posXW -4
        }

        var width = (posXW-parseInt(attr.left))+'px';
        var height = (posYH-attr.top)+'px';
        attr.top = attr.top + 'px';
        attr.left = attr.left + 'px';

        box.css({'left':attr.left,'top':attr.top,'width':width,'height':height})
      })

      $('.content').mouseup(function () {
        $('.content').off('mousemove')
      })

      break;

    case 'topRight':
      $('.content').mousemove(function(e) {
        mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
        mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

        attr.top=mouseY;
        
        if(attr.top < 0){
          attr.top = 0
        }
        if(attr.top+4 > posYH){
          attr.top = posYH-4
        }
        
        var width = (mouseX-parseInt(attr.left))+'px';
        var height = (posYH-attr.top)+'px';
        attr.top = attr.top + 'px';

        box.css({'top':attr.top,'width':width,'height':height})
      })

      $('.content').mouseup(function () {
        $('.content').off('mousemove')
      })

      break;

    case 'downLeft':
      $('.content').mousemove(function(e) {
        mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
        mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标
        
        attr.left = mouseX;
        
        if(attr.left < 0){
          attr.left = 0
        }
        if(attr.left+4 > posXW){
          attr.left = posXW -4
        }
        
        var width = (posXW-parseInt(attr.left))+'px';
        var height = (mouseY-parseInt(attr.top))+'px';
        attr.left = attr.left + 'px';

        box.css({'left':attr.left,'width':width,'height':height})
      })

      $('.content').mouseup(function () {
        $('.content').off('mousemove')
      })

      break;

    case 'downRight':
      $('.content').mousemove(function(e) {
        mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
        mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

        var width = (mouseX-parseInt(attr.left))+'px';
        var height = (mouseY-parseInt(attr.top))+'px';

        box.css({'width':width,'height':height})
      })

      $('.content').mouseup(function () {
        $('.content').off('mousemove')
      })
  }
  return false
}

// 双击上传
function loadImg(e) {
  e.stopPropagation();
  clearTimeout(timer);

  $(e.target).html(`<input type='file' class='ipt-img' accept='image/*' onchange='changeFile(event)'>`)
  $(e.target).children().click()
}

function changeFile(obj) {
  var that = this,
      file = obj.target.files,
      index = $(obj.target).parent().css('z-index')-1;
  
  // const isJPG =
  // file.type == 'image/jpeg'||
    // file.type == 'image/png'||
    // file.type == 'image/jpg'||
    // file.type == 'image/gif'||
    // file.type == 'image/tiff'||
    // file.type == 'image/raw'||
    // file.type == 'image/pcx'||
    // file.type == 'image/tga'||
    // file.type == 'image/exif'||
    // file.type == 'image/fpx'||
    // file.type == 'image/svg'||
    // file.type == 'image/psd'||
    // file.type == 'image/cdr'||
    // file.type == 'image/pcd'||
    // file.type == 'image/dxf'||
    // file.type == 'image/ufo'||
    // file.type == 'image/eps'||
    // file.type == 'image/ai'||
    // file.type == 'image/WMF'||
    // file.type == 'image/webp'||
    // file.type == 'image/bmp';

  // const isLt2M = file.size / 1024 / 1024 < 2;

  // if (!isJPG) {
    // this.$message.error(this.$t(`${lang}.imgFomat`));
    // return isJPG
  // }
  // if (!isLt2M) {
    // this.$message.error(this.$t(`${lang}.imgSize`));
    // return isLt2M
  // }
  // return isJPG && isLt2M;

  var fq = new window.FormData();

  fq.append('file', file[0], file[0].name)

  $.ajax({
　　　　"url": "https://bdd.bddco.cn/backend/index.php/file/images",
　　　　"type": "post",
　　　　"processData": false, // 将数据转换成对象，不对数据做处理，故 processData: false 
　　　　"contentType": false,    // 不设置数据类型
　　　　"data": fq,

　　　　success: function(data) {
　　　　　　console.log('success',data)
　　　　},
　　　　error: function(err) {
        var imgUrl = './img/icon/icon-A.png';
        $(obj.target).parent().html("<img src='"+imgUrl+"'/>")
        // console.log(1111,$(obj.target).parent())
        // $(obj.target).parent('.img-box').css('border','2px solid red');
        data.attrs[index].url = imgUrl;

        console.log('err',err)
　　　　}
　　  })

}


// 增加视频
$('.classify-video').on('mousedown', function() {
  $(this).addClass('active').siblings().removeClass('active')
  $('.control-video').show().siblings('.content-r-child').hide();

})

// 打开收起tdk
function openTdk() {
  $('.top-box-r .icon').toggleClass('active')
  $('.tdk-box').slideToggle()
}

// 预览
function preview() {
  $('.popup').show()
  $('.popup .clone-content').append($('.middle-layer').clone(true))
}

// 关闭预览
function closeClone() {
  $('.popup').hide()
}

// 保存发送数据
function save() {
    data['special-name'] = $('#special-name').val()
    data['special=url'] = $('#special-url').val()
    data.tdk.title = $('#title').val()
    data.tdk.description = $('#description').val()
    data.tdk.keywords = $('#keyword').val()
    
    console.log('data =====>',data)
}

$('body').click(function () {
  $('.direction-box').hide()
})
