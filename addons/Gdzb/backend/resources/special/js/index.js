// 返回上一页
$('.go-back').click(function() {
  history.back(-1)
})

// 颜色
var colors = ['#333333','#ffffff','#169BD5','#cccccc','#ff0000','#79AF42','#ffff00','#800080'];
for(var c=0; c<colors.length; c++){
  var div = document.createElement('div');
  div.className = 'option';
  div.style.backgroundColor = colors[c]; 
  $('.text-color .option-box').append(div)
}

$('.colorIpt').val(colors[0])

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
  e.stopPropagation();
  var option = $(e.target).parent().next();
  option.css('display') == 'none' ? option.slideDown(200).show() : option.slideUp(200).hide()

}

// 选择颜色
$('.text-color .option').click(function() {
  var idc = $(this).index();
  $('.colorBlock').css('backgroundColor', colors[idc])
  $(this).parent().slideUp().hide()
  $('.colorIpt').val(colors[idc])

  amend('color', colors[idc])
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
    amend('color', val)
  }
}

// 选择字体大小
$('.option-box .option').not('.text-color .option').click(function() {
  $(this).parent().prev().children('.value').text($(this).text())
  $(this).parent().slideUp().hide()
  amend('font-size', $(this).text()+'px')
})

// 加粗、斜体、下划线
$('.control-text .attr-2').click(function() {
  $(this).toggleClass('active');
  var ix = $(this).index(),key,val='';

  if((ix == 0) && $(this).hasClass('active')){
    key = 'font-weight';
    val = 'bold';
  }
  if((ix == 0) && !$(this).hasClass('active')){
    key = 'font-weight';
    val = '';
  }
  if((ix == 1) && $(this).hasClass('active')){
    key = 'font-style';
    val = 'italic';
  }
  if((ix == 1) && !$(this).hasClass('active')){
    key = 'font-style';
    val = '';
  }
  if((ix == 2) && $(this).hasClass('active')){
    key = 'text-decoration';
    val = 'underline';
  }
  if((ix == 2) && !$(this).hasClass('active')){
    key = 'text-decoration';
    val = '';
  }

  amend(key, val)
})

// 对齐方式
function alignType(e) {
  $(e.currentTarget).toggleClass('active').siblings().removeClass('active')
  
  var align = $('.attr-4').hasClass('active');
  if(align){
    var index = $('.attr-4.active').index(),
    aligns = ['justify','left','right','center'];
    
    amend('text-align', aligns[index])
  }else{
    amend('text-align', 'left')
  }
}

// 链接
function addLink(e) {
  var val = $(e.target).val();
  if(val){
    if(elementActive){
      for(var at in data.attrs){
        if(data.attrs[at].element == elementActive){
          editIndex = at;
          break
        }
      }
  
      if(editIndex != -1){
        data.attrs[editIndex].link = val;
      }
    }else{
      return
    }
  }
}

// 图片、视频宽高
function iptBlur(e, t) {
  if(elementActive){
    var width,height;

    switch (t) {
      case 'img-width':
        width = parseInt($(e.target).val());
        height = parseInt(width/ratio);
        
        $('.'+elementActive).css('width', width)
        $('.'+elementActive).css('height', height)
        $('.img-height').val(height)

        break;

      case 'img-height':
        height = parseInt($(e.target).val());
        width = parseInt(height*ratio);
        
        $('.'+elementActive).css('width', width)
        $('.'+elementActive).css('height', height)
        $('.img-width').val(width)

        break;

      case 'video-width':
        width = parseInt($(e.target).val());
        height = parseInt(width/ratio);
        
        $('.'+elementActive).css('width', width)
        $('.'+elementActive).css('height', height)
        $('.video-height').val(height)

        break;

      case 'video-height':
        height = parseInt($(e.target).val());
        width = parseInt(height*ratio);
        
        $('.'+elementActive).css('width', width)
        $('.'+elementActive).css('height', height)
        $('.video-width').val(width)

        break;
  
      default:
        break;
    }

    for(var t in data.attrs){
      if(data.attrs[t].element == elementActive){
        data.attrs[t].width = width+'px';
        data.attrs[t].height = height+'px';
        break
      }
    }
  }
}

// 删除元素
$('.del').click(function() {
  $('.'+elementActive).remove()
  var flag = false;
  for(var i in data.attrs){
    if(data.attrs[i].element == elementActive){
      var site = parseInt(data.attrs[i].top) + parseInt(data.attrs[i].height);
      if(site == maxHeight){
        flag = true
      }else{
        flag = false
      }

      data.attrs.splice(i,1)

      elementActive = '';
      elementActiveZIndex = 0;
      break
    }
  }

  if(flag && data.attrs.length){
    maxHeight = 0;
    for(var j in data.attrs){
      if((parseInt(data.attrs[j].top)+parseInt(data.attrs[j].height)) > maxHeight){
        maxHeight = parseInt(data.attrs[j].top)+parseInt(data.attrs[j].height)
      }
    }
  }

  if(!data.attrs.length){
    maxHeight = 0;
  }
})

// 要添加的位置
var content = $('.scroll'),
contentWidth = parseInt(content.innerWidth()),
contentHeight = parseInt(content.innerHeight()),

// 添加模板
tem = $('.template-text'),

// 添加文本的属性
textAttr = {
  element: '',
  type: '',
  top: 0,
  left: 0,
  'z-index': 0,
  width: 0,
  height: 0,
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
  top: 0,
  left: 0,
  'z-index': 0,
  width: 0,
  height: 0,
  url: '',
  link: ''
},

// 添加视频的属性{
videoAttr = {
  element: '',
  type: '',
  top: 0,
  left: 0,
  'z-index': 0,
  width: 0,
  height: 0,
  url: '',
  link: ''
},

// 编辑时临时属性集合
editObj = {},
// 正在编辑时在data数组中的位置
editIndex = -1,
// 正在编辑的元素
elementActive = '',
// 正在编辑元素的z-index
elementActiveZIndex = 0,

// 图片宽高比
ratio = 0,

// 最大高度
maxHeight = 0,

// 判断是否拖拽
ifMove = false,

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

// 重置富文本样式
function resetCss() {
  $('.colorBlock').css('background-color', colors[0])
  $('.colorIpt').val(colors[0])
  $('.font-size').text($(this).text())
  $('.attr-2').removeClass('active')
  $('.attr-4').removeClass('active')
  $('.link').val('')
}

console.log('attrs ===> ', editAttrs)

// 编辑展示
function showAttrs() {
  if(editAttrs){
    if(editAttrs.length == 1 && !editAttrs[0]){
      return
    }

    var divStyle = {},div;
    for(var k in editAttrs){
      data.attrs[k] = editAttrs[k];
      idNum = (editAttrs[k]['z-index']-0) > idNum ? editAttrs[k]['z-index']-0 : idNum;
      maxHeight = parseInt(editAttrs[k].top)+parseInt(editAttrs[k].height) > maxHeight ? parseInt(editAttrs[k].top)+parseInt(editAttrs[k].height) : maxHeight;
      
      if(editAttrs[k].type == 'text'){
        divStyle ='position:'+'absolute'
              +';top:'+editAttrs[k].top
              +';left:'+editAttrs[k].left
              +';z-index:'+editAttrs[k]['z-index']
              +';width:'+editAttrs[k].width
              +';height:'+editAttrs[k].height
              +';font-size:'+editAttrs[k]['font-size']
              +';font-style:'+editAttrs[k]['font-style']
              +';font-face:'+editAttrs[k]['font-face']
              +';font-weight:'+editAttrs[k]['font-weight']
              +';text-align:'+editAttrs[k]['text-align']
              +';text-decoration:'+editAttrs[k]['text-decoration']
              +';color:'+editAttrs[k].color
              +';link:'+editAttrs[k].link;

        div = `<div style='${divStyle}' class='text-box text-box-${idNum}' ondblclick='edit(event, "text-box-${idNum}")'>
                  <div class='direction-box' onmousedown='moveImg(event, "text-box-${idNum}")'>
                    <div class='direction top' onmousedown='move(event, "top", "text-box-${idNum}")'></div>
                    <div class='direction down' onmousedown='move(event, "down", "text-box-${idNum}")'></div>
                    <div class='direction left' onmousedown='move(event, "left", "text-box-${idNum}")'></div>
                    <div class='direction right' onmousedown='move(event, "right", "text-box-${idNum}")'></div>
                    <div class='direction topLeft' onmousedown='move(event, "topLeft", "text-box-${idNum}")'></div>
                    <div class='direction topRight' onmousedown='move(event, "topRight", "text-box-${idNum}")'></div>
                    <div class='direction downLeft' onmousedown='move(event, "downLeft", "text-box-${idNum}")'></div>
                    <div class='direction downRight' onmousedown='move(event, "downRight", "text-box-${idNum}")'></div>
                  </div>

                  <textarea type='text' class='ipt-text' onblur='onBlur(event, "text-box-${idNum}")'></textarea>
                  <pre class='pre' onclick='addMove(event, "text-box-${idNum}")' ></pre>
              </div>`;
        content.append(div)
        $('.text-box-'+idNum+ ' .pre').text(editAttrs[k].content)

        if(editAttrs[k].content){
          $('.text-box-'+idNum + ' .pre').addClass('no-border')
        }

        var heig = parseInt(editAttrs[k].top) + parseInt(editAttrs[k].height);
        aotuScroll(heig)

      }else if(editAttrs[k].type == 'img'){
        divStyle ='position:'+'absolute'
              +';top:'+editAttrs[k].top
              +';left:'+editAttrs[k].left
              +';z-index:'+editAttrs[k]['z-index']
              +';width:'+editAttrs[k].width
              +';height:'+editAttrs[k].height
              +';link:'+editAttrs[k].link;

        div = `<div style='${divStyle}' class='img-box img-box-${idNum}'>
                <div class='direction-box' onmousedown='moveImg(event)' ondblclick='loadImg(event, "img-box-${idNum}")'>
                  <div class='direction top' onmousedown='move(event, "top", "img-box-${idNum}")'></div>
                  <div class='direction down' onmousedown='move(event, "down", "img-box-${idNum}")'></div>
                  <div class='direction left' onmousedown='move(event, "left", "img-box-${idNum}")'></div>
                  <div class='direction right' onmousedown='move(event, "right", "img-box-${idNum}")'></div>
                  <div class='direction topLeft' onmousedown='move(event, "topLeft", "img-box-${idNum}")'></div>
                  <div class='direction topRight' onmousedown='move(event, "topRight", "img-box-${idNum}")'></div>
                  <div class='direction downLeft' onmousedown='move(event, "downLeft", "img-box-${idNum}")'></div>
                  <div class='direction downRight' onmousedown='move(event, "downRight", "img-box-${idNum}")'></div>
                </div>
                <input type='file' class='ipt-img' accept='image/*' onchange='changeFile(event, "img-box-${idNum}")'/>
                <div class='ele-box' onclick='addMove(event, "img-box-${idNum}")' ondblclick='loadImg(event, "img-box-${idNum}")'>
                  <img class='img' src=''/>
                </div>
              </div>`;
        content.append(div)

        if(editAttrs[k].url){
          $('.img-box-'+idNum+ ' .img').show().attr('src', editAttrs[k].url)
          $('.img-box-'+idNum+ ' .ele-box').addClass('no-bg')
        }

        var heig = parseInt(editAttrs[k].top) + parseInt(editAttrs[k].height);
        aotuScroll(heig)

      }else if(editAttrs[k].type == 'video'){
        divStyle ='position:'+'absolute'
              +';top:'+editAttrs[k].top
              +';left:'+editAttrs[k].left
              +';z-index:'+editAttrs[k]['z-index']
              +';width:'+editAttrs[k].width
              +';height:'+editAttrs[k].height
              +';link:'+editAttrs[k].link;

        div = `<div style='${divStyle}' class='video-box video-box-${idNum}'>
                <div class='direction-box' onmousedown='moveImg(event)' ondblclick='loadImg(event, "video-box-${idNum}")'>
                  <div class='direction top' onmousedown='move(event, "top", "video-box-${idNum}")'></div>
                  <div class='direction down' onmousedown='move(event, "down", "video-box-${idNum}")'></div>
                  <div class='direction left' onmousedown='move(event, "left", "video-box-${idNum}")'></div>
                  <div class='direction right' onmousedown='move(event, "right", "video-box-${idNum}")'></div>
                  <div class='direction topLeft' onmousedown='move(event, "topLeft", "video-box-${idNum}")'></div>
                  <div class='direction topRight' onmousedown='move(event, "topRight", "video-box-${idNum}")'></div>
                  <div class='direction downLeft' onmousedown='move(event, "downLeft", "video-box-${idNum}")'></div>
                  <div class='direction downRight' onmousedown='move(event, "downRight", "video-box-${idNum}")'></div>
                </div>
                <input type='file' class='ipt-img' accept='video/*' onchange='changeFile(event, "video-box-${idNum}")'/>
                <video class='video' src='' width="100%" height="auto" controls="controls" autoplay="autoplay"  onclick='addMove(event, "video-box-${idNum}")' ondblclick='loadImg(event, "video-box-${idNum}")'></video>
              </div>`;
        content.append(div)
        $('.video-box-'+idNum+ ' .video').attr('src', editAttrs[k].url)

        var heig = parseInt(editAttrs[k].top) + parseInt(editAttrs[k].height);
        aotuScroll(heig)
      }
    }

    idNum++
  }
}

showAttrs()

// 添加文本
$('.classify-text').on('mousedown', function(e) {
  e.stopPropagation()
  resetCss()

  var textObj = {};
  for(var attr in textAttr){
    textObj[attr] = textAttr[attr];
  }

  $(this).addClass('active').siblings().removeClass('active')
  $('.control-text').show().siblings().hide();
  
  textObj.width = '180px';
  textObj.height = '30px';
  tem.css({'width': textObj.width, 'height': textObj.height})

  $('.content').on('mousemove', function(e) {
    e.stopPropagation();
    
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
    // if(textObj.top > (parseInt(contentHeight) - parseInt(textObj.height))){
      // textObj.top = parseInt(contentHeight) - parseInt(textObj.height)
    // }
    if(mouseX > 0){
      tem.show()
    }

    textObj.left = textObj.left + 'px';
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
            +';z-index:'+textObj['z-index']
            +';width:'+textObj.width
            +';height:'+textObj.height
            +';font-size:'+textObj['font-size']
            +';font-style:'+textObj['font-style']
            +';font-face:'+textObj['font-face']
            +';font-weight:'+textObj['font-weight']
            +';text-align:'+textObj['text-align']
            +';text-decoration:'+textObj['text-decoration']
            +';color:'+textObj.color
            +';link:'+textObj.link;

    div = `<div style='${divStyle}' class='text-box text-box-${idNum}' ondblclick='edit(event, "text-box-${idNum}")'>
              <div class='direction-box' onmousedown='moveImg(event, "text-box-${idNum}")'>
                <div class='direction top' onmousedown='move(event, "top", "text-box-${idNum}")'></div>
                <div class='direction down' onmousedown='move(event, "down", "text-box-${idNum}")'></div>
                <div class='direction left' onmousedown='move(event, "left", "text-box-${idNum}")'></div>
                <div class='direction right' onmousedown='move(event, "right", "text-box-${idNum}")'></div>
                <div class='direction topLeft' onmousedown='move(event, "topLeft", "text-box-${idNum}")'></div>
                <div class='direction topRight' onmousedown='move(event, "topRight", "text-box-${idNum}")'></div>
                <div class='direction downLeft' onmousedown='move(event, "downLeft", "text-box-${idNum}")'></div>
                <div class='direction downRight' onmousedown='move(event, "downRight", "text-box-${idNum}")'></div>
              </div>
              
              <textarea type='text' class='ipt-text' onblur='onBlur(event, "text-box-${idNum}")'></textarea>
              <pre class='pre' onclick='addMove(event, "text-box-${idNum}")' ></pre>
          </div>`;
      
    $('.scroll').append(div)

    mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
    mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

    if((mouseX < 0 || mouseX > 375 || mouseY < 0) && maxHeight){
      textObj.top = (maxHeight+20) + 'px';
    }

    if(parseInt(textObj.top) + parseInt(textObj.height) > maxHeight){
      maxHeight = parseInt(textObj.top) + parseInt(textObj.height)
    }

    $('.text-box-'+idNum).css('top',textObj.top)
    
    var site = parseInt(textObj.top) + parseInt(textObj.height);
    aotuScroll(site)

    tier('text-box-'+idNum)

    idNum++;

    $('.content').off('mouseup')

    textObj.left = (parseInt(textObj.left)/contentWidth*100).toFixed(2) + '%';

    data.attrs.push(textObj)
    console.log(66,data)
    return false
  })
})

// 编辑文本
function edit(e, className) {
  e.stopPropagation();
  clearTimeout(timer);

  $('.direction-box').hide()
  editObj = {};

  if(!elementActive){
    elementActive = className;
    elementActiveZIndex = $('.'+className).css('z-index');
    $('.'+className).css('z-index', 999);
  }else{
    $('.'+elementActive).css('z-index', elementActiveZIndex)
    elementActive = className;
    elementActiveZIndex = $('.'+className).css('z-index');
    $('.'+className).css('z-index', 999)
  }

  for(var ay in data.attrs){
    if(data.attrs[ay].element == className){
      editIndex = ay;
    }
  }

  if(editIndex){
    for(var w in data.attrs[editIndex]){
      editObj[w] = data.attrs[editIndex][w]
    }
  }

  var val = $(e.currentTarget).children('.pre').text();

  $(e.currentTarget).children('.pre').hide();
  $(e.currentTarget).children('.ipt-text').show().val(val).focus();

  $('.colorBlock').css('background-color', editObj.color)
  $('.colorIpt').val(' ')

  if(editObj['font-size']){
    $('.font-size').text(parseInt(editObj['font-size']))
  }else{
    $('.font-size').text(12)
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

  var align = editObj['text-align'],idx;

  switch (align) {
    case 'justify': idx = 0; break;
    case 'left': idx = 1; break;
    case 'right': idx = 2; break;
    case 'center': idx = 3; break;
  }

  $('.content-r .attr-4').eq(idx).addClass('active')

  $('.control-text').show().siblings().hide();
  $('.del').hide()
  $('.control-text .del').show()

  $(e.currentTarget).css({'font-size': editObj['font-size'],'font-weight': editObj['font-weight'],'font-style': editObj['font-style'],'text-decoration': editObj['text-decoration'],'text-align': editObj['text-align'],'color': editObj['color']});
}

// 获取光标
function onFocus(e) {}

// 结束编辑
function onBlur(e,className) {
  var text = $(e.target).val();
  data.attrs[editIndex].content = text;
  data.attrs[editIndex].color = editObj['color'];
  data.attrs[editIndex]['font-size'] = editObj['font-size'];
  data.attrs[editIndex]['font-weight'] = editObj['font-weight'];
  data.attrs[editIndex]['font-style'] = editObj['font-style'];
  data.attrs[editIndex]['text-decoration'] = editObj['text-decoration'];

  $('.'+className+ ' .pre').css({'font-size': editObj['font-size'],'font-weight': editObj['font-weight'],'font-style': editObj['font-style'],'text-decoration': editObj['text-decoration'],'text-align': editObj['text-align'],'color': editObj['color']});
  $(e.target).hide().next('.pre').show().text(text);
  
  if(text){
    $('.'+className+ ' .pre').addClass('no-border')
  }else{
    $('.'+className+ ' .pre').removeClass('no-border')
  }
}


// 增加图片
$('.classify-img').on('mousedown', function() {
  resetCss()
  var imgObj = {};
  for(var attr in imgAttr){
    imgObj[attr] = imgAttr[attr];
  }

  $(this).addClass('active').siblings().removeClass('active')
  $('.control-img').show().siblings().hide();

  imgObj.width = '375px';
  imgObj.height = '120px';

  $('.img-width').val(parseInt(imgObj.width))
  $('.img-height').val(parseInt(imgObj.height))

  // var imgWidth = $('.img-width').val(),imgHeight = $('.img-height').val();
  // if(imgWidth && imgWidth>0){
    // imgObj.width = parseInt(imgWidth)+'px';
  // }

  // if(imgHeight && imgHeight>0){
    // imgObj.height = parseInt(imgHeight)+'px';
  // }

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
    
    // if(imgObj.top > (parseInt(contentHeight) - parseInt(imgObj.height))){
      // imgObj.top = parseInt(contentHeight) - parseInt(imgObj.height)
    // }

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
            +';cursor:pointer';

    div = `<div style='${divStyle}' class='img-box img-box-${idNum}'>
            <div class='direction-box' onmousedown='moveImg(event)' ondblclick='loadImg(event, "img-box-${idNum}")'>
              <div class='direction top' onmousedown='move(event, "top", "img-box-${idNum}")'></div>
              <div class='direction down' onmousedown='move(event, "down", "img-box-${idNum}")'></div>
              <div class='direction left' onmousedown='move(event, "left", "img-box-${idNum}")'></div>
              <div class='direction right' onmousedown='move(event, "right", "img-box-${idNum}")'></div>
              <div class='direction topLeft' onmousedown='move(event, "topLeft", "img-box-${idNum}")'></div>
              <div class='direction topRight' onmousedown='move(event, "topRight", "img-box-${idNum}")'></div>
              <div class='direction downLeft' onmousedown='move(event, "downLeft", "img-box-${idNum}")'></div>
              <div class='direction downRight' onmousedown='move(event, "downRight", "img-box-${idNum}")'></div>
            </div>

            <input type='file' class='ipt-img' accept='image/*' onchange='changeFile(event, "img-box-${idNum}")'/>
            <div class='ele-box' onclick='addMove(event, "img-box-${idNum}")' ondblclick='loadImg(event, "img-box-${idNum}")'>
              <img class='img' src=''/>
            </div>
          </div>`;
    $('.scroll').append(div)

    mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
    mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

    if((mouseX < 0 || mouseX > 375 || mouseY < 0) && maxHeight){
      imgObj.top = (maxHeight+20) + 'px';
    }

    if(parseInt(imgObj.top) + parseInt(imgObj.height) > maxHeight){
      maxHeight = parseInt(imgObj.top) + parseInt(imgObj.height)
    }

    $('.img-box-'+idNum).css('top',imgObj.top)
    
    var site = parseInt(imgObj.top) + parseInt(imgObj.height);
    aotuScroll(site)

    tier('img-box-'+idNum)

    idNum++;

    $('.content').off('mouseup')

    data.attrs.push(imgObj)
    console.log(77,data)
    return false
  })
})


// 增加视频
$('.classify-video').on('mousedown', function() {
  resetCss()
  var videoObj = {};
  for(var attr in videoAttr){
    videoObj[attr] = videoAttr[attr];
  }
  
  $(this).addClass('active').siblings().removeClass('active')
  $('.control-video').show().siblings('.content-r-child').hide();

  videoObj.width = '180px';
  videoObj.height = '120px';

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
    videoObj.type = 'video';

    videoObj.link = $('.control-video .video-link').val();
    videoObj.element = 'video-box-'+idNum;

    $('.video-width').val(parseInt(videoObj.width))
    $('.video-height').val(parseInt(videoObj.height))

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
            <div class='direction-box' onmousedown='moveImg(event)' ondblclick='loadImg(event, "video-box-${idNum}")'>
              <div class='direction top' onmousedown='move(event, "top", "video-box-${idNum}")'></div>
              <div class='direction down' onmousedown='move(event, "down", "video-box-${idNum}")'></div>
              <div class='direction left' onmousedown='move(event, "left", "video-box-${idNum}")'></div>
              <div class='direction right' onmousedown='move(event, "right", "video-box-${idNum}")'></div>
              <div class='direction topLeft' onmousedown='move(event, "topLeft", "video-box-${idNum}")'></div>
              <div class='direction topRight' onmousedown='move(event, "topRight", "video-box-${idNum}")'></div>
              <div class='direction downLeft' onmousedown='move(event, "downLeft", "video-box-${idNum}")'></div>
              <div class='direction downRight' onmousedown='move(event, "downRight", "video-box-${idNum}")'></div>
            </div>
            <input type='file' class='ipt-img' accept='video/*' onchange='changeFile(event, "video-box-${idNum}")'/>
            <video class='video' src='' width="100%" height="100%" controls="controls" autoplay="autoplay"  onclick='addMove(event, "video-box-${idNum}")' ondblclick='loadImg(event, "video-box-${idNum}")'></video>
          </div>`;

    $('.scroll').append(div)

    mouseX=parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
    mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

    if((mouseX < 0 || mouseX > 375 || mouseY < 0) && maxHeight){
      videoObj.top = (maxHeight+20) + 'px';
    }

    if(parseInt(videoObj.top) + parseInt(videoObj.height) > maxHeight){
      maxHeight = parseInt(videoObj.top) + parseInt(videoObj.height)
    }

    $('.video-box-'+idNum).css('top',videoObj.top)
    
    var site = parseInt(videoObj.top) + parseInt(videoObj.height);
    aotuScroll(site)

    tier('video-box-'+idNum)

    idNum++;

    $('.content').off('mouseup')

    data.attrs.push(videoObj)
    console.log(88,data)
    return false
  })
})

// 单击显示移动缩放工具
function addMove(e, className) {
  e.stopPropagation();

  ifMove = false;
  tier(className)
  
  clearTimeout(timer);
  timer = setTimeout(function () {
    e.stopPropagation();
    
    $('.direction-box').hide()
    $('.'+className).find('.direction-box').show()
    
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

    $('.content-r .link').val(editObj.link)

    if(editObj.type == 'text'){
      $('.colorBlock').css('background-color', editObj.color)
      $('.colorIpt').val(editObj.color)

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

      var align = editObj['text-align'],idx;

      switch (align) {
        case 'justify': idx = 0; break;
        case 'left': idx = 1; break;
        case 'right': idx = 2; break;
        case 'center': idx = 3; break;
      }

      $('.content-r .attr-4').eq(idx).addClass('active')

      $('.control-text').show().siblings().hide();
      $('.del').hide()
      $('.control-text .del').show()
    }

    if(editObj.type == 'img'){
      $('.img-width').val(parseInt(editObj.width))
      $('.img-height').val(parseInt(editObj.height))
      // $('.img-link').val(parseInt(editObj.link))

      ratio = (parseInt(editObj.width)/parseInt(editObj.height)).toFixed(4)

      $('.control-img').show().siblings().hide();
      $('.del').hide()
      $('.control-img .del').show()
    }
    
    if(editObj.type == 'video'){
      $('.video-width').val(parseInt(editObj.width))
      $('.video-height').val(parseInt(editObj.height))

      ratio = (parseInt(editObj.width)/parseInt(editObj.height)).toFixed(4)

      $('.control-video').show().siblings().hide();
      $('.del').hide()
      $('.control-video .del').show()
      $('.video-url').val(editObj.url)
    }
  }, 300);
}

// 取消移动/选中状态
function closeMove(e) {
  // $(e.target).hide()
}

// 拖拽移动
function moveImg(e, className) {
  if($(e.target).hasClass('direction-box')){
    ifMove = false;

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
    
    $(content).mousemove(function (e) {
      ifMove = true;
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
      // if(editObj.top > (parseInt(contentHeight) - parseInt(editObj.height))){
      //   editObj.top = parseInt(contentHeight) - parseInt(editObj.height)
      // }

      editObj.left = editObj.left + 'px';
      editObj.top = editObj.top + 'px';

      box.css({'left':editObj.left, 'top':editObj.top})
    })

    $(content).mouseup(function () {  
      if(ifMove){
        data.attrs[editIndex].left = (parseInt(editObj.left)/contentWidth*100).toFixed(2) + '%';
      }else{
        data.attrs[editIndex].left = editObj.left;
      }
      
      data.attrs[editIndex].top = editObj.top;

      if(parseInt(editObj.top) + parseInt(editObj.height) > maxHeight){
        maxHeight = parseInt(editObj.top) + parseInt(editObj.height)
      }

      $(content).off('mousemove')
    })
  }

}

// 拖拽缩放
function move(e, direction, className) {
  var box = $('.'+className),
      posX = parseInt(box.css('left')),
      posY = parseInt(box.css('top')),
      posW = parseInt(box.css('width')),
      posH = parseInt(box.css('height')),
      // posHrefYH = posY + parseInt(posH/2),
      // posHrefXW = posX + parseInt(posW/2),
      posYH = posY + posH,
      posXW = posX + posW,
      type = className.split('-box-')[0],   //元素类型，文本，图片，视频
      rate = (posW/posH).toFixed(4),        //宽高比
      widthK,heightk,topK,mouseXK,mouseYK,
      zoomType = 1;   //缩放类型，1为自然缩放，2为图片，3为视频; 

  if(type == 'img' && data.attrs[editIndex].url){
    zoomType = 2
  }

  if(type == 'video' && data.attrs[editIndex].url){
    zoomType = 3;
    posW = parseInt(box.children('video').css('width'));
    posH = parseInt(box.children('video').css('height'));
    posYH = posY + posH,
    posXW = posX + posW,
    rate = (posW/posH).toFixed(4);
  }
  

  if(zoomType == 1) {
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
  
          box.css({'top':editObj.top, 'height':editObj.height})
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
          returnData(type)
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

          if(parseInt(editObj.top) < 0){
            editObj.top = 0
          }
  
          box.css({'left':editObj.left, 'width':editObj.width})
        })
  
        $('.content').mouseup(function () {
          returnData(type)
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
          returnData(type)
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
  
          box.css({'left':editObj.left, 'top':editObj.top, 'width':editObj.width, 'height':editObj.height})
        })
  
        $('.content').mouseup(function () {
          returnData(type)
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
  
          box.css({'top':editObj.top, 'width':editObj.width, 'height':editObj.height})
        })
  
        $('.content').mouseup(function () {
          returnData(type)
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
          
          editObj.width = (posXW-parseInt(editObj.left))+'px';
          editObj.height = (mouseY-parseInt(editObj.top))+'px';
          editObj.left = editObj.left + 'px';
  
          box.css({'left':editObj.left, 'width':editObj.width, 'height':editObj.height})
        })
  
        $('.content').mouseup(function () {
          returnData(type)
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
  
          box.css({'width':editObj.width, 'height':editObj.height})
        })
  
        $('.content').mouseup(function () {
          returnData(type)
          $('.content').off('mousemove')
        })
    }
  } else if(zoomType == 2) {
    switch (direction) {
      case 'top':
        $('.content').mousemove(function(e) {
          mouseY = parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标
          editObj.top = mouseY;
          
          if(editObj.top < 0){
            editObj.top = 0
          }
          if(editObj.top > posYH){
            editObj.top = posYH
          }

          // topK = editObj.top+'px';
          editObj.top = editObj.top + 'px';
          editObj.height = (posYH - parseInt(editObj.top)) + 'px';
          editObj.width = parseInt((posYH - parseInt(editObj.top))/rate) + 'px';

          if((posYH - editObj.top)*rate >= (contentWidth-posX)){
            editObj.width = (contentWidth-posX)+'px';
            editObj.height = parseInt((contentWidth-posX)/rate)+'px';
          }

          if(parseInt(editObj.width) >= (contentWidth - parseInt(posX))){
            editObj.width = (contentWidth - parseInt(posX)) + 'px';
            editObj.height = parseInt(parseInt(editObj.width)/rate) + 'px';
            editObj.top = (posYH - parseInt(editObj.height)) + 'px';
          }

          // if(!widthK){
          //   editObj.height = (posYH-editObj.top)+'px';
          //   editObj.width = (posYH-editObj.top)*rate+'px';
          //   editObj.left = posHrefXW-(posYH-editObj.top)*rate/2+'px';
          //   editObj.top = editObj.top+'px';
          // }

          // if(posHrefXW <= contentWidth/2){
          //   if(parseInt(editObj.left) <= 0){
          //     editObj.left = 0;

          //     if(!editObj.left && !widthK && !topK){
          //       widthK = parseInt(box.css('width'))+'px';
          //       topK = parseInt(box.css('top'))+'px';
          //       editObj.width = widthK;
          //       editObj.top = topK;

          //       editObj.height = (widthK)*rate+'px';
          //     }
          //   }

          //   if(widthK){
          //     editObj.width = widthK;
          //     editObj.top = topK;
          //   }
          // }else{
          //   console.log(2222)
          // }

          // if(editObj.width >= ())

          box.css({'top':editObj.top, 'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
          $('.content').off('mousemove')
        })

        break;

      case 'down':
        $('.content').mousemove(function(e) {
          mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标
          editObj.height = (mouseY-parseInt(editObj.top))+'px';
          editObj.width = parseInt((mouseY-parseInt(editObj.top))*rate)+'px';

          if((mouseY-parseInt(editObj.top))*rate >= (contentWidth-posX)){
            editObj.width = (contentWidth-posX)+'px';
            editObj.height = parseInt((contentWidth-posX)/rate)+'px';
          }

          box.css({'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
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

          editObj.height = box.find('.img').css('height');

          box.css({'left':editObj.left, 'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
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

          editObj.height = box.find('.img').css('height');

          box.css({'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
          $('.content').off('mousemove')
        })

        break;

      case 'topLeft':
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

          if(editObj.left > posXW){
            editObj.left = posXW
          }

          editObj.height = (posYH - parseInt(editObj.top)) + 'px';
          editObj.width = parseInt((posYH - parseInt(editObj.top))*rate) + 'px';
          editObj.left = (posXW - parseInt(editObj.width)) + 'px';

          if(parseInt(editObj.left) < 0){
            editObj.left = 0;
            editObj.width = posXW + 'px';
            editObj.height = parseInt(posXW/rate) + 'px';
            editObj.top = (posYH - parseInt(editObj.height)) + 'px';
          }

          box.css({'left':editObj.left, 'top':editObj.top, 'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
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

          editObj.height = (posYH - parseInt(editObj.top)) + 'px';
          editObj.width = parseInt((posYH - parseInt(editObj.top))*rate) + 'px';
          
          if((posYH - editObj.top)*rate >= (contentWidth-posX)){
            editObj.width = (contentWidth-posX)+'px';
            editObj.height = parseInt((contentWidth-posX)/rate)+'px';
          }
          
          if(parseInt(editObj.width) >= (contentWidth - parseInt(posX))){
            editObj.width = (contentWidth - parseInt(posX)) + 'px';
            editObj.height = parseInt(parseInt(editObj.width)/rate) + 'px';
            editObj.top = (posYH - parseInt(editObj.height)) + 'px';
          }
          
          editObj.top = parseInt(editObj.top) + 'px';
          box.css({'top':editObj.top, 'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
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
          
          editObj.left = editObj.left+'px';
          editObj.width = (posXW-parseInt(editObj.left))+'px';
          editObj.height = parseInt(parseInt(editObj.width)/rate)+'px';

          box.css({'left':editObj.left, 'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
          $('.content').off('mousemove')
        })

        break;

      case 'downRight':
        $('.content').mousemove(function(e) {
          mouseX = parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
          mouseY = parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

          if(!mouseXK){
            mouseXK = mouseX;
            mouseYK = mouseYK;
          }

          editObj.width = (mouseX-parseInt(posX)) + 'px';
          if(parseInt(editObj.width) > (contentWidth - parseInt(posX))){
            editObj.width = (contentWidth - parseInt(posX)) + 'px'
          }
          editObj.height = parseInt(parseInt(editObj.width)/rate) + 'px';

          box.css({'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
          $('.content').off('mousemove')
        })

        break;
    }
  } else {
    switch (direction) {
      case 'top':
        $('.content').mousemove(function(e) {
          mouseY = parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标
          editObj.top = mouseY;
          
          if(editObj.top < 0){
            editObj.top = 0
          }
          if(editObj.top > posYH){
            editObj.top = posYH
          }

          // topK = editObj.top+'px';
          editObj.top = editObj.top + 'px';
          editObj.height = (posYH - parseInt(editObj.top)) + 'px';
          editObj.width = parseInt((posYH - parseInt(editObj.top))*rate) + 'px';

          if((posYH - editObj.top)*rate >= (contentWidth-posX)){
            editObj.width = (contentWidth-posX)+'px';
            editObj.height = parseInt((contentWidth-posX)/rate)+'px';
          }

          if(parseInt(editObj.width) >= (contentWidth - parseInt(posX))){
            editObj.width = (contentWidth - parseInt(posX)) + 'px';
            editObj.height = parseInt(editObj.width)/rate + 'px';
            editObj.top = (posYH - parseInt(editObj.height)) + 'px';
          }

          box.css({'top':editObj.top, 'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
          $('.content').off('mousemove')
        })

        break;

      case 'down':
        $('.content').mousemove(function(e) {
          mouseY=parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标
          editObj.height = (mouseY-parseInt(editObj.top))+'px';
          editObj.width = parseInt((mouseY-parseInt(editObj.top))*rate)+'px';

          if((mouseY-parseInt(editObj.top))*rate >= (contentWidth-posX)){
            editObj.width = (contentWidth-posX)+'px';
            editObj.height = parseInt((contentWidth-posX)/rate)+'px';
          }

          box.css({'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
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
          editObj.height = parseInt(parseInt(editObj.width)/rate)+'px';

          box.css({'left':editObj.left, 'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
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

          editObj.height = parseInt(parseInt(editObj.width)/rate) + 'px';

          box.css({'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
          $('.content').off('mousemove')
        })

        break;

      case 'topLeft':
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

          if(editObj.left > posXW){
            editObj.left = posXW
          }

          editObj.height = (posYH - parseInt(editObj.top)) + 'px';
          editObj.width = parseInt((posYH - parseInt(editObj.top))*rate) + 'px';
          editObj.left = (posXW - parseInt(editObj.width)) + 'px';

          if(parseInt(editObj.left) < 0){
            editObj.left = 0;
            editObj.width = posXW + 'px';
            editObj.height = parseInt(posXW/rate) + 'px';
            editObj.top = (posYH - parseInt(editObj.height)) + 'px';
          }

          box.css({'left':editObj.left, 'top':editObj.top, 'width':editObj.width, 'height':editObj.height})

        })

        $('.content').mouseup(function () {
          returnData(type)
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

          editObj.height = (posYH - parseInt(editObj.top)) + 'px';
          editObj.width = parseInt((posYH - parseInt(editObj.top))*rate) + 'px';
          
          if((posYH - editObj.top)*rate >= (contentWidth-posX)){
            editObj.width = (contentWidth-posX)+'px';
            editObj.height = parseInt((contentWidth-posX)/rate)+'px';
          }
          
          if(parseInt(editObj.width) >= (contentWidth - parseInt(posX))){
            editObj.width = (contentWidth - parseInt(posX)) + 'px';
            editObj.height = parseInt(parseInt(editObj.width)/rate) + 'px';
            editObj.top = (posYH - parseInt(editObj.height)) + 'px';
          }
          
          editObj.top = parseInt(editObj.top) + 'px';
          box.css({'top':editObj.top, 'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
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
          
          editObj.left = editObj.left+'px';
          editObj.width = (posXW-parseInt(editObj.left))+'px';
          editObj.height = parseInt(parseInt(editObj.width)/rate)+'px';

          box.css({'left':editObj.left, 'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
          $('.content').off('mousemove')
        })

        break;

      case 'downRight':
        $('.content').mousemove(function(e) {
          mouseX = parseInt(e.pageX-content.offset().left); //获取当前鼠标相对content的X坐标
          mouseY = parseInt(e.pageY-content.offset().top); //获取当前鼠标相对img的Y坐标

          if(!mouseXK){
            mouseXK = mouseX;
            mouseYK = mouseYK;
          }

          editObj.width = (mouseX-parseInt(posX)) + 'px';
          if(parseInt(editObj.width) > (contentWidth - parseInt(posX))){
            editObj.width = (contentWidth - parseInt(posX)) + 'px'
          }
          editObj.height = parseInt(parseInt(editObj.width)/rate) + 'px';

          box.css({'width':editObj.width, 'height':editObj.height})
        })

        $('.content').mouseup(function () {
          returnData(type)
          $('.content').off('mousemove')
        })

        break;
    }
  }

  // 返回结果
  function returnData(type) {
    if((editObj.left).indexOf('%') == -1){
      data.attrs[editIndex].left = (parseInt(editObj.left)/contentWidth*100).toFixed(2) + '%';
    }else{
      data.attrs[editIndex].left = editObj.left;
    }

    data.attrs[editIndex].top = editObj.top;
    data.attrs[editIndex].width = editObj.width;
    data.attrs[editIndex].height = editObj.height;

    if(type == 'img'){
      $('.img-width').val(parseInt(editObj.width))
      $('.img-height').val(parseInt(editObj.height))
    }

    if(type == 'video'){ 
      $('.video-width').val(parseInt(editObj.width))
      $('.video-height').val(parseInt(editObj.height))
    }

    return
  }
}

// 双击上传
function loadImg(e,className) {
  e.stopPropagation();
  clearTimeout(timer);

  tier(className)

  $('.direction-box').hide()

  $('.'+className+ ' .ipt-img').click()
}

function changeFile(obj,className) {
  var that = this,
      file = obj.target.files;

  for(var at in data.attrs){
    if(data.attrs[at].element == className){
      editIndex = at;
      break
    }
  }
  
  var type = data.attrs[editIndex].type;

  if(type == 'img'){
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
            var imgUrl = res.data.url;

            $('.'+className).children('.ele-box').addClass('no-bg')
            $('.'+className).find('.img').show().attr('src', imgUrl)
            $('.'+className).css('height', 'auto')
            data.attrs[editIndex].url = imgUrl;

            var img = new Image();
            img.src = imgUrl;
            img.onload = function() {
              ratio = img.width/img.height
              data.attrs[editIndex].width = $('.'+className).css('width');
              data.attrs[editIndex].height = parseInt(parseInt($('.'+className).css('width'))/ratio)+'px';
              
              var heig = parseInt($('.'+className).css('top')) + parseInt(data.attrs[editIndex].height);
              aotuScroll(heig)
            }

  　　　　},
  　　　　error: function(err) {
            console.log('err',err)
  　　　　}
  　 })

     $('.'+className).find('.ipt-img').hide()
  }else if(type == 'video'){
    const isJPG =
      file.type == 'video/mp4'||
      file.type == 'video/raw'

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
  　　　　"url": "/backend/file/videos",
  　　　　"type": "post",
  　　　　"processData": false, // 将数据转换成对象，不对数据做处理，故 processData: false 
  　　　　"contentType": false,    // 不设置数据类型
  　　　　"data": fq,
  
  　　　　success: function(res) {
            var videoUrl = res.data.url;

            $('.'+className).children('.video').attr('src', videoUrl)
            $('.'+className).addClass('no-bg')
            $('.'+className).css('height', 'auto')
            data.attrs[editIndex].url = videoUrl;
            $('.video-url').val(videoUrl)

            // var img = new Image();
            // img.src = imgUrl;
            // img.onload = function() {
            //   ratio = img.width/img.height
            //   data.attrs[editIndex].width = $('.'+className).css('width');
            //   data.attrs[editIndex].height = parseInt(parseInt($('.'+className).css('width'))/ratio)+'px';
              
            //   var heig = parseInt($('.'+className).css('top')) + parseInt(data.attrs[editIndex].height);
            //   aotuScroll(heig)
            // }
  　　　　},
  　　　　error: function(err) {
            console.log('err',err)
  　　　　}
  　 })
  }
}

// 增加区域
function addArea() {
  var height = parseInt($('.scroll').css('height'))+150;
  
  $('.scroll').css('height', height + 'px')

  if(height > 545){
    var scroll = height - contentHeight;
    $('.scroll-box').scrollTop(scroll)
  }
}

// 添加元素时滚动
function aotuScroll(heig) {
  var diff = parseInt($('.scroll').css('height'))-120;
  if(heig >= diff){
    var height = heig+150;

    $('.scroll').css('height', height + 'px')

    var scroll = height - contentHeight;
    $('.scroll-box').scrollTop(scroll)
  }
}

// 修改数据
function amend(cl,val) {
  // cl: 要修改的属性，val:要修改的值
  if(elementActive){
    for(var at in data.attrs){
      if(data.attrs[at].element == elementActive){
        editIndex = at;
        break
      }
    }

    if(editIndex != -1){
      $('.'+elementActive).css(cl, val)
      data.attrs[editIndex][cl] = val;
      
      if(cl == 'color' || cl == 'font-size' || 'text-align'){
        $('.'+elementActive+ ' pre').css(cl, val)
      }
    }
  }else{
    return
  }

}

// 切换层级
function tier(ele) {
  if(!elementActive){
    elementActive = ele;
    elementActiveZIndex = $('.'+ele).css('z-index');
    $('.'+ele).css('z-index', 999);
  }else{
    $('.'+elementActive).css('z-index', elementActiveZIndex)
    elementActive = ele;
    elementActiveZIndex = $('.'+ele).css('z-index');
    $('.'+ele).css('z-index', 999)
  }
}

// 去除层级和选中状态
$('body').click(function (e) {
  $('.option-box').hide()
  if(!($(e.target).parents().hasClass('content-r') || $(e.target).parents().hasClass('content-m'))){
      $('.direction-box').hide()
      elementActive = '';
      elementActiveZIndex = -1;
  }

  // if(elementActive){
  //   $('.'+elementActive).css('z-index', elementActiveZIndex)
  //   elementActive = '';
  //   elementActiveZIndex = 0;
  // }
})

// 打开收起tdk
function openTdk() {
  $('.top-box-r .icon').toggleClass('active')
  $('.tdk-box').slideToggle()
}

// 预览
function preview() {
  // alert('稍等，页面制作中。。。')
  // return

  $('.direction-box').hide()
  $('.popup').show()
  $('.popup .clone-content').append($('.scroll').clone(false))
}

// 关闭预览
function closeClone() {
  $('.popup .clone-content .scroll').remove()
  $('.popup').hide()
}

// data.attrs = null;

// 保存发送数据
function save() {
  $('.direction-box').hide()

  if(!$('#special-url').val()){
    alert('保存失败，URL不能为空')
    return
  }

  if(!data['attrs'].length){
    data['attrs'] = null;
  }

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
      console.log(msg)
      if(msg.code==200){
        alert('保存成功')
      }else{
        alert(msg.message)
      }
    },
    error: function(err) {
      console.log(err)
    }
  })
}
