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

// 删除元素
$('.del').click(function() {
  $('.'+editElementClass).hide()
  var i = $('.'+editElementClass).css('z-index');
  data.attrs.splice(i,1)
})

// 要添加的位置
var content = $('.middle-layer'),
contentWidth = parseInt(content.innerWidth()),
contentHeight = parseInt(content.innerHeight()),

// 添加模板
tem = $('.template-text'),

// 添加文本的属性
textAttr = {
  type: '',
  top: '',
  left: '',
  'z-index': 0,
  width: '',
  height: '',
  'font-face': '',
  'font-size': '',
  'font-weight': '',
  'font-style': '',
  'text-decoration': '',
  'text-align': '',
  color: '',
  content: '',
  link: ''
},

// 添加图片、视频的属性{
imgAttr = {
  type: '',
  top: '',
  'z-index': 0,
  left: '',
  width: '',
  height: '',
  url: '',
  link: ''
},

// 编辑时临时属性集合
editObj = {},
oldIndex = '',
editElementClass = '',

// 鼠标位置
mouseX = '',
mouseY = '',
// 默认行高
lineHeight = '',
// 排序
idNum = 0,
// 样式集合
divStyle = '',
// 返回数据集合
data = {
  'special-name' : '',
  'special=url': '',
  'tdk': {'title': '','description': '','keywords':''},
  'attrs': []
},
timer;

// 添加文本
$('.classify-text').on('mousedown', function() {
  var textObj = {};
  for(var attr in textAttr){
    textObj[attr] = textAttr[attr];
  }

  $(this).addClass('active').siblings().removeClass('active')
  $('.control-text').show().siblings().hide();
  
  ;
  textObj.width = '160px';
  textObj.height = '24px';
  tem.css({'width': textObj.width, 'height': textObj.height})

  $('.content').on('mousemove', function(e) {
    mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
    mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标
    
    textObj.left=mouseX - parseInt(textObj.width)/2;
    textObj.top=mouseY - parseInt(textObj.height)/2;

    if(textObj.left < 0){
      textObj.left = 0
    }
    if(textObj.left > (parseInt(contentWidth) - parseInt(textObj.width))){
      textObj.left = parseInt(contentWidth) - parseInt(textObj.width)
    }
    if(textObj.top < 0){
      textObj.top = 0
    }
    if(textObj.top > (parseInt(contentHeight) - parseInt(textObj.height))){
      textObj.top = parseInt(contentHeight) - parseInt(textObj.height)
    }
    if(mouseX > 0){
      tem.show()
    }

    textObj.left = (textObj.left/contentWidth*100).toFixed(2) + '%';
    textObj.top = textObj.top + 'px';

    tem.css({'left': textObj.left, 'top': textObj.top})
  })

  $('.content').on('mouseup', function(e) {
    $('.content').off('mousemove')
    $('.template-text').hide()
    textObj['z-index'] = idNum;
    textObj.type = 'text';
    textObj.link = $('.control-text .ipt-link').val();
    if(textObj.link){
      textObj.type = 'text-A'
    }

    divStyle ='position:'+'absolute'
            +';top:'+textObj.top
            +';left:'+textObj.left
            +';width:'+textObj.width
            +';height:'+textObj.height
            +';background-color:#fff'
            +';z-index:'+textObj['z-index'];

    div = `<div style='${divStyle}' class='text-box text-box-${idNum}'>
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
              <pre class='pre' onclick='addMove(event, "text-box-${idNum}")' ondblclick='edit(event)'></pre>
          </div>`;
    $('.middle-layer').append(div)

    idNum++;
    $('.content').off('mouseup')

    data.attrs.push(textObj)
    return false
  })
})

// 编辑文本
function edit(e) {
  e.stopPropagation();
  clearTimeout(timer);

  var index = $(e.target).css('z-index'),
      ar = data.attrs[index];
      oldIndex = index;
  
  for(var q in ar){
    editObj[q] = ar[q]
  }
  
  $(e.target).hide().prev('.ipt-text').show().focus();
}

// 获取光标
function onFocus(e) {}

// 输入文本
function inputText(e) {
  var align = $('.attr-4').hasClass('active');
  if(align){
    var index = $('.attr-4.active').index(),
        aligns = ['justify','left','right','center'];
    
    editObj['text-align'] = aligns[index];
  }

  editObj['color'] = $('.colorBlock').css('backgroundColor');
  editObj['font-size'] = $('.font-size').text()+'px';
  editObj['font-weight'] = $('.attr-bold').hasClass('active') ? 'bold' : '';
  editObj['font-style'] = $('.attr-i').hasClass('active') ? 'italic' : '';
  editObj['text-decoration'] = $('.attr-underline').hasClass('active') ? 'underline' : '';

  $(e.target).parent().css({'font-size': editObj['font-size'],'font-weight': editObj['font-weight'],'font-style': editObj['font-style'],'text-decoration': editObj['text-decoration'],'text-align': editObj['text-align'],'color': editObj['color'],});
}

// 结束编辑
function onBlur(e,className) {
  var index = $(e.target).parent().css('z-index');
  var text = $(e.target).val();
  data.attrs[index].content = text;
  data.attrs[index].color = editObj['color'];
  data.attrs[index]['font-size'] = editObj['font-size'];
  data.attrs[index]['font-weight'] = editObj['font-weight'];
  data.attrs[index]['text-decoration'] = editObj['text-decoration'];

  $(e.target).hide().next('.pre').show().text(text);
  if(text){
    $('.'+className).addClass('no-border')
  }else{
    $('.'+className).removeClass('no-border')
  }
}


// 增加图片
$('.classify-img').on('mousedown', function() {
  var imgObj = {};
  for(var attr in imgAttr){
    imgObj[attr] = imgAttr[attr];
  }

  $(this).addClass('active').siblings().removeClass('active')
  $('.control-img').show().siblings().hide();

  imgObj.width = '140px';
  imgObj.height = '140px';

  var imgWidth = $('.img-width').val(),imgHeight = $('.img-height').val();
  if(imgWidth && imgWidth>0){
    imgObj.width = parseInt(imgWidth)+'px';
  }

  if(imgHeight && imgHeight>0){
    imgObj.height = parseInt(imgHeight)+'px';
  }

  tem.css({'width': imgObj.width, 'height': imgObj.height})

  $('.content').on('mousemove', function(e) {
    mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
    mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

    imgObj.left=mouseX - parseInt(imgObj.width)/2;
    imgObj.top=mouseY - parseInt(imgObj.height)/2;
    
    if(imgObj.left < 0){
      imgObj.left = 0
    }
    if(imgObj.left > (parseInt(contentWidth) - parseInt(imgObj.width))){
      imgObj.left = parseInt(contentWidth) - parseInt(imgObj.width)
    }
    if(imgObj.top < 0){
      imgObj.top = 0
    }
    if(imgObj.top > (parseInt(contentHeight) - parseInt(imgObj.height))){
      imgObj.top = parseInt(contentHeight) - parseInt(imgObj.height)
    }
    if(mouseX > 0){
      tem.show()
    }

    imgObj.left = (imgObj.left/contentWidth*100).toFixed(2) + '%';
    imgObj.top = imgObj.top + 'px';
    
    tem.css({'left': imgObj.left, 'top': imgObj.top})

  })

  $('.content').on('mouseup', function(e) {
    $('.content').off('mousemove')
    $('.template-text').hide()

    imgObj['z-index'] = idNum;
    imgObj.type = 'img';

    divStyle =
            'top:'+imgObj.top
            +';left:'+imgObj.left
            +';z-index:'+imgObj['z-index']
            +';width:'+imgObj.width
            +';height:'+imgObj.height
            +';background:#fff url('+baseStaticUrl+'img/icon/load.png) no-repeat center'
            +';background-size:60% 60%'
            +';cursor:pointer';

    div = `<div style='${divStyle}' class='img-box img-box-${idNum}'>
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
            <input type='file' class='ipt-img' accept='image/*' onchange='changeFile(event)'/>
            <img class='img' srt='' onclick='addMove(event, "img-box-${idNum}")' ondblclick='loadImg(event)'/>
          </div>`;
    $('.middle-layer').append(div)

    idNum++;
    $('.content').off('mouseup')

    data.attrs.push(imgObj)
    return false
  })
})

// 单击显示移动缩放工具
function addMove(e, eleClass) {
  clearTimeout(timer);
  timer = setTimeout(function () {
    $('.direction-box').hide()
    $(e.target).siblings('.direction-box').show();

    editElementClass = eleClass;
    var index = $(e.target).parent().css('z-index'),
        ar = data.attrs[index];
    
    for(var q in ar){
      editObj[q] = ar[q]
    }

    console.log(123123,editObj)
    
    var type = editObj.type;
    if(type == 'text'){
      $('.control-text').show().siblings().hide();
      $('.del').hide()
      $('.control-text .del').show()
    }else if(type == 'img'){
      $('.control-img').show().siblings().hide();
      $('.del').hide()
      $('.control-img .del').show()
    }else{
      $('.control-video').show().siblings().hide();
      $('.del').hide()
      $('.control-video .del').show()
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

      editObj.left=mouseX - clientX;
      editObj.top=mouseY - clientY;

      if(editObj.left < 0){
        editObj.left = 0
      }
      if(editObj.left > (parseInt(contentWidth) - parseInt(editObj.width))){
        editObj.left = parseInt(contentWidth) - parseInt(editObj.width)
      }
      if(editObj.top < 0){
        editObj.top = 0
      }
      if(editObj.top > (parseInt(contentHeight) - parseInt(editObj.height))){
        editObj.top = parseInt(contentHeight) - parseInt(editObj.height)
      }

      editObj.left = (editObj.left/contentWidth*100).toFixed(2) + '%';
      editObj.top = editObj.top + 'px';

      box.css({'left':editObj.left, 'top':editObj.top})
    })

    $(e.target).mouseup(function () {
      data.attrs[index].left = editObj.left;
      data.attrs[index].top = editObj.top;

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

      function returnData() {
        data.attrs[index].left = editObj.left;
        data.attrs[index].top = editObj.top;
        data.attrs[index].width = editObj.width;
        data.attrs[index].height = editObj.height;
      }
      
  switch (direction) {
    case 'top':
      $('.content').mousemove(function(e) {
        mouseY = parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

        editObj.top = mouseY;
        
        if(editObj.top < 0){
          editObj.top = 0
        }
        if(editObj.top > posYH){
          editObj.top = posYH-4
        }

        editObj.height = (posYH-editObj.top)+'px';
        editObj.top = editObj.top + 'px';

        box.css({'top':editObj.top,'height':editObj.height})
      })

      $('.content').mouseup(function () {
        returnData()
        $('.content').off('mousemove')
      })

      break;

    case 'down':
      $('.content').mousemove(function(e) {
        mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标
        editObj.height = (mouseY-parseInt(editObj.top))+'px';

        box.css({'height':editObj.height})
      })

      $('.content').mouseup(function () {
        returnData()
        $('.content').off('mousemove')
      })

      break;

    case 'left':
      $('.content').mousemove(function(e) {
        mouseX = parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标

        editObj.left = mouseX;

        if(editObj.left < 0){
          editObj.left = 0
        }
        if(editObj.left > posXW){
          editObj.left = posXW
        }

        editObj.left = editObj.left + 'px';
        editObj.width = (posXW-parseInt(editObj.left))+'px';

        box.css({'left':editObj.left,'width':editObj.width})
      })

      $('.content').mouseup(function () {
        returnData()
        $('.content').off('mousemove')
      })

      break;

      case 'right':
      $('.content').mousemove(function(e) {
        mouseX = parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标

        editObj.width = (mouseX-parseInt(editObj.left));
        if(editObj.width > (contentWidth - parseInt(editObj.left))){
          editObj.width = (contentWidth - parseInt(editObj.left)) + 'px'
        }

        box.css({'width':editObj.width})
      })

      $('.content').mouseup(function () {
        returnData()
        $('.content').off('mousemove')
      })

      break;

    case 'topLeft':
      $('.content').mousemove(function(e) {
        mouseX = parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
        mouseY = parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

        editObj.left = mouseX;
        editObj.top = mouseY;
        
        if(editObj.top < 0){
          editObj.top = 0
        }
        if(editObj.top > posYH){
          editObj.top = posYH-4
        }

        if(editObj.left < 0){
          editObj.left = 0
        }
        if(editObj.left+4 > posXW){
          editObj.left = posXW -4
        }

        editObj.width = (posXW-parseInt(editObj.left))+'px';
        editObj.height = (posYH-editObj.top)+'px';
        editObj.top = editObj.top + 'px';
        editObj.left = editObj.left + 'px';

        box.css({'left':editObj.left,'top':editObj.top,'width':editObj.width,'height':editObj.width})
      })

      $('.content').mouseup(function () {
        returnData()
        $('.content').off('mousemove')
      })

      break;

    case 'topRight':
      $('.content').mousemove(function(e) {
        mouseX = parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
        mouseY = parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

        editObj.top = mouseY;
        
        if(editObj.top < 0){
          editObj.top = 0
        }
        if(editObj.top > posYH){
          editObj.top = posYH
        }
        
        var width = (mouseX-parseInt(editObj.left))+'px';
        var height = (posYH-editObj.top)+'px';
        editObj.top = editObj.top + 'px';

        box.css({'top':editObj.top,'width':width,'height':height})
      })

      $('.content').mouseup(function () {
        returnData()
        $('.content').off('mousemove')
      })

      break;

    case 'downLeft':
      $('.content').mousemove(function(e) {
        mouseX = parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
        mouseY = parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标
        
        editObj.left = mouseX;
        
        if(editObj.left < 0){
          editObj.left = 0
        }
        if(editObj.left > posXW){
          editObj.left = posXW
        }
        
        var width = (posXW-parseInt(editObj.left))+'px';
        var height = (mouseY-parseInt(editObj.top))+'px';
        editObj.left = editObj.left + 'px';

        box.css({'left':editObj.left,'width':width,'height':height})
      })

      $('.content').mouseup(function () {
        returnData()
        $('.content').off('mousemove')
      })

      break;

    case 'downRight':
      $('.content').mousemove(function(e) {
        mouseX = parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
        mouseY = parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

        var width = (mouseX-parseInt(editObj.left))+'px';
        var height = (mouseY-parseInt(editObj.top))+'px';

        box.css({'width':width,'height':height})
      })

      $('.content').mouseup(function () {
        returnData()
        $('.content').off('mousemove')
      })
  }
  return false
}

// 双击上传
function loadImg(e) {
  e.stopPropagation();
  clearTimeout(timer);

  $(e.target).prev('.ipt-img').show().click()
}

function changeFile(obj,className) {
  var that = this,
      file = obj.target.files,
      index = $(obj.target).parent().css('z-index');
  
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
　　　　"url": "/backend/file/images",
　　　　"type": "post",
　　　　"processData": false, // 将数据转换成对象，不对数据做处理，故 processData: false 
　　　　"contentType": false,    // 不设置数据类型
　　　　"data": fq,

　　　　success: function(data) {
　　　　　　console.log('success',data)
　　　　},
　　　　error: function(err) {
        var imgUrl = baseStaticUrl+'/img/icon/icon-A.png';
        $(obj.target).siblings('.img').attr('src', imgUrl)
        $('.'+className).addClass('no-bg')
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
  var id = $('#special-id').val();

  var param = {};

  param['data'] = data['attrs'];
  param['name'] = $('#special-name').val()
  param['url'] = $('#special-url').val()
  param['title'] = $('#title').val()
  param['keywords'] = $('#keyword').val()
  param['description'] = $('#description').val()

  $.ajax({
    type: "POST",
    url: 'edit?id='+id,
    dataType: 'json',
    data: {'Special': param, '_csrf-backend': $('meta[name=csrf-token]').attr("content")},
    success: function(msg) {
      // if(msg.error == 0) {
      //   //window.location.reload();
      // } else {
      //   alert(msg.msg);
      // }
    }
  });
}

$('body').click(function () {
  $('.direction-box').hide()
})
