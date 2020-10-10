<!DOCTYPE html>
<html>
	<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script src="https://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>  
    <style type="text/css">
      body,div,img,video,a{
        padding: 0;
        margin: 0;
        box-sizing: border-box;
      }
      body{
        max-width: 640px;
        margin: 0 auto;
      }

      @font-face{
        font-family: Frutiger;
        src: url('./font/Frutiger-LT-45-Light.ttf');
      }

      @font-face{
        font-family: Helvetica;
        src: url('./font/Helvetica-Neue-2.ttf');
      }

      #container{
        position: relative;
      }
      div,img,video,a{
        position: absolute;
      }
      a,img,video{
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        color: inherit;
      }
      
      .div-text{
        padding: 4px;
        overflow: hidden;
      }
      .div-img, .div-video{
        font-size: 0;
      }

      a,.text{
        width: 100%;
        height: 100%;
        font-size: inherit;
        text-align: inherit;
        font-style: inherit;
        color: inherit;
        font-weight: inherit;
        text-decoration: inherit;
        font-family: inherit;
        white-space: pre-wrap;
        overflow: hidden;
      }
      .tem{
        position: absolute;
        top: 0;
        left: 0;
        z-index: -1;
      }
      .div-video img{
        position: absolute;
        border: 1px solid red;
      }
      canvas{
        border: 1px solid red
      }
    </style>
  </head>
  <body>
    <!-- 版本号：6.3 -->
    <div id="container"></div>

    <script>
      function getData() {
        var screenWidth = document.body.offsetWidth,
            ratio =  (375/screenWidth).toFixed(4);
        
        // 兼容性
        function selfAdaption(size, type) {
          var result;
          if(type){
            result = Math.ceil(parseInt(size)/ratio)+'px';
          }else{
            result = parseInt(parseInt(size)/ratio)+'px';
          }

          return result
        }

        var url = location.href;
        if(url.indexOf('preview') == -1){
          url = url.split('?url=')[1];
          // 非预览

          $.ajax({
            url: 'https://gdzb.bddco.cn/api/gdzb/v1/special/details?url='+url,
            type: 'get',
            data: {},
            dataType: 'json',
            success: function (res) {
              console.log(res)
              successFn(res) 
            }
          })
        }else{
          // 预览数据
          try {
            var postDatas = {};
                eval('postDatas = <?php echo sprintf("%s", isset($_POST["params"]) ? $_POST["params"] : "{}"); ?>');
          } catch(err) {
            postDatas = {};
          }

          var postData = JSON.parse(sessionStorage.getItem('pageData'));

          console.log('postData',postData)

          if(postData){
            var re = {data:{title:'',description:'',keywords:'',data:[]}};
            
            if(postData.hasOwnProperty('tdk')){ 
              re.data.title = postData.tdk.title;
              re.data.description = postData.tdk.description;
              re.data.keywords = postData.tdk.keywords;
            }
              
            for(var s in postData.attrs){
              (re.data.data).push(postData.attrs[s])
            }

            successFn(re)
          }
        }

        // 处理http
        function dispose(url) {
          var disposeUrl;
          // 没有http，也没有https的，恒得利官网加https，非恒得利官网加http
          if((url.indexOf('http') == -1) && (url.indexOf('https') == -1)){
            if((url.indexOf('bddco') == -1) && (url.indexOf('kadart.com') == -1) && (url.indexOf('hdlbdd.com') == -1) && (url.indexOf('gdzb.bddco') == -1)){
              disposeUrl = 'http://'+url;

              console.log(888,disposeUrl)
            }else{
              disposeUrl = 'https://'+url;
            }
          }

          // 有http，没有https且为恒得利官网的，加https
          if((url.indexOf('http') != -1) && (url.indexOf('https') == -1)){
            if((url.indexOf('bddco') != -1) && (url.indexOf('kadart.com') != -1) && (url.indexOf('hdlbdd.com') != -1) && (url.indexOf('gdzb.bddco') != -1)){
              url = url.split('http://')[1];
              disposeUrl = 'https://'+url;
            }
          }

          return disposeUrl
        }

        // 请求成功渲染数据
        function successFn(res) {
          var box = document.getElementById('container'),
              obj = res.data.data,urlHttps;

          $(box).children().filter("video").each(function(){ 
              this.pause(); 
              $(this).remove(); 
          }); 
          $(box).empty();

          if(res.data.title){
            $(document).attr("title", res.data.title);
          }

          if(res.data.description){
            $("meta[name='viewport']").after(
              `<meta name="description" content=${res.data.description}>`
            );
          }

          if(res.data.keywords){
            $("meta[name='viewport']").after(
              `<meta name="keywords" content=${res.data.keywords}>`
            );
          }

          for(var i=0; i< obj.length; i++){
            urlHttps = '';
            if(obj[i].type == 'text'){
              var div = document.createElement('div');
              div.setAttribute('class', 'div-text div-text-'+i)
              div.innerText = obj[i].content;
              box.appendChild(div)

              div.style.width = selfAdaption(obj[i].width);
              div.style.height = selfAdaption(obj[i].height);
              div.style.top = selfAdaption(obj[i].top);
              div.style.left = obj[i].left;
              div.style.color = obj[i].color;
              div.style['font-family'] = obj[i]['font-family'];
              div.style['font-size'] = obj[i]['font-size'];
              div.style['font-weight'] = obj[i]['font-weight'];
              div.style['font-style'] = obj[i]['font-style'];
              div.style['text-decoration'] = obj[i]['text-decoration'];
              div.style['text-align'] = obj[i]['text-align'];
              div.style['z-index'] = obj[i]['z-index'];

              if(obj[i].link){
                urlHttps = dispose(obj[i].link)
                div.style.padding = 0;
                $(div).html(`<a href='${urlHttps}'><div class='text' style='padding: 4px'>${obj[i].content}</div></a>`)
              }
            }

            if(obj[i].type == 'img'){
              var style = `
                "width: ${selfAdaption(obj[i].width)};
                height: ${selfAdaption(obj[i].height, 'height')};
                top: ${selfAdaption(obj[i].top)};
                left: ${obj[i].left};
                z-index: ${obj[i]['z-index']}"`,

              img = `
                  <div class="div-img div-img-${i}" style=${style}>
                      <img class=img-${i} src='${obj[i].url}'/>
                  </div>`;

              $(box).append(img)

              if(obj[i].link){
                urlHttps = dispose(obj[i].link)
                $('.div-img-'+i+ ' img').wrap(`<a href="${urlHttps}"></a>`)
              }
            }

            if(obj[i].type == 'video'){
              var style = `
                width: ${selfAdaption(obj[i].width)};
                height: ${selfAdaption(obj[i].height, 'height')};
                top: ${selfAdaption(obj[i].top)};
                left: ${obj[i].left};
                z-index: ${obj[i]['z-index']}`;

              var video = $(`<div class="div-video div-video-${i}" style="${style}">
                            <video class="video video-${i}" controls src=${obj[i].url}></video>
                          </div>`);

              $(box).append(video)

              var w = $('.div-video-'+i).css('width'),
                  h = $('.div-video-'+i).css('height');

              $('.video-'+i).css({'width': w,"height": h})
            }
          }

          // var videoBox = $('.div-video');
          // for(var j=0; j<videoBox.length; j++){
          //   (function() {
          //     var arg = j;
          //     var vi = videoBox[arg].children[0];
              
          //     vi.setAttribute('crossOrigin', 'anonymous');//处理跨域
          //     vi.addEventListener('loadeddata', function () {
          //       var canvas = document.createElement("canvas");
          //       $('#container').append(canvas);
          //       canvas.width = 200;
          //       canvas.height = 200;
          //       var dataURL = '';

          //       canvas.getContext("2d").drawImage(vi, 0, 0, parseInt(w), parseInt(h)); //绘制canvas

          //       var img = document.createElement("img");
          //       img.src = canvas.toDataURL("image/png");//转换成base64图片，地址拿出来就可以直接使用

          //       $(videoBox[arg]).append(img);

          //       // $('.video-'+i).attr('poster', dataURL)

          //     });

          //   })()
          // }

        }
      }

      getData()

      window.onresize = function(){
        if((navigator.platform).indexOf('Win') != -1){
          getData()
        }
      };

    </script>
  </body>
</html>