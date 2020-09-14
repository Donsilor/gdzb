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

  if(val && reg.test(val)){
    $('.colorBlock').css('background-color', val)
  }
}

// 加粗、斜体、下划线
$('.control-text .attr-2').click(function() {
  $(this).toggleClass('active')
})

// 对齐方式
function alignType(e) {
  $(e.target).toggleClass('active').siblings().removeClass('active')
}

// 删除元素
$('.del').click(function() {
  $('.'+elementActive).remove()
  for(var i in data.attrs){
    if(data.attrs[i].element == elementActive){
      data.attrs.splice(i,1)
      elementActive = '';
      elementActiveZIndex = 0;
      break
    }
  }
})

// 要添加的位置
var content = $('.middle-layer'),
contentWidth = parseInt(content.innerWidth()),
contentHeight = parseInt(content.innerHeight()),

// 添加模板
tem = $('.template-text'),

// 添加文本的属性
textAttr = {
  element: '',
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

// 添加图片的属性{
imgAttr = {
  element: '',
  type: '',
  top: '',
  'z-index': 0,
  left: '',
  width: '',
  height: '',
  url: '',
  link: ''
},

// 添加视频的属性{
videoAttr = {
  element: '',
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
// 正在编辑时在data数组中的位置
editIndex = '',
// 正在编辑的元素
elementActive = '',
// 正在编辑元素的z-index
elementActiveZIndex = 0,

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
  textObj.width = '180px';
  textObj.height = '30px';
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
    textObj.link = $('.control-text .text-link').val();

    textObj.element = 'text-box-'+idNum;

    divStyle ='position:'+'absolute'
            +';top:'+textObj.top
            +';left:'+textObj.left
            +';width:'+textObj.width
            +';height:'+textObj.height
            +';z-index:'+textObj['z-index'];

    div = `<div style='${divStyle}' class='text-box text-box-${idNum}'>
              <div class='direction-box' onmousedown='moveImg(event, "text-box-${idNum}")'>
                <div class='direction top' onmousedown='move(event, "top")'></div>
                <div class='direction down' onmousedown='move(event, "down")'></div>
                <div class='direction left' onmousedown='move(event, "left")'></div>
                <div class='direction right' onmousedown='move(event, "right")'></div>
                <div class='direction topLeft' onmousedown='move(event, "topLeft")'></div>
                <div class='direction topRight' onmousedown='move(event, "topRight")'></div>
                <div class='direction downLeft' onmousedown='move(event, "downLeft")'></div>
                <div class='direction downRight' onmousedown='move(event, "downRight")'></div>
              </div>
              <textarea type='text' class='ipt-text' onInput='inputText(event)' onfocus='onFocus(event)' onblur='onBlur(event, "text-box-${idNum}")'></textarea>
              <pre class='pre' onclick='addMove(event, "text-box-${idNum}")' ondblclick='edit(event, "text-box-${idNum}")'></pre>
          </div>`;
    $('.middle-layer').append(div)

    idNum++;
    $('.content').off('mouseup')

    data.attrs.push(textObj)
    return false
  })
})

// 编辑文本
function edit(e, className) {
  e.stopPropagation();
  clearTimeout(timer);

  var clas = className;

  for(var at in data.attrs){
    if(data.attrs[at].element == clas){
      editIndex = at;
    }
  }

  if(editIndex){
    for(var q in data.attrs[editIndex]){
      editObj[q] = data.attrs[editIndex][q]
    }
  }
  
  $(e.target).hide().prev('.ipt-text').css('display', 'block').focus();
  // $(e.target).hide().prev('.ipt-text').show().focus();
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
  var text = $(e.target).val();
  data.attrs[editIndex].content = text;
  data.attrs[editIndex].color = editObj['color'];
  data.attrs[editIndex]['font-size'] = editObj['font-size'];
  data.attrs[editIndex]['font-weight'] = editObj['font-weight'];
  data.attrs[editIndex]['font-style'] = editObj['font-style'];
  data.attrs[editIndex]['text-decoration'] = editObj['text-decoration'];

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

  $('.img-width').val(parseInt(imgObj.width))
  $('.img-height').val(parseInt(imgObj.height))

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

    imgObj.link = $('.control-img .img-link').val();
    imgObj.element = 'img-box-'+idNum;

    $('.img-width').val(parseInt(imgObj.width))
    $('.img-height').val(parseInt(imgObj.height))

    divStyle =
            'top:'+imgObj.top
            +';left:'+imgObj.left
            +';z-index:'+imgObj['z-index']
            +';width:'+imgObj.width
            +';height:'+imgObj.height
            +';background:#fff url('+baseStaticUrl+'img/icon/icon-load.jpg) no-repeat center'
            +';background-size:60% 60%'
            +';border:1px solid #ccc'
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
            <input type='file' class='ipt-img' accept='image/*' onchange='changeFile(event, "img-box-${idNum}")'/>
            <img class='img' src='' onclick='addMove(event, "img-box-${idNum}")' ondblclick='loadImg(event, "img-box-${idNum}")'/>
          </div>`;
    $('.middle-layer').append(div)

    idNum++;
    $('.content').off('mouseup')

    data.attrs.push(imgObj)
    return false
  })
})

// 单击显示移动缩放工具
function addMove(e, className) {
  if(!elementActive){
    elementActive = className;
    elementActiveZIndex = $('.'+className).css('z-index');
    $('.'+className).css('z-index', 9999);
  }else{
    $('.'+elementActive).css('z-index', elementActiveZIndex)
    elementActive = className;
    elementActiveZIndex = $('.'+className).css('z-index');
    $('.'+className).css('z-index', 9999)
  }

  clearTimeout(timer);
  timer = setTimeout(function () {
    $('.direction-box').hide()
    $(e.target).siblings('.direction-box').show().removeClass('no-border');
    // $(e.target).parent().removeClass('no-border');

    for(var at in data.attrs){
      if(data.attrs[at].element == className){
        editIndex = at;
      }
    }

    if(editIndex){
      for(var q in data.attrs[editIndex]){
        editObj[q] = data.attrs[editIndex][q]
      }
    }

    if(editObj.type == 'text'){
      $('.colorBlock').css('background-color', editObj.color)
      $('.colorIpt').val(' ')

      if(editObj['font-size']){
        $('.font-size').text(parseInt(editObj['font-size']))
      }

      if(editObj['font-weight']){
        $('.attr-bold').addClass('active')
      }else{
        $('.attr-bold').removeClass('active')
      }

      if(editObj['font-style']){
        $('.attr-i').addClass('active')
      }else{
        $('.attr-i').removeClass('active')
      }

      if(editObj['text-decoration']){
        $('.attr-underline').addClass('active')
      }else{
        $('.attr-underline').removeClass('active')
      }

      var align = editObj['text-align'];
      if(align == 'left'){
        $('.align-left').addClass('active')
      }
      if(align == 'right'){
        $('.align-right').addClass('active')
      }
      if(align == 'center'){
        $('.align-center').addClass('active')
      }
      if(align == 'justify'){
        $('.align-justify').addClass('active')
      }


      // $('.text-link').val(editObj.link)
    }

    if(editObj.type == 'img'){
      $('.img-width').val(parseInt(editObj.width))
      $('.img-height').val(parseInt(editObj.height))
      // $('.img-link').val(parseInt(editObj.link))
    }
    
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
function moveImg(e, className) {
  if($(e.target).hasClass('direction-box')){
    for(var at in data.attrs){
      if(data.attrs[at].element == className){
        editIndex = at;
      }
    }

    if(editIndex){
      for(var q in data.attrs[editIndex]){
        editObj[q] = data.attrs[editIndex][q]
      }
    }

    var box = $(e.target).parent(),
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
      data.attrs[editIndex].left = editObj.left;
      data.attrs[editIndex].top = editObj.top;

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
      posYH = posY + posH,
      posXW = posX + posW;

      // 返回结果
      function returnData() {
        data.attrs[editIndex].left = (parseInt(editObj.left)/contentWidth*100).toFixed(2) + '%';
        data.attrs[editIndex].top = editObj.top;
        data.attrs[editIndex].width = editObj.width;
        data.attrs[editIndex].height = editObj.height;

        $('.img-width').val(parseInt(editObj.width))
        $('.img-height').val(parseInt(editObj.height))

        return
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

        editObj.width = (mouseX-parseInt(posX)) + 'px';
        if(parseInt(editObj.width) > (contentWidth - parseInt(posX))){
          editObj.width = (contentWidth - parseInt(posX)) + 'px'
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
          editObj.top = posYH
        }

        if(editObj.left < 0){
          editObj.left = 0
        }
        if(editObj.left > posXW){
          editObj.left = posXW
        }

        editObj.width = (posXW-parseInt(editObj.left))+'px';
        editObj.height = (posYH-editObj.top)+'px';
        editObj.top = editObj.top + 'px';
        editObj.left = editObj.left + 'px';

        box.css({'left':editObj.left,'top':editObj.top,'width':editObj.width,'height':editObj.height})
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
        
        editObj.width = (mouseX-parseInt(posX)) + 'px';
        if(parseInt(editObj.width) > (contentWidth - parseInt(posX))){
          editObj.width = (contentWidth - parseInt(posX)) + 'px'
        }

        editObj.height = (posYH-editObj.top)+'px';
        editObj.top = editObj.top + 'px';

        box.css({'top':editObj.top,'width':editObj.width,'height':editObj.height})
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

        editObj.width = (mouseX-parseInt(posX)) + 'px';
        if(parseInt(editObj.width) > (contentWidth - parseInt(posX))){
          editObj.width = (contentWidth - parseInt(posX)) + 'px'
        }

        editObj.height = (mouseY-parseInt(editObj.top))+'px';

        box.css({'width':editObj.width,'height':editObj.height})
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
      file = obj.target.files;

  for(var at in data.attrs){
    if(data.attrs[at].element == className){
      editIndex = at;
    }
  }
  
  const isJPG =
  file.type == 'image/jpeg'||
    file.type == 'image/png'||
    file.type == 'image/jpg'||
    file.type == 'image/gif'||
    file.type == 'image/tiff'||
    file.type == 'image/raw'||
    file.type == 'image/pcx'||
    file.type == 'image/tga'||
    file.type == 'image/exif'||
    file.type == 'image/fpx'||
    file.type == 'image/svg'||
    file.type == 'image/psd'||
    file.type == 'image/cdr'||
    file.type == 'image/pcd'||
    file.type == 'image/dxf'||
    file.type == 'image/ufo'||
    file.type == 'image/eps'||
    file.type == 'image/ai'||
    file.type == 'image/WMF'||
    file.type == 'image/webp'||
    file.type == 'image/bmp';

  const isLt2M = file.size / 1024 / 1024 < 2;

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

　　　　success: function(res) {
　　　　　　console.log('success',res)

          var imgUrl = res.data.url;
          $(obj.target).siblings('.img').attr('src', imgUrl)
          $('.'+className).addClass('no-bg')
          data.attrs[editIndex].url = imgUrl;
　　　　},
　　　　error: function(err) {
          console.log('err',err)
　　　　}
　 })
}


// 增加视频
$('.classify-video').on('mousedown', function() {
  return
  var videoObj = {};
  for(var attr in videoAttr){
    videoObj[attr] = videoAttr[attr];
  }
  
  $(this).addClass('active').siblings().removeClass('active')
  $('.control-video').show().siblings('.content-r-child').hide();

  videoObj.width = '140px';
  videoObj.height = '140px';

  var vidWidth = $('.video-width').val(),vidHeight = $('.video-height').val();
  if(vidWidth && vidWidth>0){
    videoObj.width = parseInt(vidWidth)+'px';
  }

  if(vidHeight && vidHeight>0){
    videoObj.height = parseInt(vidHeight)+'px';
  }

  tem.css({'width': videoObj.width, 'height': videoObj.height})

  $('.content').on('mousemove', function(e) {
    mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
    mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

    videoObj.left=mouseX - parseInt(videoObj.width)/2;
    videoObj.top=mouseY - parseInt(videoObj.height)/2;
    
    if(videoObj.left < 0){
      videoObj.left = 0
    }
    if(videoObj.left > (parseInt(contentWidth) - parseInt(videoObj.width))){
      videoObj.left = parseInt(contentWidth) - parseInt(videoObj.width)
    }
    if(videoObj.top < 0){
      videoObj.top = 0
    }
    if(videoObj.top > (parseInt(contentHeight) - parseInt(videoObj.height))){
      videoObj.top = parseInt(contentHeight) - parseInt(videoObj.height)
    }
    if(mouseX > 0){
      tem.show()
    }

    videoObj.left = (videoObj.left/contentWidth*100).toFixed(2) + '%';
    videoObj.top = videoObj.top + 'px';
    
    tem.css({'left': videoObj.left, 'top': videoObj.top})

  })

  $('.content').on('mouseup', function(e) {
    $('.content').off('mousemove')
    $('.template-text').hide()

    videoObj['z-index'] = idNum;
    videoObj.type = 'img';

    // videoObj.link = $('.control-img .ipt-link').val();

    divStyle =
            'top:'+videoObj.top
            +';left:'+videoObj.left
            +';z-index:'+videoObj['z-index']
            +';width:'+videoObj.width
            +';height:'+videoObj.height
            +';background:#fff url('+baseStaticUrl+'img/icon/icon-load.jpg) no-repeat center'
            +';background-size:60% 60%'
            +';border:1px solid #ccc'
            +';cursor:pointer';

    div = `<div style='${divStyle}' class='video-box video-box-${idNum}'>
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
            <input type='file' class='ipt-img' accept='image/*' onchange='changeFile(event, "video-box-${idNum}")'/>
            <video class='video' src=''  onclick='addMove(event, "video-box-${idNum}")' ondblclick='loadImg(event, "video-box-${idNum}")'></video>
          </div>`;
    $('.middle-layer').append(div)

    idNum++;
    $('.content').off('mouseup')

    data.attrs.push(videoObj)
    return false
  })
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
      alert('保存成功')
      // if(msg.error == 0) {
      //   //window.location.reload();
      // } else {
      //   alert(msg.msg);
      // }
    }
  });
}

$('body').click(function (e) {
  $('.direction-box').hide()

  if(elementActive){
    if(!$(e.target).parent().hasClass(elementActive)){
      $('.'+elementActive).css('z-index', elementActiveZIndex)
      elementActive = '';
      elementActiveZIndex = 0;
    }
  }
})
